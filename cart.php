<?php 
  session_start(); 
  require_once 'includes/db.php'; 
  include 'includes/header.php'; 
  include 'includes/navbar.php'; 
?>

<div class="form-bg">
    <div class="order-summary-box">
        <h5 class="border-bottom pb-2">Order Summary</h5>
        
        <?php 
        $totalPrice = 0;
        $totalItems = 0;
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): 
            foreach ($_SESSION['cart'] as $item):
                if ($item['type'] === 'product') {
                    $stmt = $connect->prepare("SELECT product_name, product_price FROM products WHERE product_id = ?");
                } else {
                    $stmt = $connect->prepare("SELECT box_name as product_name, box_price as product_price FROM boxes WHERE box_id = ?");
                }
                $stmt->execute([$item['id']]);
                $product = $stmt->fetch();

                if ($product):
                    $subtotal = $product['product_price'] * $item['quantity'];
                    $totalPrice += $subtotal;
                    $totalItems += $item['quantity'];
        ?>
            <div class="d-flex justify-content-between mb-2">
                <div>
                    <h6><?php echo $product['product_name']; ?></h6>
                    <small>Quantity: <?php echo $item['quantity']; ?></small>
                </div>
                <span><?php echo $subtotal; ?> EGP</span>
            </div>
        <?php endif; endforeach; ?>
        <?php else: ?>
            <p class="text-center">Your cart is empty!</p>
        <?php endif; ?>
        
        <div class="d-flex justify-content-between border-top mt-3">
            <span>Total Items:</span>
            <strong><?php echo $totalItems; ?></strong>
        </div>

        <div class="d-flex justify-content-between">
            <span>Total Price:</span>
            <strong><?php echo $totalPrice; ?> EGP</strong>
        </div>
    </div>

    <div class="order-card">
        <h2>Complete Order</h2>
        
        <form action="process_order.php" method="POST" id="orderForm">
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-label-group">
                        <input type="tegitxt" id="fname" name="fname" class="form-control" placeholder="First Name" 
                               value="<?php echo $_SESSION['user_fname'] ?? ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-label-group">
                        <input type="text" id="lname" name="lname" class="form-control" placeholder="Last Name" 
                               value="<?php echo $_SESSION['user_lname'] ?? ''; ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-label-group">
                <input type="tel" id="phone" name="phone" class="form-control" placeholder="Phone Number" required>
            </div>

            <div class="mb-4">
                <label class="form-label d-block">How do you want to receive your order?</label>
                <div class="form-check-inline">
                    <input class="form-check-input" type="radio" name="order_state" id="pickup" value="pickup" checked onclick="AddressShow(false)">
                    <label class="form-check-label" for="pickup"><i class="fas fa-store me-1"></i> Pickup from Shop</label>
                </div>
                <div class="form-check-inline">
                    <input class="form-check-input" type="radio" name="order_state" id="delivery" value="delivery" onclick="AddressShow(true)">
                    <label class="form-check-label" for="delivery"><i class="fas fa-truck me-1"></i> Delivery</label>
                </div>
            </div>

            <div id="address-div" class="form-label-group" style="display: none;">
                <input type="text" id="address" name="address" class="form-control" placeholder="Full Address">
            </div>

            <div class="form-label-group">
                <textarea id="notes" name="notes" class="form-control" placeholder="Notes (Optional)" rows="3"></textarea>
            </div>

            <input type="hidden" name="total_price_hidden" value="<?php echo $totalPrice; ?>">

            <button type="submit" name="submit_order" class="btn-submit shadow-sm mt-4">Confirm Order</button>
        </form>
    </div>
</div>

<script>
function AddressShow(show) {
    const div = document.getElementById('address-div');
    const input = document.getElementById('address');
    if (show) {
        div.style.display = 'block';
        input.setAttribute('required', 'required');
    } else {
        div.style.display = 'none';
        input.removeAttribute('required');
    }
}
</script>

<?php include 'includes/footer.php'; ?>