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
            $displayTitle = "";
            if (isset($_GET['table'])) {
                $currentTable = $_GET['table'];
                $spaceName = str_replace('_', ' ', $currentTable);
                $displayTitle = ucwords($spaceName);
            }
            $statement = $connect->prepare("SELECT * FROM $currentTable");
            $statement->execute();
            $currentTableCount =$statement->rowCount();
            $result = $statement->fetchAll();
            ?>

            <div class="d-flex justify-content-between align-items-center mb-2 div-title">
            <h2 class="table-title" ><?php echo $displayTitle;?> <span class="badge"><?php echo $currentTableCount;?></span></h2>
            
            <a href="add.php?table=<?php echo $currentTable; ?>" class="btn shadow-sm btn-add"><i class="fa-solid fa-plus"></i>Add New</a>
        </div>
        <?php 
            $columns = [];
            if (!empty($result)) {
            $columns = array_filter(array_keys($result[0]), function($key) {
             return !is_numeric($key);
            });
            $columns = array_slice($columns, 0, 3);
            }
             if(isset($_SESSION['message']) ){
                    echo "<h4 class='alert alert-success text-center'>".$_SESSION['message']."</h4>";
                }
            unset($_SESSION['message']);

        ?>
            <div class="card shadow-sm mt-4 overflow-hidden">
    
                <div class="table-responsive">
        
                    <table class="table table-hover mb-0 w-100 border 1px">
                        <thead>
                            <tr>
                                <?php foreach ($columns as $col): ?>
                                    <th><?php echo ucwords(str_replace('_', ' ', $col)); ?></th>
                                    <?php endforeach; ?>
                                    <th>Actions</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $row): ?>
                                <tr>
                                    <?php foreach ($columns as $col): ?>
                                    <td><?php echo $row[$col]; ?></td>
                                        <?php endforeach; ?>
                                    <td class="d-flex flex-md-nowrap justify-content-center operation">
                                     <a href="action.php?action=show&table=<?php echo $currentTable; ?>&id=<?php echo array_values($row)[0]; ?>" class="btn btn-sm btn-success"><i class="fa-solid fa-eye"></i></a>
                                    <a href="action.php?action=edit&table=<?php echo $currentTable; ?>&id=<?php echo array_values($row)[0]; ?>" class="btn btn-sm btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="action.php?action=delete&table=<?php echo $currentTable; ?>&id=<?php echo array_values($row)[0]; ?>" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                               </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>

        </main>
    </div>
</div>


<?php
include 'includes/temp/footer.php';
?>