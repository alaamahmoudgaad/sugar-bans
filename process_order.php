<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_msg'] = "Please login first to make order";
    header("Location: login.php");
    exit;
}
if (isset($_POST['cancel_order'])) {
    unset($_SESSION['cart']);
    $_SESSION['order_cancelled'] = true;

    header("Location: index.php");
    exit;
}

if (!isset($_POST['submit_order'])) {
    exit;
}

if (empty($_SESSION['cart'])) {
    $_SESSION['error_msg'] = "Your cart is empty. Please add items first.";
    header("Location: cart.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$fname = trim($_POST['fname'] ?? '');
$lname = trim($_POST['lname'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if (
    strtolower(trim($fname)) !== strtolower(trim($_SESSION['user_fname'])) ||
    strtolower(trim($lname)) !== strtolower(trim($_SESSION['user_lname']))
){
    $_SESSION['error_msg'] = "You cannot change account information";
    header("Location: cart.php");
    exit;
}

if (!preg_match('/^01[0-9]{9}$/', $phone)) {
    $_SESSION['error_msg'] = "Invalid phone number";
    header("Location: cart.php");
    exit;
}

$order_type = $_POST['order_state'] ?? '';
if (!in_array($order_type, ['pickup', 'delivery'])) {
    $_SESSION['error_msg'] = "Invalid order type";
    header("Location: cart.php");
    exit;
}

$note = trim($_POST['notes'] ?? '');
$address = null;

if ($order_type === 'delivery') {
    $address = trim($_POST['address'] ?? '');

    if ($address === '') {
        $_SESSION['error_msg'] = "Please enter delivery address";
        header("Location: cart.php");
        exit;
    }
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

$boxesMap = [];
if (!empty($boxIds)) {

    $in = str_repeat('?,', count($boxIds) - 1) . '?';

    $stmt = $connect->prepare("
        SELECT box_id, box_price
        FROM boxes
        WHERE box_id IN ($in)
    ");

    $stmt->execute($boxIds);

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $b) {
        $boxesMap[$b['box_id']] = $b;
    }
}

try {

    $connect->beginTransaction();

    $stmt = $connect->prepare("
        INSERT INTO orders (user_id, order_price, order_type, note, address)
        VALUES (?, 0, ?, ?, ?)
    ");

    $stmt->execute([$user_id, $order_type, $note, $address]);

    $order_id = $connect->lastInsertId();

    $total_price = 0;

    foreach ($_SESSION['cart'] as $item) {

        $id = $item['id'];
        $qty = max(1, (int)$item['quantity']);
        if ($item['type'] === 'product') {

            $product = $productsMap[$id] ?? null;

            if (!$product) {
                continue; 
            }

            $stmt = $connect->prepare("
    SELECT stock
    FROM products
    WHERE product_id = ?
");

$stmt->execute([$id]);

$currentStock = $stmt->fetchColumn();

if ($currentStock < $qty) {

    $connect->rollBack();

    $_SESSION['error_msg'] = "Some products are out of stock";

    header("Location: cart.php");
    exit;
}

            $connect->prepare("
                UPDATE products
                SET stock = stock - ?
                WHERE product_id = ?
            ")->execute([$qty, $id]);

            $price = $product['product_price'];

            $connect->prepare("
                INSERT INTO order_details (order_id, product_id, box_id, quantity, price)
                VALUES (?, ?, NULL, ?, ?)
            ")->execute([$order_id, $id, $qty, $price]);

        }

else {

    $box = $boxesMap[$id] ?? null;

    if (!$box) {
        continue;
    }
    $stmt = $connect->prepare("
        SELECT product_id, quantity
        FROM box_items
        WHERE box_id = ?
    ");
    $stmt->execute([$id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$items) {
        continue;
    }

    $boxProductIds = [];

foreach ($items as $it) {
    $boxProductIds[] = $it['product_id'];
}

if (empty($boxProductIds)) {
    continue;
}

$in = str_repeat('?,', count($boxProductIds) - 1) . '?';

$stmt = $connect->prepare("
    SELECT product_id, stock
    FROM products
    WHERE product_id IN ($in)
");

$stmt->execute($boxProductIds);

    $productsStock = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $productsStock[$row['product_id']] = $row['stock'];
    }

    $canProceed = true;

    foreach ($items as $it) {

        $pid = $it['product_id'];
        $needed = $it['quantity'] * $qty;

        if (!isset($productsStock[$pid]) || $productsStock[$pid] < $needed) {
            $canProceed = false;
            break;
        }
    }

   if (!$canProceed) {

    $connect->rollBack();

    $_SESSION['error_msg'] = "Some box items are out of stock";

    header("Location: cart.php");
    exit;
}
    foreach ($items as $it) {

        $connect->prepare("
            UPDATE products
            SET stock = stock - ?
            WHERE product_id = ?
        ")->execute([
            $it['quantity'] * $qty,
            $it['product_id']
        ]);
    }
    $price = $box['box_price'];

    $connect->prepare("
        INSERT INTO order_details (order_id, product_id, box_id, quantity, price)
        VALUES (?, NULL, ?, ?, ?)
    ")->execute([$order_id, $id, $qty, $price]);
}
        $total_price += $price * $qty;
    }

    if ($total_price == 0) {
        $connect->rollBack();
        $_SESSION['error_msg'] = "Cart items are invalid or out of stock";
        header("Location: cart.php");
        exit;
    }

    $connect->prepare("
        UPDATE orders
        SET order_price = ?
        WHERE order_id = ?
    ")->execute([$total_price, $order_id]);

    $connect->commit();

    unset($_SESSION['cart']);

   $_SESSION['order_success'] = [
    'order_id' => $order_id,
    'total_price' => $total_price,
    'fname' => $_SESSION['user_fname'],
    'lname' => $_SESSION['user_lname'],
    'order_type' => $order_type
];

    header("Location: index.php");
    exit;

} catch (Exception $e) {

    if ($connect->inTransaction()) {
        $connect->rollBack();
    }

    $_SESSION['error_msg'] = $e->getMessage();
    header("Location: cart.php");
    exit;

} finally  {
    $connect = null;
}
?>