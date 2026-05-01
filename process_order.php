<?php
session_start();
require_once 'includes/db.php';

if (isset($_POST['submit_order'])) {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        header("Location: menu.php");
        exit();
    }
    
    $user_id = $_SESSION['user_id'] ?? null;
    $total_price = $_POST['total_price_hidden']; 
    $order_type = $_POST['order_state']; 
    $note = htmlspecialchars($_POST['notes']);

    $address = ($order_type === 'delivery') ? htmlspecialchars($_POST['address']) : null;

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
                $stmtDet = $connect->prepare("INSERT INTO order_details (order_id, product_id, quantity) VALUES (?, ?, ?)");
                $stmtStock = $connect->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");
            } else {
                $stmtDet = $connect->prepare("INSERT INTO order_boxes (order_id, box_id, quantity) VALUES (?, ?, ?)");
                $stmtStock = $connect->prepare("UPDATE boxes SET stock = stock - ? WHERE box_id = ?");
            }
            
            $stmtDet->execute([$order_id, $id, $qty]);
            $stmtStock->execute([$qty, $id]);
        }

        $connect->commit();
        
        unset($_SESSION['cart']);
        
    
        echo "<script>
            alert('Order placed successfully! Order ID: #$order_id');
            window.location.href = 'index.php';
        </script>";
        exit();

    } catch (Exception $e) {
        $connect->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>