<?php 
  session_start();
  include 'includes/db.php';
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
    <?php if (isset($_SESSION['error_msg'])){
        echo "<div class='alert alert-danger text-center'>".$_SESSION['error_msg']."</div>";}
        unset($_SESSION['error_msg']);?>
 
    <h3>Log IN</h3>
    
    <form id="loginForm" action="includes/process.php" method="POST">
        
        <div class="form-row">
          
            <div class="col-md-6">
                <p class="form-label-group">
                    <input type="text" id="fname" name="fname" class="form-control" placeholder="First Name" required>
                </p>
            </div>

            <div class="col-md-6">
                <p class="form-label-group">
                    <input type="text" id="lname" name="lname" class="form-control" placeholder="Last Name" required>
                </p>
            </div>
        </div>

        <p class="form-label-group">
            <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
        </p>

        <p class="form-label-group">
            <input type="password" id="pass" name="pass" class="form-control" placeholder="password" required>
        </p>

        <button type="submit" name="login" class="btn-submit shadow-sm">LogIn</button>

        <div class="text-center mt-4 pt-3 border-top">
            <p>Don't have an account? <a href="register.php">Register Here</a></p>
        </div>
    </form>
</div>
</div>


<?php include 'includes/footer.php'; ?>