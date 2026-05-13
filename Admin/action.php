<?php 
require 'includes/db/db.php';
include 'includes/temp/header.php';
include 'includes/temp/navbar.php';

if ($_SESSION['role'] !== 'admin') {
    $_SESSION['error_msg'] = "Access Denied! please log in first";
    header("Location: ../login.php");
    exit();
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/temp/aside.php'; ?>
        
        <main class="col-md-10">
            <?php 
                
                $action = isset($_GET['action']) ? $_GET['action'] : '';
                $table = isset($_GET['table'])  ? $_GET['table']  : '';
                $id = isset($_GET['id'])     ? $_GET['id']     : 0;

                if (!empty($table)) {
                    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
                        $stmtCol = $connect->prepare("SHOW COLUMNS FROM $table");
                        $stmtCol->execute();
                        $primaryKey = $stmtCol->fetchColumn(); 
                        $stmtCol = null;
                }

            switch($action) {

                case 'delete':
                    $stmt = $connect->prepare("DELETE FROM $table WHERE $primaryKey = ?");
                    $stmt->execute([$id]) ;
                    $stmt = null; 
                    $connect = null;
                    $_SESSION["message"] ="Deleted successfully";
                    header("Location: view_table.php?table=$table");
                    exit();
                    break;
            
                case 'show':
                    $stmt = $connect->prepare("SELECT * FROM $table WHERE $primaryKey = ?");
                    $stmt->execute([$id]);
                    $item = $stmt->fetch(PDO::FETCH_ASSOC);
                    $stmt = null;
                    ?>
                  
                            <div class="card shadow-sm mt-4 mx-5">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4>Details: <?php echo $id; ?></h4>
                                    <a href="view_table.php?table=<?php echo $table; ?>" class="btn btn-secondary">Back</a>
                                </div>

                                <table class="table mb-0">
                                     <?php foreach ($item as $key => $value){ 
                                     ?>
                                    <tr>
                                        <th><?php echo ucwords(str_replace('_', ' ', $key)); ?></th>
                                        <td><?php echo  $value?></td>
                                    </tr>
                                    <?php } ?>
                                </table>

                                <div class="card-footer d-flex align-items-center py-3 m">
                                        <a href="action.php?action=edit&table=<?php echo $table; ?>&id=<?php echo $id; ?>" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="action.php?action=delete&table=<?php echo $table; ?>&id=<?php echo $id; ?>" class="btn btn-danger btn-sm px-3 mr-5" onclick= "return confirm('Are you sure you want to delete this?')"><i class="fa-solid fa-trash"></i></a>
                                </div>
                            </div>
                    

                <?php      
                    break;

                case 'edit':
                    $stmt = $connect->prepare("SELECT * FROM $table WHERE $primaryKey = ?");
                    $stmt->execute([$id]);
                    $item = $stmt->fetch(PDO::FETCH_ASSOC);
                    $stmt = null;
                    ?>
                    <div class="card shadow-sm mt-2 mb-5 mx-5">
                        <div class="card-header py-3">
                            <h4>Edit Record: <?php echo $id; ?></h4>
                        </div>
                        <?php 
                        if(isset($_SESSION['validation_msg']) ){
                            echo "<h5 class='alert alert-danger text-center'>{$_SESSION['validation_msg']}</h5>";
                        }
                        unset($_SESSION['validation_msg']);
                        ?>

                       <form action="action.php?action=process_edit" method="POST" class="px-5">
                        <input type="hidden" name="table_name" value="<?php echo $table; ?>">
                        <input type="hidden" name="id_value" value="<?php echo $id; ?>">
                        <input type="hidden" name="primary_key" value="<?php echo $primaryKey; ?>">

                        <?php 
                            foreach ($item as $key => $value){
                            if ($key == $primaryKey) continue;
                            ?>
                
                        <p class="my-4 px-3 d-flex">
                            <label class="form-label"><?php echo ucwords(str_replace('_', ' ', $key)); ?>:</label>
                           <input type="text" class="form-control ml-5" name="<?php echo $key; ?>" value="<?php echo $value; ?>"required>
                        </p>
                        <?php }?>

                        <div class="mt-4 px-3 pb-4">
                            <button type="submit" name="update" class="btn btn-success px-3">Save Changes</button>
                            <a href="view_table.php?table=<?php echo $table; ?>" class="btn btn-outline-secondary px-4">Cancel</a>
                        </div>

                       </form> 
                    </div>

                 <?php    
                break;

                case 'process_edit':
                    if (isset($_POST['update'])) {
                        $tableName = $_POST['table_name'];
                        $idValue = $_POST['id_value'];
                        $primaryKey = $_POST['primary_key'];
                        

                        unset($_POST['table_name'], $_POST['id_value'], $_POST['primary_key'], $_POST['update']);

                        $updateParts = [];
                        $values = [];

                        foreach ($_POST as $key => $value) {
                            $trimmedValue = trim($value);
                            if ($trimmedValue === "" && $trimmedValue !== "0") {
                                $_SESSION['validation_msg'] = "Please fill in all fields!";
                                $connect = null;
                                header("Location: action.php?action=edit&table=$tableName&id=$idValue");
                                exit();
                            }
                            if ($key =='email') {

                                $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
                                if (!preg_match($pattern, $trimmedValue)) {
                                    $_SESSION['validation_msg'] = "Invalid Email format! Please use something like name@example.com";
                                    $connect = null;
                                    header("Location: action.php?action=edit&table=$tableName&id=$idValue");
                                    exit();
                                }

                                $checkEmail = $connect->prepare("SELECT email FROM $tableName WHERE email = ? AND $primaryKey != ?");
                                 $checkEmail->execute([$trimmedValue, $idValue]);
        
                                if ($checkEmail->rowCount() > 0) {
                                $_SESSION['validation_msg'] = "This Email is already registered!";
                                $checkEmail = null;
                                $connect = null;
                                header("Location: action.php?action=edit&table=$tableName&id=$idValue");
                                exit();
                                }
                                $checkEmail = null;
                            }

                            if ($key =='phone') {
                    
                            $phonePattern = "/^01[0125][0-9]{8}$/";

                            if (!preg_match($phonePattern, $trimmedValue)) {
                                $_SESSION['validation_msg'] = "Invalid Egyptian phone number!";
                                $connect = null;
                                header("Location: action.php?action=edit&table=$tableName&id=$idValue");
                                exit();
                            }
                        }

                            $updateParts[] = "`$key` = ?";
                            $values[] = $value;
                        }
                        $values[] = $idValue;

                        

                        $stmt = $connect->prepare("UPDATE `$tableName` SET " . implode(', ', $updateParts) . " WHERE `$primaryKey` = ?");
                        $stmt->execute($values);
                        $stmt = null;
                        $connect = null;

                        $_SESSION['msg'] = "Record Updated Successfully!";
                        header("Location: view_table.php?table=$tableName");
                        exit();
                    }



                 case 'add':
                        $stmtCol = $connect->prepare("SHOW COLUMNS FROM $table");
                        $stmtCol->execute();
                        $allColumns = $stmtCol->fetchAll(PDO::FETCH_ASSOC);
                        $stmtCol = null;
                 ?> 
                 <div class="card shadow-sm mt-1 mb-5 mx-5">
                        <div class="card-header py-3">
                            <h4>Add New Record to: <?php echo $table; ?></h4>
                        </div>
                        <?php 
                            if(isset($_SESSION['validation_msg']) ){
                                echo "<h5 class='alert alert-danger text-center'>{$_SESSION['validation_msg']}</h5>";
                            }
                            unset($_SESSION['validation_msg']);
                        ?>

                       <form action="action.php?action=process_add&table=<?php echo $table; ?>" method="POST" class="px-5">
                        <input type="hidden" name="table_name" value="<?php echo $table; ?>">
                        <?php 
                            foreach ($allColumns as $column){
                            if ($column['Extra'] == 'auto_increment' || $column['Type'] =='timestamp') continue;
                            $oldValue = "";
                            if(isset($_SESSION['form_data'][$column['Field']])){
                                $oldValue = $_SESSION['form_data'][$column['Field']];
                            }
                            ?>
                
                        <p class="my-4">
                           <input type="text" class="form-control" name="<?php echo $column['Field']; ?>" placeholder="<?php echo $column['Field'] ?>" value="<?php echo $oldValue; ?>" required>
                        </p>
                        <?php }?>

                        <div class="mt-4 pb-4">
                            <button type="submit" name="save_record" class="btn btn-success px-4">Save</button>
                            <a href="view_table.php?table=<?php echo $table; ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>

                       </form> 
                    </div> 
                    <?php 
                    unset($_SESSION['form_data']);
                    break;

                    case 'process_add':
                        if (isset($_POST['save_record'])) {
                            $tableName = $_POST['table_name'];
                            $_SESSION['form_data'] = $_POST;
                            unset($_POST['table_name']); 
                            unset($_POST['save_record']);

                            $columns = array_keys($_POST); 
                            $values  = array_values($_POST);

                            if (in_array("", $values)) {
                                $_SESSION['validation_msg'] = "Please fill in all fields!";
                                $connect = null;
                                header("Location: action.php?action=add&table=$tableName");
                                exit();
                            }
                            if (isset($_POST['email'])) {
                                $email = $_POST['email'];

                                $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
                                // Perl Regular Expressions
                                if (!preg_match($pattern, $email)) {
                                    $_SESSION['validation_msg'] = "Invalid Email format! Please use something like name@example.com";
                                    $connect = null;
                                    header("Location: action.php?action=add&table=$tableName");
                                    exit();
                                }

                            $checkEmail = $connect->prepare("SELECT email FROM $tableName WHERE email = ?");
                            $checkEmail->execute([$email]);
        
                            if ($checkEmail->rowCount() > 0) {
                                $_SESSION['validation_msg'] = "This Email is already registered!";
                                $checkEmail = null;
                                $connect = null;
                                header("Location: action.php?action=add&table=$tableName");
                                exit();
                            }
                            $checkEmail = null;
                        }

                        if (isset($_POST['phone'])) {
                            $phone = $_POST['phone'];

                            $phonePattern = "/^01[0125][0-9]{8}$/";

                            if (!preg_match($phonePattern, $phone)) {
                                $_SESSION['validation_msg'] = "Invalid Egyptian phone number!";
                                $connect = null;
                                header("Location: action.php?action=add&table=$tableName");
                                exit();
                            }
                        }
    
                        $placeholders = implode(', ', array_fill(0, count($columns), '?'));

                        $stmt = $connect->prepare("INSERT INTO $tableName (" . implode(', ', $columns) . ") VALUES ($placeholders)");
                        $stmt->execute($values); 
                        unset($_SESSION['form_data']); 
    
                        $_SESSION['msg'] = "Record Added Successfully!";
                        $stmt = null;
                        $connect = null;
                        header("Location: view_table.php?table=$tableName");
                        exit();
                    }      
                    break;
                }
                ?>

        </main>
    </div>
</div>


<?php
$connect = null;
include 'includes/temp/footer.php';
?>