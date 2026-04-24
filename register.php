<?php 
  include 'includes/db.php';
  include 'includes/header.php'; 
  include 'includes/navbar.php'; 
?>


<div class="form-bg">
   <h2 class="text-center fw-bold mb-5" style="letter-spacing: 1px;">Start Your Journey with Sugar Bans</h2>

<div class="order-card pb-5">
    <h3>Create Your Account</h3>
    
    <form action="#" method="POST">
        
        <div class="form-row">
          
            <div class="col-md-6">
                <div class="form-label-group">
                    <input type="text" id="fname" name="fname" class="form-control" placeholder="First Name" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-label-group">
                    <input type="text" id="lname" name="lname" class="form-control" placeholder="Last Name" required>
                </div>
            </div>
        </div>

        <div class="form-label-group">
            <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
        </div>

        <div class="form-label-group">
            <input type="password" id="pass" name="pass" class="form-control" placeholder="password" required>
        </div>

        <div class="form-label-group">
            <input type="tel" id="phone" name="phone" class="form-control" placeholder="Phone Number" required>
        </div>

        <div class="form-label-group">
            <input type="text" id="address" name="address" class="form-control" placeholder="Full Address" required>
        </div>

        <button type="submit" name="submit" class="btn-submit mt-4 shadow-sm">Create Account</button>

        <div class="text-center mt-4 pt-3 border-top">
            <p>Already have an account <a href="login.php">Login Here</a></p>
        </div>
    </form>
</div>
</div>




<?php include 'includes/footer.php'; ?>