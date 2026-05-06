<?php 
    session_start();
  include 'includes/db.php';
  include 'includes/header.php'; 
  include 'includes/navbar.php'; 

  if (isset($_POST['contact'])) {
    $fname    = trim($_POST['fname']);
    $lname    = trim($_POST['lname']);
    $email    = trim($_POST['email']);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));


    if (empty($fname) || empty($lname) || empty($email) || empty($subject) || empty($message)){
        $connect = null;
        $_SESSION['error_msg'] = "All fields are required!";
        header("Location: contact.php");
        exit();
    }

    $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    if(!preg_match($pattern, $email)){
        $connect = null;
        $_SESSION['error_msg'] = "Invalid Email format! Please use something like name@example.com";
        header("Location: contact.php");
        exit();
    }

    $strEmail = "alaa.mahmoud.gaad@gmail.com";
    mail($strEmail, $subject, $message);
    
    if (isset($_SESSION['user_id'])){
        $stmt = $connect->prepare( "INSERT INTO user_comment (user_id, subject, comment) VALUES (?, ?, ? )");
        $stmt->execute([$_SESSION['user_id'], $subject, $message]);

        $stmt = null; 
        $connect = null;

            $_SESSION['success_msg'] = "Thank you! Your message has been sent.";
            header("Location: contact.php");
            exit();  
    }
    else{
        $user_id = 6; 
        $full_name = $fname . " " . $lname;
        $stmt = $connect->prepare( "INSERT INTO user_comment (user_id, subject, comment , guest_name, guest_email) VALUES (?, ?, ? ,? ,?)");
        $stmt->execute([$user_id, $subject, $message, $full_name , $email]);

        $stmt = null; 
        $connect = null;

        $_SESSION['success_msg'] = "Thank you! Your message has been sent.";
            header("Location: contact.php");
            exit(); 
            
    }
}

?>
<div class="form-bg">
<div class="container my-5 ">
    <div class="row  shadow" style="background-color: #fdfdfd;">
        
        <div class="col-md-6 p-5">
            <h2 class=" mb-2">Contact Us</h2>
            <p class="mb-4">We'd love to hear from you. Please fill out the form below.</p>
            <?php if (isset($_SESSION['error_msg'])){
                echo "<h5 class='alert alert-danger text-center'>".$_SESSION['error_msg']."</h5>";}
                unset($_SESSION['error_msg']);?>

                <?php if (isset($_SESSION['success_msg'])){
                echo "<h5 class='alert alert-success text-center'>".$_SESSION['success_msg']."</h5>";}
                unset($_SESSION['success_msg']);?>
            
            <form id="contact" action="contact.php" method="POST">

                <div class="form-row">
                    <div class="col-md-6">
                          <div class="form-label-group">
                          <input type="text" id="fname" name="fname" class="form-control" value="<?php echo $_SESSION['user_fname'] ?? "";?>" placeholder="First Name" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-label-group">
                          <input type="text" id="lname" name="lname" class="form-control" value="<?php echo $_SESSION['user_lname'] ?? "";?>" placeholder="Last Name" required>
                    </div>
                </div>
              </div>

               <div class="mb-3">
                   <div class="form-label-group">
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo $_SESSION['user_email'] ?? ""; ?>"  placeholder="Email" required>
                    </div>
               </div>

                <div class="mb-3">
                    <label for="Subject">Subject</label>
                   <div class="form-label-group mb-3">
                        <select class="form-control py-2" name="subject" id="Subject" required>
                            <option hidden value="disabled selected">What is this about?</option>
                            <option>Problem</option>
                            <option>Review</option>
                            <option>Complaint</option>
                            <option>Question</option>
                        </select>
                </div>
                </div>

                <div class="mb-3">
                    <label for="message">Message</label>
                    <textarea class="form-control py-2" name="message" rows="4" id= "message" placeholder="Write your message here..." required></textarea>
                </div>

                

                <button type="submit" name="contact" class="btn-submit w-100  py-2 mt-4 ">
                    Send Message</button>
            </form>
        </div>

        <div class="col-md-6">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3456.6385742321054!2d31.245564896789563!3d29.96107300000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x145847063d781f65%3A0x37278acb7a61a7f1!2zU2Fsw6kgU3VjcsOpIFDDonRpc3Nlcmll!5e0!3m2!1sen!2seg!4v1776459496507!5m2!1sen!2seg" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
     </div>
  </div>
</div>

<?php 
$connect = null;
include 'includes/footer.php'; ?>