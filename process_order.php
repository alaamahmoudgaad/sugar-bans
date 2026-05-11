<?php
session_start();
require_once 'includes/db.php';

if (isset($_POST['submit_order'])) {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        header("Location: menu.php");
        exit();
    }
    
    $user_id = $_SESSION['user_id'] ?? 1;
    $total_price = $_POST['total_price_hidden']; 
    $order_type = $_POST['order_state']; 
    $order_type = $_POST['order_state']; 
$note = htmlspecialchars($_POST['notes']);

$address = ($order_type === 'delivery') 
    ? htmlspecialchars($_POST['address']) 
    : null;

if ($order_type === 'delivery' && empty(trim($_POST['address']))) {

    echo "<script>
    alert('Please enter delivery address');
    window.history.back();
    </script>";

    exit();
}
    try {
        $connect->beginTransaction();

        $sql = "INSERT INTO orders (user_id, order_price, order_type, note, address) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$user_id, $total_price, $order_type, $note, $address]);
        $order_id = $connect->lastInsertId();

 foreach ($_SESSION['cart'] as $item) {

    $id = $item['id'];
    $qty = $item['quantity'] ?? 1;

    if ($item['type'] === 'product') {

        $stmtPrice = $connect->prepare("
            SELECT product_price 
            FROM products 
            WHERE product_id = ?
        ");

        $stmtPrice->execute([$id]);

        $product = $stmtPrice->fetch();

        $price = $product['product_price'];

        $stmtDet = $connect->prepare("
            INSERT INTO order_details 
            (order_id, product_id, quantity, price) 
            VALUES (?, ?, ?, ?)
        ");

        $stmtStock = $connect->prepare("
            UPDATE products 
            SET stock = stock - ? 
            WHERE product_id = ?
        ");

    } else {

        $stmtPrice = $connect->prepare("
            SELECT box_price 
            FROM boxes 
            WHERE box_id = ?
        ");

        $stmtPrice->execute([$id]);

        $box = $stmtPrice->fetch();

        $price = $box['box_price'];

        $stmtDet = $connect->prepare("
            INSERT INTO order_boxes 
            (order_id, box_id, quantity, box_price) 
            VALUES (?, ?, ?, ?)
        ");

        $stmtStock = $connect->prepare("
            UPDATE boxes 
            SET stock = stock - ? 
            WHERE box_id = ?
        ");
    }

    $stmtDet->execute([
        $order_id,
        $id,
        $qty,
        $price
    ]);

    $stmtStock->execute([$qty, $id]);
}

        $connect->commit();
        
        unset($_SESSION['cart']);
        
    
      echo "<script>
alert(` Order Created Successfully

Order ID: #$order_id
Customer: {$_POST['fname']} {$_POST['lname']}
Order Type: $order_type
Products Total: $total_price EGP
Delivery Fee: " . ($order_type === 'delivery' ? 50 : 0) . " EGP

Your order has been saved successfully `);
window.location.href = 'index.php';
</script>";
exit();
        exit();

    } catch (Exception $e) {
        $connect->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
