<?php 
  include 'includes/header.php'; 
  include 'includes/navbar.php'; 
?>

<div class="form-bg">

    <div class="order-summary-box">

        <h5 class="border-bottom pb-2">Order Summary</h5>

        <div class="d-flex justify-content-between mb-2">
                <div>
                    <h6 >Strawberry Tart</h6>
                    <small>Quantity: 2</small>
                </div>
                <span>300 EGP</span>
            </div>
        
        <div class="d-flex justify-content-between border-top">
            <span>Total Items:</span>
            <strong>3</strong>
        </div>

        <div class="d-flex justify-content-between">
            <span>Total Price:</span>
            <strong>450 EGP</strong>
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

            <div class="col-md-6">
                <div class="form-label-group">
                    <input type="text" id="lname" name="lname" class="form-control" placeholder="Last Name" required>
                </div>
            </div>
        </div>

        <div class="form-label-group">
            <input type="tel" id="phone" name="phone" class="form-control" placeholder="Phone Number" required>
        </div>

        <div class="mb-4">
            <label class="form-label d-block ">How do you want to receive your order?</label>

            <div class="form-check-inline ">
                <input class="form-check-input" type="radio" name="order_state" id="pickup" value="pickup" checked onclick="AddressShow(false)">
                <label class="form-check-label" for="pickup"><i class="fas fa-store me-1"></i> Pickup from Shop</label>
            </div>

            <div class="form-check-inline custom-radio">
                <input class="form-check-input" type="radio" name="order_state" id="delivery" value="delivery" onclick="AddressShow(true)">
                <label class="form-check-label" for="delivery"><i class="fas fa-truck me-1"></i> Delivery</label>
            </div>

        </div>

        <div id="address-div" class="form-label-group">
            <input type="tel" id="address" name="address" class="form-control" placeholder="Full Address" required>
        </div>

        <div class="form-label-group">
            <textarea id="notes" name="notes" class="form-control" placeholder="Notes" rows="3"></textarea>
        </div>

        <button type="submit" name="submit_order" class="btn-submit shadow-sm mt-4">Confirm Order</button>
    </form>
</div>
</div>

<?php include 'includes/footer.php'; ?>