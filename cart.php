<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

if (!isset($_SESSION['user_id'])) {
    $connect = null;
    echo "<script>
    alert('Please login first');
    window.location.href='login.php';
    </script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id   = $_POST['id'] ?? null;
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
    $type = $_POST['type'] ?? 'product';

    if (!$id || !is_numeric($id) || (int)$id <= 0) {
        $connect = null;
        echo "<script>
        alert('Invalid item');
        window.history.back();
        </script>";
        exit;
    }

    $id = (int)$id;

    if (!in_array($type, ['product', 'box'])) {
        $connect = null;
        echo "<script>
        alert('Invalid type');
        window.history.back();
        </script>";
        exit;
    }

    $qty = max(1, $qty);

    if ($type === 'product') {
    $stmt = $connect->prepare("
        SELECT stock
        FROM products
        WHERE product_id = ?
    ");
} else {
    $stmt = $connect->prepare("
        SELECT stock
        FROM boxes
        WHERE box_id = ?
    ");
}

$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = null;

$stock = $product['stock'] ?? 0;

if ($stock <= 0) {
    $connect = null;
    echo "<script>
    alert('Out of stock');
    window.history.back();
    </script>";
    exit;
}

if ($qty > $stock) {
    $connect = null;
    echo "<script>
    alert('Only $stock items available in stock');
    window.history.back();
    </script>";
    exit;
}

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id && $item['type'] === $type) {
            $item['quantity'] += $qty;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'id'       => $id,
            'quantity' => $qty,
            'type'     => $type
        ];
    }

    header("Location: menu.php");
    $connect = null;
    exit;
}

$totalPrice = 0;
$totalItems = 0;
?>

<div class="form-bg">
    <div class="order-summary-box mb-2">
        <h5 class="border-bottom pb-2">Order Summary</h5>

        <?php if (!empty($_SESSION['cart'])): ?>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <?php

                if ($item['type'] === 'product') {
                    $stmt = $connect->prepare("
                        SELECT product_name, product_price
                        FROM products
                        WHERE product_id = ?
                    ");
                } else {
                    $stmt = $connect->prepare("
                        SELECT box_name AS product_name, box_price AS product_price
                        FROM boxes
                        WHERE box_id = ?
                    ");
                }

                $stmt->execute([$item['id']]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt = null;

                if ($product):
                    $subtotal = $product['product_price'] * $item['quantity'];
                    $totalPrice += $subtotal;
                    $totalItems += $item['quantity'];
                ?>

                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <h6><?= htmlspecialchars($product['product_name']) ?></h6>
                            <small> Quantity: <?= (int)$item['quantity'] ?> </small>
                        </div>

                        <span><?= $subtotal ?> EGP</span>
                    </div>

                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Your cart is empty!</p>
        <?php endif; ?>

        <div class="d-flex justify-content-between border-top mt-3">
            <span>Total Items:</span>
            <strong><?= (int)$totalItems ?></strong>
        </div>

        <div class="d-flex justify-content-between">
            <span>Total Price:</span>
            <strong><?= $totalPrice ?> EGP</strong>
        </div>
    </div>

    <div class="order-card">
        <h2>Complete Order</h2>

        <?php
        if (isset($_SESSION['error_msg'])) {
            echo "<div class='alert alert-danger text-center'>"
                . $_SESSION['error_msg'] .
            "</div>";
            unset($_SESSION['error_msg']);
        }

        if (isset($_SESSION['success_msg'])) {
            echo "<div class='alert alert-success text-center'>"
                . $_SESSION['success_msg'] .
            "</div>";
            unset($_SESSION['success_msg']);
        }
        ?>

        <form action="process_order.php" method="POST" id="orderForm">

            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="fname" class="form-control" placeholder="First Name"
                        value="<?= htmlspecialchars($_SESSION['user_fname'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <input type="text" name="lname" class="form-control" placeholder="Last Name"
                        value="<?= htmlspecialchars($_SESSION['user_lname'] ?? '') ?>" required>
                </div>
            </div>

            <input type="tel" name="phone" class="form-control mt-2" placeholder="Phone Number" required>

            <div class="mt-3">
                <label class="form-label d-block"> How do you want to receive your order? </label>

                <div class="form-check-inline">
                    <input class="form-check-input" type="radio" name="order_state" id="pickup"
                        value="pickup" checked onclick="AddressShow(false)">
                    <label class="form-check-label" for="pickup"> Pickup from Shop </label>
                </div>

                <div class="form-check-inline">
                    <input class="form-check-input" type="radio" name="order_state" id="delivery"
                        value="delivery" onclick="AddressShow(true)">
                    <label class="form-check-label" for="delivery"> Delivery </label>
                </div>

                <div id="address-div" style="display:none;">
                    <input type="text" name="address" class="form-control mt-2" placeholder="Full Address">
                </div>

                <textarea name="notes" class="form-control mt-2" placeholder="Notes (Optional)"></textarea>

                <input type="hidden" name="total_price_hidden" value="<?= (float)$totalPrice ?>">

                <div class="d-flex justify-content-center mt-5 mb-3">
                    <button type="submit" name="submit_order" class="btn-submit w-48">Confirm Order</button>

                    <button type="submit" name="cancel_order" class="btn-submit cancel-btn w-48"
                        style="margin-left:15px;">Cancel Order</button>
                </div>
            </div>

        </form>

    </div>
</div>

<?php
include 'includes/footer.php';
$connect = null;
?>