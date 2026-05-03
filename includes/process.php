
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


