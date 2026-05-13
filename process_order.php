<?php
session_start();
require_once 'includes/db.php';

if (isset($_POST['cancel_order'])) {
    unset($_SESSION['cart']);
    $connect = null;
    echo "<script>
        alert('Order cancelled successfully');
        window.location.href='index.php';
    </script>";
    exit();
}

if (!isset($_POST['submit_order'])) {
    $connect = null;
    exit();
}

if (!isset($_SESSION['user_id'])) {
    $connect = null;
    echo "<script>
        alert('Login required');
        window.location.href='login.php';
    </script>";
    exit();
}

if (empty($_SESSION['cart'])) {
    $connect = null;
    echo "<script>
        alert('Your cart is empty. Please add items first.');
        window.location.href='menu.php';
    </script>";
    exit();
}

$user_id = $_SESSION['user_id'];

$fname = trim($_POST['fname'] ?? '');
$lname = trim($_POST['lname'] ?? '');

if (
    $fname !== $_SESSION['user_fname'] || $lname !== $_SESSION['user_lname']
) {
    $connect = null;
    echo "<script>
        alert('You cannot change account information');
        window.location.href='cart.php';
    </script>";
    exit();
}

$phone = trim($_POST['phone'] ?? '');
if (!preg_match('/^01[0-9]{9}$/', $phone)) {
    $connect = null;
    echo "<script>
        alert('Invalid phone number');
        window.history.back();
    </script>";
    exit();
}

$order_type = $_POST['order_state'] ?? '';

if (!in_array($order_type, ['pickup', 'delivery'])) {
    $connect = null;
   echo "<script>
            alert('Invalid order type');
            window.history.back();
        </script>";
    exit();
}

$note = htmlspecialchars($_POST['notes'] ?? '');

$address = null;

if ($order_type === 'delivery') {
    $addressInput = trim($_POST['address'] ?? '');
    if ($addressInput === '') {
        $connect = null;
        echo "<script>
            alert('Please enter delivery address');
            window.history.back();
        </script>";
        exit();
    }

    $address = htmlspecialchars($addressInput);
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


$productsData = [];
$boxesData = [];

if (!empty($productIds)) {
    $in = str_repeat('?,', count($productIds) - 1) . '?';

    $stmt = $connect->prepare("
        SELECT product_id, product_price, stock
        FROM products
        WHERE product_id IN ($in)
    ");

    $stmt->execute($productIds);

    $productsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (!empty($boxIds)) {

    $in = str_repeat('?,', count($boxIds) - 1) . '?';

    $stmt = $connect->prepare("
        SELECT box_id, box_price, stock
        FROM boxes
        WHERE box_id IN ($in)
    ");

    $stmt->execute($boxIds);

    $boxesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


$productsMap = [];
$boxesMap = [];

foreach ($productsData as $p) {
    $productsMap[$p['product_id']] = $p;
}

foreach ($boxesData as $b) {
    $boxesMap[$b['box_id']] = $b;
}

$total_price = 0;

try {

    $connect->beginTransaction();

    $stmt = $connect->prepare("
        INSERT INTO orders (user_id, order_price, order_type, note, address)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([$user_id, 0, $order_type, $note, $address]);

    $order_id = $connect->lastInsertId();

    foreach ($_SESSION['cart'] as $item) {

        $id = $item['id'];
        $qty = max(1, (int)$item['quantity']);

        if ($item['type'] === 'product') {

            $product = $productsMap[$id] ?? null;

            if (!$product) continue;

            if ($product['stock'] < $qty) {

                echo "<script>
                    alert('Some products are out of stock or not enough quantity');
                    window.history.back();
                </script>";

                exit();
            }

            $price = $product['product_price'];
        }

        else {

            $box = $boxesMap[$id] ?? null;
            if (!$box) continue;

            if ($box['stock'] < $qty) {
                echo "<script>
                    alert('Some boxes are out of stock or not enough quantity');
                    window.history.back();
                </script>";
                exit();
            }
            $price = $box['box_price'];
        }

        $subtotal = $price * $qty;
        $total_price += $subtotal;

        if ($item['type'] === 'product') {
            $stmtDet = $connect->prepare("
                INSERT INTO order_details (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ");

            $stmtStock = $connect->prepare("
                UPDATE products
                SET stock = stock - ?
                WHERE product_id = ?
            ");

            $stmtDet->execute([$order_id, $id, $qty, $price]);
            $stmtStock->execute([$qty, $id]);

        } else {
            $stmtDet = $connect->prepare("
                INSERT INTO order_boxes (order_id, box_id, quantity, box_price)
                VALUES (?, ?, ?, ?)
            ");

            $stmtStock = $connect->prepare("
                UPDATE boxes
                SET stock = stock - ?
                WHERE box_id = ?
            ");
            $stmtDet->execute([$order_id, $id, $qty, $price]);
            $stmtStock->execute([$qty, $id]);
        }
    }
    $update = $connect->prepare("
        UPDATE orders
        SET order_price = ?
        WHERE order_id = ?
    ");
    $update->execute([$total_price, $order_id]);

    $connect->commit();
    unset($_SESSION['cart']);
   echo "<script>
        alert(`Order Created Successfully

        Order ID: #$order_id
        Customer: $fname $lname
        Order Type: $order_type
        Total Price: $total_price EGP
        Delivery Fee: " . ($order_type === 'delivery' ? 50 : 0) . " EGP`);

window.location.href = 'index.php';
</script>";

} catch (Exception $e) {

    if ($connect->inTransaction()) {
        $connect->rollBack();
    }

    header("Location: 404.php");
    exit();

} finally {
    $connect = null;
}
?>