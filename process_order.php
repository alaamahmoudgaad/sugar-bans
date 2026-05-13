<?php
session_start();
require_once 'includes/db.php';

if (isset($_POST['cancel_order'])) {
    unset($_SESSION['cart']);
    $connect = null;

    $_SESSION['order_cancelled'] = true;

    header("Location: index.php");
    exit;
}

if (!isset($_POST['submit_order'])) {
    $connect = null;
    exit;
}

if (empty($_SESSION['cart'])) {
    $connect = null;

    $_SESSION['error_msg'] = "Your cart is empty. Please add items first.";

    header("Location: cart.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$fname = trim($_POST['fname'] ?? '');
$lname = trim($_POST['lname'] ?? '');

if (
    strtolower(trim($fname)) !== strtolower(trim($_SESSION['user_fname'])) ||
    strtolower(trim($lname)) !== strtolower(trim($_SESSION['user_lname']))
) {
    $connect = null;

    $_SESSION['error_msg'] = "You cannot change account information";

    header("Location: cart.php");
    exit;
}

$phone = trim($_POST['phone'] ?? '');

if (!preg_match('/^01[0-9]{9}$/', $phone)) {
    $connect = null;

    $_SESSION['error_msg'] = "Invalid phone number";

    header("Location: cart.php");
    exit;
}

$order_type = $_POST['order_state'] ?? '';

if (!in_array($order_type, ['pickup', 'delivery'])) {
    $connect = null;

    $_SESSION['error_msg'] = "Invalid order type";

    header("Location: cart.php");
    exit;
}

$note = trim($_POST['notes'] ?? '');

$address = null;

if ($order_type === 'delivery') {

    $addressInput = trim($_POST['address'] ?? '');

    if ($addressInput === '') {
        $connect = null;

        $_SESSION['error_msg'] = "Please enter delivery address";

        header("Location: cart.php");
        exit;
    }

    $address = $addressInput;
}

$productIds = [];
$boxIds = [];

foreach ($_SESSION['cart'] as $item) {

    if ($item['type'] === 'product') {
        $productIds[] = $item['id'];
    } else {
        $boxIds[] = $item['id'];
    }
}

$productsMap = [];
$boxesMap = [];

if (!empty($productIds)) {

    $in = str_repeat('?,', count($productIds) - 1) . '?';

    $stmt = $connect->prepare("
        SELECT product_id, product_price, stock
        FROM products
        WHERE product_id IN ($in)
    ");

    $stmt->execute($productIds);

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $p) {
        $productsMap[$p['product_id']] = $p;
    }
}

if (!empty($boxIds)) {

    $in = str_repeat('?,', count($boxIds) - 1) . '?';

    $stmt = $connect->prepare("
        SELECT box_id, box_price, stock
        FROM boxes
        WHERE box_id IN ($in)
    ");

    $stmt->execute($boxIds);

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $b) {
        $boxesMap[$b['box_id']] = $b;
    }
}

$total_price = 0;

try {

    $connect->beginTransaction();

    $stmt = $connect->prepare("
        INSERT INTO orders (
            user_id,
            order_price,
            order_type,
            note,
            address
        )
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $user_id,
        0,
        $order_type,
        $note,
        $address
    ]);

    $order_id = $connect->lastInsertId();

    foreach ($_SESSION['cart'] as $item) {

        $id = $item['id'];
        $qty = max(1, (int)$item['quantity']);

        if ($item['type'] === 'product') {

            $product = $productsMap[$id] ?? null;

            if (!$product) {
                $connect->rollBack();

                $_SESSION['error_msg'] = "Product not found";

                header("Location: cart.php");
                exit;
            }

            $stmtStock = $connect->prepare("
                UPDATE products
                SET stock = stock - ?
                WHERE product_id = ? AND stock >= ?
            ");

            $ok = $stmtStock->execute([$qty, $id, $qty]);

            if (!$ok || $stmtStock->rowCount() == 0) {

                $connect->rollBack();

                echo "<script>
                        alert('Product out of stock');
                        window.history.back();
                      </script>";

                exit;
            }

            $price = $product['product_price'];

            $stmtDet = $connect->prepare("
                INSERT INTO order_details (
                    order_id,
                    product_id,
                    quantity,
                    price
                )
                VALUES (?, ?, ?, ?)
            ");

            $stmtDet->execute([
                $order_id,
                $id,
                $qty,
                $price
            ]);

        } else {

            $box = $boxesMap[$id] ?? null;

            if (!$box) {
                $connect->rollBack();

                $_SESSION['error_msg'] = "Box not found";

                header("Location: cart.php");
                exit;
            }

            $stmtStock = $connect->prepare("
                UPDATE boxes
                SET stock = stock - ?
                WHERE box_id = ? AND stock >= ?
            ");

            $ok = $stmtStock->execute([$qty, $id, $qty]);

            if (!$ok || $stmtStock->rowCount() == 0) {

                $connect->rollBack();

                echo "<script>
                        alert('Box out of stock');
                        window.history.back();
                      </script>";

                exit;
            }

            $price = $box['box_price'];

            $stmtDet = $connect->prepare("
                INSERT INTO order_boxes (
                    order_id,
                    box_id,
                    quantity,
                    box_price
                )
                VALUES (?, ?, ?, ?)
            ");

            $stmtDet->execute([
                $order_id,
                $id,
                $qty,
                $price
            ]);
        }

        $total_price += $price * $qty;
    }

    $update = $connect->prepare("
        UPDATE orders
        SET order_price = ?
        WHERE order_id = ?
    ");

    $update->execute([
        $total_price,
        $order_id
    ]);

    $connect->commit();

    unset($_SESSION['cart']);

    $_SESSION['order_success'] = [
        'order_id' => $order_id,
        'fname' => $fname,
        'lname' => $lname,
        'order_type' => $order_type,
        'total_price' => $total_price
    ];

    header("Location: index.php");
    exit;

} catch (Exception $e) {

    if ($connect->inTransaction()) {
        $connect->rollBack();
    }

    header("Location: 404.php");
    exit;

} finally {

    $connect = null;
}
?>