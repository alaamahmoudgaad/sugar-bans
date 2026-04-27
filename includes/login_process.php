<?php
session_start();
include ( 'db.php');

if (isset($_POST['submit'])) {

    $fname    = trim($_POST['fname']);
    $lname    = trim($_POST['lname']);
    $email    = trim($_POST['email']);
    $password = $_POST['pass'];

    if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
        header("Location: ../login.php");      
        exit();
    }

    $sql = "SELECT * FROM users WHERE email = :email AND first_name = :fname AND last_name = :lname";
    $statement = $connect->prepare($sql);
    $statement->execute([
        ':email' => $email,
        ':fname' => $fname,
        ':lname' => $lname
     ]);

     $user = $statement->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fname'];
            $_SESSION['user_lname'] = $user['lname'];    
            $_SESSION['user_email'] = $user['email'];   

            $_SESSION['success_msg'] = "Welcome back, " . $user['fname'] . "! You have successfully logged in.";

            header("Location: ../index.php");
            exit();
        } 
         else {
            $_SESSION['error_msg'] = "The password you entered is incorrect.";
            header("Location: ../login.php");
            exit();
        }
    }
    else {
        $_SESSION['error_msg'] = "No account found with this name and email.";
        header("Location: ../login.php");
        exit();
        }
}
else {
    $_SESSION['error_msg'] = "Access Denied! Please log in first to access this page.";
    header("Location: ../login.php");
    exit();
}
?>
