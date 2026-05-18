
<?php 
// PDO   ===> PHP Data Object 

$dns = "mysql:host=localhost;dbname=sugarbans";
$user = "root";
$pass = "";
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
);

try{

    $connect = new PDO($dns,$user,$pass,$option);
    $connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e){
    echo "Failed To Connect With DB" . $e->getMessage();
}


function getBoxStock($connect, $box_id)
{
    $stmt = $connect->prepare("
        SELECT MIN(FLOOR(p.stock / bp.quantity)) AS box_stock
        FROM box_products bp
        JOIN products p ON p.product_id = bp.product_id
        WHERE bp.box_id = ?
    ");

    $stmt->execute([$box_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['box_stock'] ?? 0;
}


function getItemStock($connect, $id, $type)
{
    if ($type === 'product') {

        $stmt = $connect->prepare("
            SELECT stock 
            FROM products 
            WHERE product_id = ?
        ");

        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['stock'] ?? 0;
    }

    return getBoxStock($connect, $id);
}
?>         