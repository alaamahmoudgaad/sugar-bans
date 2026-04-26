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
                    
                    break;
                case 'edit':
                   
                    break;
            }


                


            ?>




        </main>
    </div>
</div>


<?php
include 'includes/temp/footer.php';
?>