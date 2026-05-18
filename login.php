<?php 
  session_start();
  include 'includes/db.php';
  include 'includes/header.php'; 
  include 'includes/navbar.php'; 


if($_SERVER['REQUEST_METHOD' ] == "POST"){

    $fname    = trim($_POST['fname']);
    $lname    = trim($_POST['lname']);
    $email    = trim($_POST['email']);
    $password = $_POST['pass'];

    if (empty($fname) || empty ($lname) || empty($email) || empty($password)) {
         $connect = null;
        header("Location: login.php");      
        exit();
    }
    $statement = $connect->prepare("SELECT * FROM users WHERE email = ? AND first_name = ? AND last_name = ?");
    $statement->execute(array( $email , $fname , $lname));
    $userCount = $statement->rowCount();

    if($userCount>0){ 
        $result = $statement->fetch();
        if (password_verify($password, $result['password'])) {

            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['user_fname'] = $result['first_name'];
            $_SESSION['user_lname'] = $result['last_name'];    
            $_SESSION['user_email'] = $result['email']; 
            $_SESSION['phone'] = $result['phone'] ;
            $_SESSION['role'] = $result['role']; 

            $statement = null;
            $connect = null;

            if ($_SESSION['role'] === 'admin'){
                header("Location: Admin/dashboard.php");
                exit();
            }
            else{
                header("Location: index.php");
                exit();
             }
                        
        } 
         else {
            $statement = null;
            $connect = null;
            $_SESSION['error_msg'] = "The password you entered is incorrect.";
            header("Location: login.php");
            exit();
        }
    }
    else {
        $statement = null;
        $connect = null;
        $_SESSION['error_msg'] = "No account found with this name and email , Register first";
        header("Location: register.php");
        exit();
        }
}
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
    <?php  
    if (isset($_SESSION['success_msg'])){
    echo "<div class='alert alert-success text-center'>".$_SESSION['success_msg']."</div>";}
    unset($_SESSION['success_msg']);

    if (isset($_SESSION['error_msg'])){
    echo "<div class='alert alert-danger text-center'>".$_SESSION['error_msg']."</div>";}
    unset($_SESSION['error_msg']);
    
    ?>


    <h3>Log IN</h3>
    <?php if (isset($_SESSION['login_msg'])): ?>
    <div class="alert alert-warning text-center mb-3">
        <?= $_SESSION['login_msg']; ?>
    </div>
    <?php unset($_SESSION['login_msg']); ?>
<?php endif; ?>
    
    <form id="loginForm" action="login.php" method="POST">
        
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


<?php 
 $connect = null;
include 'includes/footer.php'; ?>