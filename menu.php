<?php 
session_start();
require_once 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

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
            <li><button class="sidebar-btn active" data-cat="all">All Products</button></li>
            <li><button class="sidebar-btn" data-cat="drinks">Drinks</button></li>
            <li>
                <button class="sidebar-btn dropdown-sidebar" id="dessertsSidebar">Desserts </button>
                <ul class="submenu">
                    <li><button class="sub-btn" data-cat="western">Western Dessert</button></li>
                    <li><button class="sub-btn" data-cat="eastern">Eastern Dessert</button></li>
                </ul>
            </li>
            <li><button class="sidebar-btn" data-cat="cakes">Cakes</button></li>
            <li><button class="sidebar-btn" data-cat="boxes">Boxes</button></li>
        </ul>
    </aside>
    <div class="products-container" id="content">
        <div class="loading">Loading menu...</div>
    </div>
</div>

<script >
    async function loadMenu(filterType) {
    const container = document.getElementById('content');
    if (!container) return;
    container.innerHTML = '<div class="loading">Loading...</div>';
    let url = 'api.php?action=getAll';
    if (filterType === 'drinks') url = 'api.php?action=getByParent&id=1';
    else if (filterType === 'western') url = 'api.php?action=getByParent&id=7';
    else if (filterType === 'eastern') url = 'api.php?action=getByParent&id=8';
    else if (filterType === 'cakes') url = 'api.php?action=getByParent&id=3';
    else if (filterType === 'boxes') url = 'api.php?action=getBoxes';
    try {
        const res = await fetch(url);
        const text = await res.text();
        const data = JSON.parse(text);
        if (!Array.isArray(data) || data.length === 0) {
            container.innerHTML = '<div class="loading">No items found</div>';
            return;
        }
        let html = '';
        for (let section of data) {
            if (section.sectionName && section.sectionName !== '') {
                html += `<h2 class="section-title">${section.sectionName}</h2>`;
            }
            html += `<div class="products-grid">`;
            for (let item of section.products) {
                let img = item.product_image_url || 'https://via.placeholder.com/300x280?text=Yummy';
                let stock = item.stock || 0;
                let isInStock = stock > 0;
                html += `
                    <div class="product-card">
                        <img src="${img}" alt="${item.product_name}" onerror="this.src='https://via.placeholder.com/300x280?text=No+Image'">
                        <h4>${item.product_name}</h4>
                        <p>${item.description || (item.products_included ? 'Includes: ' + item.products_included : 'Delicious treat')}</p>
                        <div class="price">${item.product_price} EGP</div>
                        ${isInStock ? `
                            <div class="cart-controls">
                                <button class="minus">-</button>
                                <span class="count">0</span>
                                <button class="plus">+</button>
                                <button class="add-btn" data-id="${item.product_id || item.box_id}" data-name="${item.product_name}" data-max="${stock}" data-type="${item.product_id ? 'product' : 'box'}">Add to Cart</button>
                            </div>
                        ` : `<div class="out-of-stock">❌ Out of Stock</div>`}
                    </div>
                `;
            }
            html += `</div>`;
        }
        container.innerHTML = html;
        attachCartEvents();
    } catch(err) {
        container.innerHTML = '<div class="loading">Error loading menu</div>';
        console.error(err);
    }
}

function attachCartEvents() {
    document.querySelectorAll('.product-card').forEach(card => {
        let minus = card.querySelector('.minus');
        let plus = card.querySelector('.plus');
        let count = card.querySelector('.count');
        let add = card.querySelector('.add-btn');
        if (!add) return;
        let qty = 0;
        let maxStock = parseInt(add.dataset.max) || 0;
        if (minus) {
            minus.onclick = () => { if (qty > 0) { qty--; count.textContent = qty; } };
        }
        if (plus) {
            plus.onclick = () => {
                if (qty < maxStock) { qty++; count.textContent = qty; }
                else { alert(`Only ${maxStock} items available in stock`); }
            };
        }
if (add) {
    add.onclick = async () => {
        const isLoggedIn = <?php echo $isLoggedIn; ?>;

        if (!isLoggedIn) {
            alert('Please login first to add items to your cart!');
            window.location.href = 'login.php';
            return;
        }

        if (qty > 0) {
            try {
                const response = await fetch('api.php?action=addToCart', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        product_id: add.dataset.id,
                        quantity: qty,
                        type: add.dataset.type || 'product'
                    })
                });
                const result = await response.json();
                if (result.success) {
                    alert(`Added ${qty} x ${add.dataset.name} to cart!`);
                    qty = 0;
                    count.textContent = qty;
                }
            } catch(error) {
                alert('Error adding to cart');
                console.error(error);
            }
        } else {
            alert('Please select quantity first');
        }
    };
}
    });
}

const toggleBtn = document.getElementById('toggleBtn');
const sidebar = document.getElementById('mainSidebar');
if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        this.textContent = '≡';
    });
}

const dessertsSidebar = document.getElementById('dessertsSidebar');
if (dessertsSidebar) {
    dessertsSidebar.addEventListener('click', function() {
        const submenu = this.nextElementSibling;
        if (submenu) submenu.classList.toggle('show');
    });
}

const sidebarBtns = document.querySelectorAll('.sidebar-btn:not(.dropdown-sidebar)');
if (sidebarBtns.length) {
    sidebarBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.sidebar-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.sub-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            loadMenu(this.dataset.cat);
        });
    });
}

