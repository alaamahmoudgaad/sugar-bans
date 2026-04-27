<?php 
session_start();
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
                        $stmtCol = $connect->query("SHOW COLUMNS FROM $table");
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
                    $item = $stmt->fetch();
                    ?>
                    <div class="container mt-4">
                            <div class="card shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4>Details: <?php echo $id; ?></h4>
                                    <a href="view_table.php?table=<?php echo $table; ?>" class="btn btn-sm btn-secondary">Back</a>
                                </div>

                                
                                <table class="table mb-0">
                                     <?php foreach ($item as $key => $value){ 
                                        if (!is_numeric($key)){?>
                                    <tr>
                                        <th class="ps-4" style="width: 30%;"><?php echo ucwords(str_replace('_', ' ', $key)); ?></th>
                                        <td><?php echo $value ? $value : ' '; ?></td>
                                        
                                    </tr>
                                    <?php }} ?>
                                </table>

                                <div class="card-footer d-flex align-items-center py-3 m">
                                        <a href="action.php?action=edit&table=<?php echo $table; ?>&id=<?php echo $id; ?>" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="action.php?action=delete&table=<?php echo $table; ?>&id=<?php echo $id; ?>" class="btn btn-danger btn-sm px-3 mr-5" onclick= "return confirm('Are you sure you want to delete this?')"><i class="fa-solid fa-trash"></i></a>
                                </div>
                            </div>
                    </div>

                <?php      
                    break;

                case 'edit':
                    $stmt = $connect->prepare("SELECT * FROM $table WHERE $primaryKey = ?");
                    $stmt->execute([$id]);
                    $item = $stmt->fetch();
                    ?>

                <div class="container mt-2 mb-5">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h4 class="mb-0">Edit Record: <?php echo $id; ?></h4>
                        </div>

                       <form action="update.php" method="POST px-5">
                        <input type="hidden" name="table_name" value="<?php echo $table; ?>">
                        <input type="hidden" name="id_value" value="<?php echo $id; ?>">
                        <input type="hidden" name="primary_key_col" value="<?php echo $primaryKey; ?>">

                        <?php 
                            foreach ($item as $key => $value){
                            if (!is_numeric($key) && $key != $primaryKey){
                            ?>
                
                        <p class="my-4 px-3 d-flex">
                            <label class="form-label"><?php echo ucwords(str_replace('_', ' ', $key)); ?>:</label>
                           <input type="text" class="form-control ml-5" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
                        </p>
                        <?php }}?>


                        <div class="mt-4 px-3 pb-4">
                            <button type="submit" class="btn btn-success px-3">Save Changes</button>
                            <a href="view_table.php?table=<?php echo $table; ?>" class="btn btn-outline-secondary px-4">Cancel</a>
                        </div>

                       </form> 
                    </div>
                </div>


                 <?php    
                    break;
                }
                ?>

        </main>
    </div>
</div>


<?php
include 'includes/temp/footer.php';
?>