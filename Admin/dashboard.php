
<?php 
require 'includes/db/db.php';
include 'includes/temp/header.php';
include 'includes/temp/navbar.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
     $_SESSION['error_msg'] = "Access Denied! please log in first";
    exit();
}

$stmt = $connect->prepare("SELECT count(user_id) FROM users");
$stmt->execute();
$userCount = $stmt->fetch();
$stmt = null;

$stmt = $connect->prepare("SELECT count(comment_id) FROM user_comment");
$stmt->execute();
$commentCount = $stmt->fetch();
$stmt = null;

$stmt = $connect->prepare("SELECT count(order_id) FROM orders where order_type ='delivery' ");
$stmt->execute();
$deliveryCount = $stmt->fetch();
$stmt = null;

$stmt = $connect->prepare("SELECT count(order_id) FROM orders where order_type ='pickup' ");
$stmt->execute();
$pickupCount = $stmt->fetch();
$stmt = null;
?>

<div class="row mx-3">
        <?php include 'includes/temp/aside.php'; ?>
        
        <main class="col-md-10">

            <div class="row d-flex justify-content-around mx-5 mb-5">

                <div  class= "col-md-4 dashCard">
                    <div class="info">
                        <i class="fa-solid fa-users"></i>
                        <h4>Users</h4>
                        <p><?php echo $userCount[0];?></p>
                    </div>
                </div>

                <div class= "col-md-4 dashCard">
                    <div class="info">
                        <i class="fa-solid fa-comment-dots"></i>
                        <h4>Comments</h4>
                        <p><?php echo $commentCount[0];?></p>
                    </div>
                </div>
                
            </div>

            <div class="row d-flex justify-content-around mx-5">

                <div  class= "col-md-4 dashCard">
                    <div class="info">
                        <i class="fa-solid fa-shop"></i>
                        <h4>Pickup Orders</h4>
                        <p><?php echo $pickupCount[0];?></p>
                    </div>
                </div>

                <div class= "col-md-4 dashCard">
                    <div class="info">
                        <i class="fa-solid fa-truck-fast"></i>
                        <h4>Delivery Orders</h4>
                        <p><?php echo $deliveryCount[0];?></p>
                    </div>
                </div>
                
            </div>

        </main>
</div>

<?php
$connect = null;
include 'includes/temp/footer.php';
?>
