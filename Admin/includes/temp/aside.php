<aside class="col-md-2 side-wrapper px-2 shadow-sm">
    <div class="py-3 px-2 font-weight-bold border-bottom d-flex align-items-center">
        <i class="fas fa-layer-group"></i><span> TABLES</span>
    </div>
    
    <div class="mt-2">
        <?php

        try {
            $stmt = $connect->prepare("SHOW TABLES");
            $stmt->execute();
            $allTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($allTables as $row) {
                echo"<a href='view_table.php?table=$row' class='side-item d-flex align-items-center'>
                        <i class='fas fa-table mr-2'></i>$row</a>";
            }
        } 
        catch (PDOException $e) {
            echo "<div class='p-3 text-danger'>Error: " . $e->getMessage() . "</div>";
        }
        ?>
    </div>
</aside>