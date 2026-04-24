<aside class="col-md-2 side-wrapper px-2 shadow-sm">
    <div class="py-3 px-2 font-weight-bold border-bottom d-flex align-items-center">
        <i class="fas fa-layer-group"></i><span> TABLES</span>
    </div>
    
    <div class="mt-2">
        <?php

        try {
            $stmt = $connect->query("SHOW TABLES");
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $tableName = $row[0];
                echo '<a href="view_table.php?table=' . $tableName . '" class="side-item d-flex align-items-center">
                        <i class="fas fa-table mr-2"></i> ' . $tableName . ' </a>';
            }
        } 
        catch (PDOException $e) {
            echo "<div class='p-3 text-danger'>Error: " . $e->getMessage() . "</div>";
        }
        ?>
    </div>
</aside>