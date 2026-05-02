<?php
session_start();
include ( 'db.php');


//   ___________________________form login______________________________
if (isset($_POST['login'])) {

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
    $statement->execute([':email' => $email,':fname' => $fname,':lname' => $lname]);

     $user = $statement->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_fname'] = $user['first_name'];
            $_SESSION['user_lname'] = $user['last_name'];    
            $_SESSION['user_email'] = $user['email']; 
            $_SESSION['role'] = $user['role']; 

            if (isset($_SESSION['contact_message'])) {
                header("Location: ../contact.php");
                exit();
            } 
            else {
                if ($_SESSION['role'] === 'admin'){
                    header("Location: ../Admin/dashboard.php");
                    exit();
                }
                else{
                    header("Location: ../index.php");
                    exit();
                }
            }
            
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

// _____________________________form contact us____________________________________

if (isset($_POST['contact'])) {

    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    $errors = [];

    $allowed_subjects = ['problem', 'review', 'complaint', 'question'];

    if (empty($subject) || !in_array($subject, $allowed_subjects)) {
        $errors[] = "Please select a valid subject.";
    }

    if (empty($message)) {
        $errors[] = "Message field cannot be empty.";
    }
        
    if (!empty($errors)) {
        $_SESSION['error_validation'] = implode("<br>", $errors); 
        header("Location: ../contact.php");
        exit();
    }
    if (isset($_SESSION['user_id'])) {
        $sql = "INSERT INTO user_comment (user_id, subject, comment) 
                    VALUES (:u_id, :sub, :msg )";

            $stmt = $connect->prepare($sql);

            $stmt->execute([
                ':u_id' => $_SESSION['user_id'],
                ':sub'  => $subject,
                ':msg'  => $message
            ]);

            unset($_SESSION['contact_subject']);
            unset($_SESSION['contact_message']);
+
            $_SESSION['success_msg'] = "Thank you! Your message has been sent.";
            header("Location: ../contact.php");
            exit();
             
    }
    else {
        
        $_SESSION['contact_subject'] = $subject;
        $_SESSION['contact_message'] = $message;
        $_SESSION['error_msg'] = "Please login first to send your message. We saved your text!";
        
        header("Location: ../login.php");
        exit();
    }


    
}





// if (!isset($_POST['login']) && !isset($_POST['contact'])) {
//     $_SESSION['error_msg'] = "Access Denied!";
//     header("Location: ../login.php");
//     exit();
// }
?>


