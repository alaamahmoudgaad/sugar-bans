<?php 
include 'includes/db/db.php';
include 'includes/temp/header.php';
include 'includes/temp/navbar.php';
include 'includes/temp/aside.php';

$currentTable = $_GET['table'];
// echo "<h2> أنتِ الآن بتعرضي بيانات جدول: " . $currentTable . "</h2>";
// SELECT * FROM $currentTable;
?>


<?php
include 'includes/temp/footer.php';
?>