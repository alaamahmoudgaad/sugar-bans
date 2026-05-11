<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id  = $_POST['id'] ?? null;
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
    $type = $_POST['type'] ?? 'product';

    if ($id) {

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
                'id' => $id,
                'quantity' => $qty,
                'type' => $type
            ];
        }
    }

    header("Location: menu.php");
    exit;
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="form-bg">

    <div class="order-summary-box">

        <h5 class="border-bottom pb-2">Order Summary</h5>

        <?php 
        $totalPrice = 0;
        $totalItems = 0;

        if (!empty($_SESSION['cart'])): 

            foreach ($_SESSION['cart'] as $item):

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

                if ($product):

                    $subtotal = $product['product_price'] * $item['quantity'];
                    $totalPrice += $subtotal;
                    $totalItems += $item['quantity'];
        ?>

            <div class="d-flex justify-content-between mb-2">
                <div>
                    <h6><?= $product['product_name']; ?></h6>
                    <small>Quantity: <?= $item['quantity']; ?></small>
                </div>
                <span><?= $subtotal; ?> EGP</span>
            </div>

        <?php 
                endif;

            endforeach;

        else: 
        ?>

            <p class="text-center">Your cart is empty!</p>

        <?php endif; ?>

        <div class="d-flex justify-content-between border-top mt-3">
            <span>Total Items:</span>
            <strong><?= $totalItems; ?></strong>
        </div>

        <div class="d-flex justify-content-between">
            <span>Total Price:</span>
            <strong><?= $totalPrice; ?> EGP</strong>
        </div>

    </div>


    <div class="order-card">

        <h2>Complete Order</h2>

        <form action="process_order.php" method="POST" id="orderForm">

            <div class="row">

                <div class="col-md-6">
                    <input type="text" name="fname" class="form-control" placeholder="First Name"
                           value="<?= $_SESSION['user_fname'] ?? ''; ?>" required>
                </div>

                <div class="col-md-6">
                    <input type="text" name="lname" class="form-control" placeholder="Last Name"
                           value="<?= $_SESSION['user_lname'] ?? ''; ?>" required>
                </div>

            </div>

            <input type="tel" name="phone" class="form-control mt-2" placeholder="Phone Number" required>

            <div class="mt-3">

                <div class="mb-4">

    <label class="form-label d-block">
        How do you want to receive your order?
    </label>

    <div class="form-check-inline">

        <input class="form-check-input"
               type="radio"
               name="order_state"
               id="pickup"
               value="pickup"
               checked
               onclick="AddressShow(false)">

        <label class="form-check-label" for="pickup">
            <i class="fas fa-store me-1"></i>
            Pickup from Shop
        </label>

    </div>

    <div class="form-check-inline">

        <input class="form-check-input"
               type="radio"
               name="order_state"
               id="delivery"
               value="delivery"
               onclick="AddressShow(true)">

        <label class="form-check-label" for="delivery">
            <i class="fas fa-truck me-1"></i>
            Delivery
        </label>

    </div>

</div>

            <div id="address-div" style="display:none;">
                <input type="text" name="address" class="form-control mt-2" placeholder="Full Address">
            </div>

            <textarea name="notes" class="form-control mt-2" placeholder="Notes (Optional)"></textarea>

            <input type="hidden" name="total_price_hidden" value="<?= $totalPrice; ?>">

            <button type="submit" name="submit_order" class="btn-submit mt-3">
                Confirm Order
            </button>

        </form>

    </div>

</div>

<?php include 'includes/footer.php'; ?>