<?php 
  session_start();
  include 'includes/db.php';
  include 'includes/header.php'; 
  include 'includes/navbar.php'; 

  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $fname    = trim($_POST['fname']);
    $lname    = trim($_POST['lname']);
    $email    = trim($_POST['email']);
    $password = $_POST['pass'];
    $phone = trim($_POST['phone']);

    $_SESSION['user_fname'] = $fname ;
    $_SESSION['user_lname'] = $lname ;    
    $_SESSION['user_email'] = $email ; 
    $_SESSION['password'] = $password ; 
    $_SESSION['phone'] = $phone ; 


    if (empty($fname) || empty($lname) || empty($email) || empty($password) || empty($phone)) {
        $connect = null;
        $_SESSION['error_msg'] = "All fields are required!";
        header("Location: register.php");
        exit();
    }
    $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    if(!preg_match($pattern, $email)){
        $connect = null;
        $_SESSION['error_msg'] = "Invalid Email format! Please use something like name@example.com";
        header("Location: register.php");
        exit();
    }
    $checkEmail = $connect->prepare("SELECT email FROM users WHERE email = ?");
        $checkEmail->execute([$email]);
        
        if ($checkEmail->rowCount() > 0) {
            $checkEmail = null;
             $connect = null;
            $_SESSION['error_msg'] = "This email is already registered!";
            header("Location: register.php");
           exit();
        }
        $checkEmail = null;

        $phonePattern = "/^01[0125][0-9]{8}$/";
        if(!preg_match($phonePattern, $phone)){
            $connect = null;
            $_SESSION['error_msg'] = "Invalid Egyptian phone number!";
            header("Location: register.php");
           exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $statement = $connect->prepare("INSERT INTO users (first_name, last_name, email, `password`, phone) VALUES (?, ?, ?, ? ,? )");
        $statement->execute([$fname, $lname, $email, $hashed_password ,$phone]);

        $statement = null; 
        $connect = null;

            unset($_SESSION['user_fname'], $_SESSION['user_lname'], $_SESSION['user_email'], $_SESSION['password'], $_SESSION['phone']);
            

                $_SESSION['success_msg'] = "Registration successful! You can login now.";
                header("Location: login.php");
                exit();
  }
?>


<div class="form-bg">
   <h2 class="text-center mb-4" >Start Your Journey with Sugar BANS</h2>

<div class="order-card pb-3">
    <?php if (isset($_SESSION['error_msg'])){
        echo "<div class='alert alert-danger text-center'>".$_SESSION['error_msg']."</div>";}
        unset($_SESSION['error_msg']);
    ?>
 
    <h3>Create Your Account</h3>
    
    <form id ="register" action="register.php" method="POST">
        
        <div class="form-row">
          
            <div class="col-md-6">
                <div class="form-label-group">
                    <input type="text" id="fname" name="fname" class="form-control" placeholder="First Name" value ="<?php echo isset($_SESSION['user_fname'])?$_SESSION['user_fname']:'';?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-label-group">
                    <input type="text" id="lname" name="lname" class="form-control" placeholder="Last Name" value ="<?php echo isset($_SESSION['user_lname'])?$_SESSION['user_lname']:'';?>"required >
                </div>
            </div>
        </div>

        <div class="form-label-group">
            <input type="email" id="email" name="email" class="form-control" placeholder="Email" value ="<?php echo isset($_SESSION['user_email'])?$_SESSION['user_email']: "";?>" required>
        </div>

        <div class="form-label-group">
            <input type="password" id="pass" name="pass" class="form-control" placeholder="password" value ="<?php echo isset($_SESSION['password'])?$_SESSION['password']: "";?>" required>
        </div>

        <div class="form-label-group">
            <input type="tel" id="phone" name="phone" class="form-control" placeholder="Phone Number" value ="<?php echo isset($_SESSION['phone'])?$_SESSION['phone']: "";?>" required>
        </div>

        <button type="submit" name="submit" class="btn-submit mt-4 shadow-sm">Create Account</button>

        <div class="text-center mt-4 pt-3 border-top">
            <p>Already have an account <a href="login.php">Login Here</a></p>
        </div>
    </form>
</div>
</div>

<?php
 $connect = null;
 include 'includes/footer.php'; 
?>