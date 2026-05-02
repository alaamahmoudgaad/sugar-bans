<?php 
session_start();
include 'includes/db/db.php';

if (isset($_POST['save_record'])) {
    $tableName = $_POST['table_name'];
    unset($_POST['table_name']); 
    unset($_POST['save_record']);

    $columns = array_keys($_POST); 
    $values  = array_values($_POST);

    if (in_array("", $values)) {
        $_SESSION['validation_msg'] = "Please fill in all fields!";
        header("Location: action.php?action=add&table=$tableName");
        exit();
    }
    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        // Perl Regular Expressions
        if (!preg_match($pattern, $email)) {
            $_SESSION['validation_msg'] = "Invalid Email format! Please use something like name@example.com";
            header("Location: action.php?action=add&table=$tableName");
            exit();
       }

        $checkEmail = $connect->prepare("SELECT email FROM $tableName WHERE email = ?");
        $checkEmail->execute([$email]);
        
        if ($checkEmail->rowCount() > 0) {
            $_SESSION['validation_msg'] = "This Email is already registered!";
            header("Location: action.php?action=add&table=$tableName");
            exit();
        }
    }

    if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];

    $phonePattern = "/^01[0125][0-9]{8}$/";

    if (!preg_match($phonePattern, $phone)) {
        $_SESSION['validation_msg'] = "Invalid Egyptian phone number!";
        header("Location: action.php?action=add&table=$tableName");
        exit();
    }
}
    
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));

        $sql = "INSERT INTO $tableName (" . implode(', ', $columns) . ") VALUES ($placeholders)";
        $stmt = $connect->prepare($sql);
        $stmt->execute($values);  
    
        $_SESSION['msg'] = "Record Added Successfully!";
        header("Location: view_table.php?table=$tableName");
        exit();
    
}
else{
    $_SESSION['error_msg'] = "Access Denied!";
    header("Location: ../login.php");
    exit();
}


?>

