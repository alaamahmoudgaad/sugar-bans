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
                }
                switch($action) {
                case 'delete':
                    $stmt = $connect->prepare("DELETE FROM $table WHERE $primaryKey = ?");
                    $stmt->execute([$id]) ;
                    $_SESSION["message"] ="Deleted successfully";
                    header("Location: view_table.php?table=$table");
                    exit();
                    break;
            
                case 'show':
                    $stmt = $connect->prepare("SELECT * FROM $table WHERE $primaryKey = ?");
                    $stmt->execute([$id]);
                    $item = $stmt->fetch(PDO::FETCH_ASSOC);
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
                    ?>
                    <div class="card shadow-sm mt-2 mb-5 mx-5">
                        <div class="card-header bg-white py-3">
                            <h4>Edit Record: <?php echo $id; ?></h4>
                        </div>

                       <form action="update.php" method="POST" class="px-5">
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
                            <button type="submit" class="btn btn-success px-3">Save Changes</button>
                            <a href="view_table.php?table=<?php echo $table; ?>" class="btn btn-outline-secondary px-4">Cancel</a>
                        </div>

                       </form> 
                    </div>

                 <?php    
                    break;

                    case 'add':
                        $stmtCol = $connect->prepare("SHOW COLUMNS FROM $table");
                        $stmtCol->execute();
                        $allColumns = $stmtCol->fetchAll(PDO::FETCH_ASSOC);
                 ?> 
                 <div class="card shadow-sm mt-2 mb-5 mx-5">
                        <div class="card-header bg-white py-3">
                            <h4>Add New Record to: <?php echo $table; ?></h4>
                        </div>
                        <?php 
                            if(isset($_SESSION['validation_msg']) ){
                                echo "<h5 class='alert alert-danger text-center'>{$_SESSION['validation_msg']}</h5>";
                            }
                            unset($_SESSION['validation_msg']);
                        ?>

                       <form action="insert.php" method="POST" class="px-5">
                        <input type="hidden" name="table_name" value="<?php echo $table; ?>">
                        <?php 
                            foreach ($allColumns as $column){
                            if ($column['Extra'] == 'auto_increment' || $column['Type'] =='timestamp') continue;
                            ?>
                
                        <p class="my-4">
                           <input type="text" class="form-control" name="<?php echo $column['Field']; ?>" placeholder="<?php echo $column['Field'] ?>" >
                        </p>
                        <?php }?>

                        <div class="mt-4 pb-4">
                            <button type="submit" name="save_record" class="btn btn-success px-4">Save</button>
                            <a href="view_table.php?table=<?php echo $table; ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>

                       </form> 
                    </div>       

                  



                <?php  
                }
                ?>

        </main>
    </div>
</div>


<?php
include 'includes/temp/footer.php';
?>