
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php 
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
     $_SESSION['error_msg'] = "Access Denied! please log in first";
    exit();
}
include 'includes/db/db.php';
include 'includes/temp/header.php';
include 'includes/temp/navbar.php';
include 'includes/temp/aside.php';
?>

<h2>Hello From Dashboard</h2>

<?php
include 'includes/temp/footer.php';
?>
