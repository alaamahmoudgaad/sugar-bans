<?php 
require_once 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';
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
        window.scrollTo({ top: 0, behavior: 'smooth' });
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
                if (qty > 0) {
                    try {
                        const response = await fetch('api.php?action=updateStock', {
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
                            alert(`Added ${qty} x ${add.dataset.name} to cart`);
                            const activeBtn = document.querySelector('.sidebar-btn.active');
                            if (activeBtn && activeBtn.dataset.cat) loadMenu(activeBtn.dataset.cat);
                            else loadMenu('all');
                        } else {
                            alert(result.message || 'Failed to add to cart');
                        }
                    } catch(error) {
                        alert('Error adding to cart');
                        console.error(error);
                    }
                    qty = 0;
                    count.textContent = qty;
                } else {
                    alert('Please select quantity first');
                }
            };
        }
    });
}

const dessertsSidebar = document.getElementById('dessertsSidebar');
if (dessertsSidebar) {
    dessertsSidebar.addEventListener('click', function() {
        document.querySelectorAll('.sidebar-btn, .sub-btn').forEach(b => b.classList.remove('active'));
        const submenu = this.nextElementSibling;
        if (submenu) {
            const isOpen = submenu.classList.contains('show');
            document.querySelectorAll('.submenu').forEach(s => s.classList.remove('show'));
            if (!isOpen) submenu.classList.add('show');
        }
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

    const toggleBtn = document.getElementById('toggleBtn');
    const sidebar = document.getElementById('mainSidebar');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });
    }

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
