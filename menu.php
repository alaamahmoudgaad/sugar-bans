<?php
session_start();
require_once 'includes/db.php';

include 'includes/header.php';
include 'includes/navbar.php';

$cat = $_GET['cat'] ?? 'all';
$isLoggedIn = isset($_SESSION['user_id']);

if (isset($_SESSION['error_msg'])) {
    echo "<script>
        alert('" . $_SESSION['error_msg'] . "');
    </script>";
    unset($_SESSION['error_msg']);
}
?> 

<div class="main-container">

    <aside class="sidebar" id="mainSidebar">
        <div class="sidebar-header">
            <button class="toggle-sidebar-btn" id="toggleBtn">≡</button>
            <h3>MENU</h3>
        </div>

        <ul class="sidebar-menu">

            <li>
                <a href="menu.php?cat=all" class="sidebar-btn <?= $cat == 'all' ? 'active' : '' ?>">All Products</a>
            </li>

            <li>
                <a href="menu.php?cat=drinks" class="sidebar-btn <?= $cat == 'drinks' ? 'active' : '' ?>">Drinks</a>
            </li>

            <li>
                <button class="sidebar-btn dropdown-sidebar">Desserts</button>

                <ul class="submenu <?= in_array($cat, ['western', 'eastern']) ? 'show' : '' ?>">

                    <li>
                        <a href="menu.php?cat=western" class="sub-btn <?= $cat == 'western' ? 'active' : '' ?>">
                            Western Dessert
                        </a>
                    </li>

                    <li>
                        <a href="menu.php?cat=eastern" class="sub-btn <?= $cat == 'eastern' ? 'active' : '' ?>">
                            Eastern Dessert
                        </a>
                    </li>

                </ul>
            </li>

            <li>
                <a href="menu.php?cat=cakes" class="sidebar-btn <?= $cat == 'cakes' ? 'active' : '' ?>">Cakes</a>
            </li>

            <li>
                <a href="menu.php?cat=boxes" class="sidebar-btn <?= $cat == 'boxes' ? 'active' : '' ?>">Boxes</a>
            </li>

        </ul>
    </aside>

    <div class="products-container">

<?php

if ($cat == 'all') {

    $stmt = $connect->query("
        SELECT p.*, c.name AS category_name
        FROM products p
        JOIN categories c ON p.category_id = c.category_id
    ");

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = null;

    echo "<h2 class='menu-title'>All Products</h2>";
    echo "<div class='products-grid'>";

    foreach ($products as $item) {
        showCard($item, 'product');
    }

    echo "</div>";
}

elseif ($cat == 'boxes') {

    $stmt = $connect->query("SELECT * FROM boxes");
    $boxes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = null;

    echo "<h2 class='menu-title'>Boxes</h2>";
    echo "<div class='products-grid'>";

    foreach ($boxes as $item) {

        $item['product_id'] = $item['box_id'];
        $item['product_name'] = $item['box_name'];
        $item['product_price'] = $item['box_price'];
        $item['product_image_url'] = $item['box_image_url'];

        showCard($item, 'box');
    }

    echo "</div>";
}

else {

    $map = [
        "drinks"  => 1,
        "cakes"   => 3,
        "western" => 7,
        "eastern" => 8
    ];

    $id = $map[$cat] ?? 0;

    if ($id === 0) {
        echo "<h2 class='menu-title'>Invalid Category</h2>";
        $connect = null;
        include 'includes/footer.php';
        exit;
    }

    $main = $connect->prepare("
        SELECT *
        FROM products
        WHERE category_id = ?
    ");

    $main->execute([$id]);
    $mainItems = $main->fetchAll(PDO::FETCH_ASSOC);
    $main = null;

    if (!empty($mainItems)) {

        echo "<h2 class='menu-title'>" . htmlspecialchars(ucfirst($cat)) . "</h2>";
        echo "<div class='products-grid'>";

        foreach ($mainItems as $item) {
            showCard($item, 'product');
        }

        echo "</div>";
    }


    /* SUB SECTIONS */
    $stmtSections = $connect->prepare("
        SELECT category_id, name
        FROM categories
        WHERE parent_id = ?
    ");

    $stmtSections->execute([$id]);
    $sections = $stmtSections->fetchAll(PDO::FETCH_ASSOC);
    $stmtSections = null;

    foreach ($sections as $section) {

        $stmtProducts = $connect->prepare("
            SELECT *
            FROM products
            WHERE category_id = ?
        ");

        $stmtProducts->execute([$section['category_id']]);
        $products = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);
        $stmtProducts = null;

        if (empty($products)) continue;

        echo "<h2 class='menu-title'>" . htmlspecialchars($section['name']) . "</h2>";
        echo "<div class='products-grid'>";

        foreach ($products as $item) {
            showCard($item, 'product');
        }

        echo "</div>";
    }
}

?>

    </div>
</div>

<?php
function showCard($item, $type)
{
    $img = !empty($item['product_image_url'])
        ? htmlspecialchars($item['product_image_url'])
        : 'https://via.placeholder.com/300x280';

    $stock = $item['stock'] ?? 0;
?>

<div class="product-card"
     data-id="<?= htmlspecialchars($item['product_id']) ?>"
     data-type="<?= htmlspecialchars($type) ?>"
     data-stock="<?= htmlspecialchars($stock) ?>">

    <img src="<?= $img ?>" onerror="this.src='https://via.placeholder.com/300x280'">

    <h4><?= htmlspecialchars($item['product_name']) ?></h4>

    <p><?= htmlspecialchars($item['description'] ?? 'Delicious item') ?></p>

    <div class="price">
        <?= htmlspecialchars($item['product_price']) ?> EGP
    </div>

    <?php if ($stock > 0): ?>
        <div class="cart-controls">
            <button class="minus">-</button>
            <span class="count">0</span>
            <button class="plus">+</button>
            <button class="add-btn">Add to Cart</button>
        </div>
    <?php else: ?>
        <div class="out-of-stock">Out Of Stock</div>
    <?php endif; ?>

</div>

<?php } ?>

<script>
const isLoggedIn = <?= json_encode($isLoggedIn) ?>;
</script>

<?php
include 'includes/footer.php';
$connect = null;
?>