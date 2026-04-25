<?php 
  include 'includes/header.php'; 
  include 'includes/navbar.php'; 
?>

<div class="video-background">
        <video autoplay muted loop id="bgVideo">
            <source src="assets/From KlickPin CF Pin on Postres fáciles y rápidos sin horno.mp4" type="video/mp4">
        </video>
        <div class="overlay"></div>
    </div>

<div class="log-card">
   <h2 class="text-center fw-bold mb-3" style="letter-spacing: 1px;">Welcome Back </h2>

<div class="order-card">
    <h3>Log IN</h3>
    
    <form id="loginForm" action="#" method="POST">
        
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

        <button type="submit" name="submit" class="btn-submit shadow-sm">LogIn</button>

        <div class="text-center mt-4 pt-3 border-top">
            <p>Don't have an account? <a href="register.php">Register Here</a></p>
        </div>
    </form>
</div>
</div>


<?php include 'includes/footer.php'; ?>