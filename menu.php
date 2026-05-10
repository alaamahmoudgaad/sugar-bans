<?php 
session_start();
require_once 'includes/db.php';

include 'includes/header.php';
include 'includes/navbar.php';

$cat = $_GET['cat'] ?? 'all';

$isLoggedIn = isset($_SESSION['user_id']) ? 'true' : 'false';
?>

<link rel="stylesheet" href="css/style.css">

<div class="main-container">

    <aside class="sidebar" id="mainSidebar">

        <div class="sidebar-header">
            <button class="toggle-sidebar-btn" id="toggleBtn">≡</button>
            <h3>MENU</h3>
        </div>

        <ul class="sidebar-menu">

            <li>
                <a href="menu.php?cat=all"
                   class="sidebar-btn <?= $cat=='all' ? 'active' : '' ?>">
                   All Products
                </a>
            </li>

            <li>
                <a href="menu.php?cat=drinks"
                   class="sidebar-btn <?= $cat=='drinks' ? 'active' : '' ?>">
                   Drinks
                </a>
            </li>

            <li>

                <button class="sidebar-btn dropdown-sidebar">
                    Desserts
                </button>

                <ul class="submenu <?= ($cat=='western' || $cat=='eastern') ? 'show' : '' ?>">

                    <li>
                        <a href="menu.php?cat=western"
                           class="sub-btn <?= $cat=='western' ? 'active' : '' ?>">
                           Western Dessert
                        </a>
                    </li>

                    <li>
                        <a href="menu.php?cat=eastern"
                           class="sub-btn <?= $cat=='eastern' ? 'active' : '' ?>">
                           Eastern Dessert
                        </a>
                    </li>

                </ul>

            </li>

            <li>
                <a href="menu.php?cat=cakes"
                   class="sidebar-btn <?= $cat=='cakes' ? 'active' : '' ?>">
                   Cakes
                </a>
            </li>

            <li>
                <a href="menu.php?cat=boxes"
                   class="sidebar-btn <?= $cat=='boxes' ? 'active' : '' ?>">
                   Boxes
                </a>
            </li>

        </ul>

    </aside>

    <div class="products-container">

        <?php

        if($cat == 'all'){

            $stmt = $connect->query("
                SELECT 
                    p.*,
                    c.name AS category_name
                FROM products p
                JOIN categories c
                ON p.category_id = c.category_id
                ORDER BY c.category_id
            ");

            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2 class='section-title'>All Products</h2>";

            echo "<div class='products-grid'>";

            foreach($products as $item){

                showCard($item);

            }

            echo "</div>";
        }

        elseif($cat == 'boxes'){

            $stmt = $connect->query("SELECT * FROM boxes");

            $boxes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2 class='section-title'>Boxes</h2>";

            echo "<div class='products-grid'>";

            foreach($boxes as $item){

                $item['product_name'] = $item['box_name'];
                $item['product_price'] = $item['box_price'];
                $item['product_image_url'] = $item['box_image_url'];
                $item['product_id'] = $item['box_id'];

                showCard($item);

            }

            echo "</div>";
        }

        else{

            $map = [
                "drinks" => 1,
                "cakes" => 3,
                "western" => 7,
                "eastern" => 8
            ];

            $id = $map[$cat] ?? 1;

            $stmtSections = $connect->prepare("
                SELECT category_id , name
                FROM categories
                WHERE parent_id = ?
            ");

            $stmtSections->execute([$id]);

            $sections = $stmtSections->fetchAll(PDO::FETCH_ASSOC);

            if(empty($sections)){

                $stmt = $connect->prepare("
                    SELECT *
                    FROM products
                    WHERE category_id = ?
                ");

                $stmt->execute([$id]);

                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo "<h2 class='section-title'>"
                     . ucfirst($cat) .
                     "</h2>";

                echo "<div class='products-grid'>";

                foreach($products as $item){

                    showCard($item);

                }

                echo "</div>";
            }

            else{

                $mainProducts = $connect->prepare("
                    SELECT *
                    FROM products
                    WHERE category_id = ?
                ");

                $mainProducts->execute([$id]);

                $mainItems = $mainProducts->fetchAll(PDO::FETCH_ASSOC);

                if(!empty($mainItems)){

                    echo "<h2 class='section-title'>"
                         . ucfirst($cat) .
                         "</h2>";

                    echo "<div class='products-grid'>";

                    foreach($mainItems as $item){

                        showCard($item);

                    }

                    echo "</div>";
                }

                foreach($sections as $section){

                    echo "<h2 class='section-title'>"
                         . $section['name'] .
                         "</h2>";

                    $stmtProducts = $connect->prepare("
                        SELECT *
                        FROM products
                        WHERE category_id = ?
                    ");

                    $stmtProducts->execute([
                        $section['category_id']
                    ]);

                    $products = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);

                    if(empty($products)){
                        continue;
                    }

                    echo "<div class='products-grid'>";

                    foreach($products as $item){

                        showCard($item);

                    }

                    echo "</div>";
                }
            }
        }

        ?>

    </div>

</div>

<?php

function showCard($item){

    $img = $item['product_image_url'] ?? '';
    $stock = $item['stock'] ?? 0;

?>

<div class="product-card"
     data-id="<?= $item['product_id'] ?>">

    <img src="<?= $img ?>"
         onerror="this.src='https://via.placeholder.com/300x280'">

    <h4><?= $item['product_name'] ?></h4>

    <p>
        <?= $item['description'] ?? 'Delicious item' ?>
    </p>

    <div class="price">
        <?= $item['product_price'] ?> EGP
    </div>

    <?php if($stock > 0): ?>

    <div class="cart-controls">

        <button class="minus">-</button>

        <span class="count">0</span>

        <button class="plus">+</button>

        <button class="add-btn">
            Add to Cart
        </button>

    </div>

    <?php else: ?>

    <div class="out-of-stock">
        Out Of Stock
    </div>

    <?php endif; ?>

</div>

<?php } ?>

<script>

const isLoggedIn = <?= $isLoggedIn ?>;

const toggleBtn = document.getElementById('toggleBtn');

const sidebar = document.getElementById('mainSidebar');

toggleBtn.onclick = function(){

    sidebar.classList.toggle('collapsed');

};

document.querySelectorAll('.dropdown-sidebar').forEach(btn => {

    btn.onclick = function(){

        this.nextElementSibling.classList.toggle('show');

    };

});

document.querySelectorAll('.product-card').forEach(card => {

    let minus = card.querySelector('.minus');

    let plus = card.querySelector('.plus');

    let count = card.querySelector('.count');

    let addBtn = card.querySelector('.add-btn');

    if(!addBtn) return;

    let qty = 0;

    plus.onclick = () => {

        qty++;

        count.innerText = qty;

    };

    minus.onclick = () => {

        if(qty > 0){

            qty--;

            count.innerText = qty;

        }

    };

    addBtn.onclick = () => {

        if(!isLoggedIn){

            alert('Please login first');

            window.location.href = 'login.php';

            return;
        }

        if(qty <= 0){

            alert('Please select quantity');

            return;
        }

        let productName =
            card.querySelector('h4').innerText;

        let confirmAdd = confirm(
            `Are you sure you want to add ${qty} × ${productName} to cart?`
        );

        if(!confirmAdd){
            return;
        }

        let formData = new FormData();

        formData.append('id', card.dataset.id);

        formData.append('qty', qty);

        fetch('cart.php', {

            method:'POST',

            body:formData

        })

        .then(() => {

            alert(`${productName} added to cart successfully`);

            qty = 0;

            count.innerText = 0;

        });

    };

});
</script>

<?php 
$connect = null;
include 'includes/footer.php'; 
?>