<?php 
    session_start();
  include 'includes/db.php';
  include 'includes/header.php'; 
  include 'includes/navbar.php'; 
?>
<div class="form-bg">
<div class="container my-5 ">
    <div class="row  shadow" style="background-color: #fdfdfd;">
        
        <div class="col-md-6 p-5">
            <h2 class=" mb-2">Contact Us</h2>
            <p class="mb-4">We'd love to hear from you. Please fill out the form below.</p>
            <?php 
                if (isset($_SESSION['user_id'])){} 
            ?>
            
            <form id="contact" action="save_comment.php" method="POST">

                <div class="form-row">
                    <div class="col-md-6">
                          <div class="form-label-group">
                          <input type="text" id="fname" name="fname" class="form-control" value="<?php echo $_SESSION['user_fname'] ?? "";?>" placeholder="First Name">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-label-group">
                          <input type="text" id="lname" name="lname" class="form-control" value="<?php echo $_SESSION['user_lname'] ?? "";?>" placeholder="Last Name">
                    </div>
                </div>
              </div>

               <div class="mb-3">
                   <div class="form-label-group">
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo $_SESSION['user_email'] ?? ""; ?>"  placeholder="Email">
                    </div>
               </div>

                <div class="mb-3">
                    <label for="Subject">Subject</label>
                   <div class="form-label-group mb-3">
                        <select class="form-control py-2" name="subject" id="Subject" required>
                            <option hidden value="disabled selected">What is this about?</option>
                            <option value="problem"<?php echo (isset($_SESSION['contact_message']) && $_SESSION['contact_message'] == 'problem') ? 'selected' : ''; ?>>Problem</option>
                            <option value="review"<?php echo (isset($_SESSION['contact_message']) && $_SESSION['contact_message'] == 'review') ? 'selected' : ''; ?>>Review</option>
                            <option value="complaint"<?php echo (isset($_SESSION['contact_message']) && $_SESSION['contact_message'] == 'complaint') ? 'selected' : ''; ?>>Complaint</option>
                            <option value="question"<?php echo (isset($_SESSION['contact_message']) && $_SESSION['contact_message'] == 'question') ? 'selected' : ''; ?>>Question</option>
                        </select>
                </div>
                </div>

                <div class="mb-3">
                    <label for="message">Message</label>
                    <textarea class="form-control py-2" name="user_message" rows="4" id= "message" placeholder="Write your message here..." required>
                        <?php echo $_SESSION['contact_message'] ?? ''; ?></textarea>
                </div>

                <?php if (isset($_SESSION['user_id'])){} ?>

                <button type="submit" class="btn-submit w-100  py-2 mt-4 ">
                    Send Message</button>
            </form>
        </div>

        <div class="col-md-6">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3456.6385742321054!2d31.245564896789563!3d29.96107300000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x145847063d781f65%3A0x37278acb7a61a7f1!2zU2Fsw6kgU3VjcsOpIFDDonRpc3Nlcmll!5e0!3m2!1sen!2seg!4v1776459496507!5m2!1sen!2seg" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
     </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>