const subBtns = document.querySelectorAll('.sub-btn');
if (subBtns.length) {
    subBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.sidebar-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.sub-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            loadMenu(this.dataset.cat);
        });
    });
}

document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const categoryFromUrl = urlParams.get('cat');
    if (categoryFromUrl) {
        loadMenu(categoryFromUrl);
        const targetBtn = document.querySelector(`[data-cat="${categoryFromUrl}"]`);
        if (targetBtn) {
            document.querySelectorAll('.sidebar-btn, .sub-btn').forEach(b => b.classList.remove('active'));
            targetBtn.classList.add('active');
            const parentSubmenu = targetBtn.closest('.submenu');
            if (parentSubmenu) parentSubmenu.classList.add('show');
        }
    } else {
        loadMenu('all');
    }
});
</script>

<?php include 'includes/footer.php'; ?>

<style>
.main-container {
    display: flex;
    gap: 30px;
    margin-top: 120px;
    padding: 0 30px;
}
.sidebar {
    width: 260px;
    background: #b19a80;
    border-radius: 15px;
    padding: 20px;
    height: fit-content;
    position: sticky;
    top: 100px;
    transition: width 0.3s ease;
    overflow: hidden;
    white-space: nowrap;
}
.sidebar.collapsed {
    width: 80px;
}
.sidebar.collapsed .sidebar-header {
    justify-content: center;
    gap: 0;
}
.sidebar.collapsed .sidebar-header h3 {
    display: none;
}
.sidebar.collapsed .sidebar-menu li button {
    opacity: 0;
    pointer-events: none;
    padding: 12px 0;
    margin: 0;
    width: 100%;
    text-indent: -9999px;
}
.sidebar.collapsed .sidebar-menu li {
    list-style: none;
    margin: 0;
    padding: 0;
}
.sidebar.collapsed .submenu .sub-btn {
    opacity: 0;
    pointer-events: none;
    text-indent: -9999px;
}
.sidebar-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #4c3a2f;
}
.sidebar-header h3 {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
    color: #2c1e12;
}
.sidebar-menu {
    list-style: none;
    padding: 0;
}
.sidebar-btn {
    width: 100%;
    text-align: left;
    background: transparent;
    border: none;
    padding: 12px 15px;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.2s;
    font-size: 16px;
    color: #2c1e12;
}
.sidebar-btn:hover, .sidebar-btn.active {
    background: #30200e;
    color: white;
}
.submenu {
    list-style: none;
    padding-left: 20px;
    margin: 5px 0 10px 0;
    display: none;
}
.submenu.show {
    display: block;
}
.submenu .sub-btn {
    width: 100%;
    text-align: left;
    background: transparent;
    border: none;
    padding: 8px 15px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
}
.products-container {
    flex: 1;
    min-height: 60vh;
    padding: 0 30px 50px 30px;
}
.toggle-sidebar-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #ca8740;
    border: none;
    color: white;
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: 0.3s;
    flex-shrink: 0;
}
.dropdown-menu {
    background: #2c1e12;
    border: 1px solid #4a3422;
    border-radius: 12px;
    padding: 8px;
    min-width: 260px;
}
.dropdown-item {
    color: white;
    padding: 12px 16px;
    border-radius: 8px;
    transition: 0.2s;
}
.dropdown-item:hover {
    background: #ca8740;
    color: white;
}
.dropdown-item small {
    font-size: 11px;
    color: #b6a28e;
}
.dropdown-item:hover small {
    color: white;
}
.section-title {
    font-size: 28px;
    color: #2c1e12;
    border-left: 5px solid #ca8740;
    padding-left: 15px;
    margin: 30px 0 20px;
}
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}
.product-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: 0.2s;
    text-align: center;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px #b6a28e;
}
.product-card img {
    width: 100%;
    height: 320px;
    object-fit: cover;
}
.product-card h4 {
    font-size: 22px;
    margin: 12px 0 5px;
}
.product-card p {
    font-size: 15px;
    color: #51453a;
    padding: 0 10px;
}
.price {
    font-weight: bold;
    color: #ca8740;
    font-size: 18px;
    margin: 8px 0;
}
.cart-controls {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin: 10px 0 15px;
}
.cart-controls button {
    background: #b6a28e;
    border: none;
    padding: 5px 12px;
    border-radius: 8px;
    cursor: pointer;
}
.add-btn {
    background: #5d4037;
    color: white;
    border-radius: 20px;
    padding: 5px 15px;
}
.add-btn:hover {
    background: #ca8740;
}
.loading {
    text-align: center;
    padding: 60px;
    font-size: 18px;
    color: #b6a28e;
}
.sub-btn.active {
    background: #413832 !important;
    color: white !important;
}
@media (max-width: 768px) {
    .filter-bar {
        margin-top: 80px;
        gap: 10px;
    }
    .filter-btn {
        padding: 8px 18px;
        font-size: 14px;
    }
    .products-container {
        padding: 0 15px 30px 15px;
    }
    .product-card img {
        height: 220px;
    }
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    }
}
.stock-info {
    font-size: 12px;
    color: #2c1e12;
    margin: 5px 0;
}
.out-of-stock {
    background: #f8d7da;
    color: #721c24;
    padding: 8px;
    border-radius: 20px;
    margin: 10px 15px;
    font-size: 14px;
    font-weight: bold;
}
.cart-controls button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

</style>