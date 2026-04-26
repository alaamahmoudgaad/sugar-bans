<?php 
    require_once 'includes/db.php';
    include 'includes/header.php';
    include 'includes/navbar.php';
?>

<!-- Filter Bar -->
<div class="filter-bar">
    <button class="filter-btn active" data-cat="all">All Products</button>
    <button class="filter-btn" data-cat="drinks">Drinks</button>
    
    <div class="dropdown d-inline-block">
        <button class="filter-btn dropdown-toggle" type="button" id="dessertsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Desserts
        </button>
        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dessertsDropdown">
            <<li><a class="dropdown-item sub-btn" data-cat="western" href="#">
                 Western<br>
                <small class="text-muted">Donuts, Cupcakes, Cheesecakes, Cinnamon Rolls</small>
                </a></li>
            <<li><a class="dropdown-item sub-btn" data-cat="eastern" href="#">
    Eastern<br>
    <small class="text-muted">Kunafa, Basbousa, Qatayef, Baklava</small>
</a></li>
        </ul>
    </div>
    
    <button class="filter-btn" data-cat="cakes">Cakes</button>
    <button class="filter-btn" data-cat="boxes">Boxes</button>
</div>

<!-- Products Container -->
<div class="products-container" id="content">
    <div class="loading">Loading menu...</div>
</div>

<style>
    /* Filter Bar Style */
    .filter-bar {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 100px;
        margin-bottom: 40px;
        padding: 0 20px;
    }
    
    .filter-btn {
        background: #e8ddd0;
        border: none;
        padding: 10px 25px;
        border-radius: 30px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.2s;
    }
    
    .filter-btn:hover, .filter-btn.active {
        background: #c97e4a;
        color: white;
    }
    
    /* Dropdown menu style */
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
        background: #c97e4a;
        color: white;
    }
    
    .dropdown-item small {
        font-size: 11px;
        color: #b6a28e;
    }
    
    .dropdown-item:hover small {
        color: white;
    }
    
    /* Products Container */
    .products-container {
        padding: 0 30px 50px 30px;
        min-height: 60vh;
    }
    
    .section-title {
        font-size: 28px;
        color: #2c1e12;
        border-left: 5px solid #c97e4a;
        padding-left: 15px;
        margin: 30px 0 20px;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
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
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    
    .product-card img {
        width: 100%;
        height: 280px;
        object-fit: cover;
    }
    
    .product-card h4 {
        font-size: 18px;
        margin: 12px 0 5px;
    }
    
    .product-card p {
        font-size: 13px;
        color: #777;
        padding: 0 10px;
    }
    
    .price {
        font-weight: bold;
        color: #c97e4a;
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
        background: #ddd;
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
        background: #c97e4a;
    }
    
    .loading {
        text-align: center;
        padding: 60px;
        font-size: 18px;
        color: #b6a28e;
    }
    
    /* Active state for nested items */
    .sub-btn.active {
        background: #c97e4a !important;
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
    }
</style>

<!-- Bootstrap Icons (if not already in header) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    async function loadMenu(filterType) {
        const container = document.getElementById('content');
        container.innerHTML = '<div class="loading">Loading...</div>';
        
        let url = 'api.php?action=getAll';
        if (filterType === 'drinks') url = 'api.php?action=getByParent&id=1';
        else if (filterType === 'western') url = 'api.php?action=getByParent&id=7';
        else if (filterType === 'eastern') url = 'api.php?action=getByParent&id=8';
        else if (filterType === 'cakes') url = 'api.php?action=getByParent&id=3';
        else if (filterType === 'boxes') url = 'api.php?action=getBoxes';
        
        try {
            const res = await fetch(url);
            const data = await res.json();
            
            if (!data.length) {
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
                    html += `
                        <div class="product-card">
                            <img src="${img}" alt="${item.product_name}" onerror="this.src='https://via.placeholder.com/300x280?text=No+Image'">
                            <h4>${item.product_name}</h4>
                            <p>${item.description || 'Delicious treat'}</p>
                            <div class="price">${item.product_price} EGP</div>
                            <div class="cart-controls">
                                <button class="minus">-</button>
                                <span class="count">0</span>
                                <button class="plus">+</button>
                                <button class="add-btn" data-name="${item.product_name}">Add to Cart</button>
                            </div>
                        </div>
                    `;
                }
                html += `</div>`;
            }
            container.innerHTML = html;
            attachCartEvents();
        } catch(err) {
            container.innerHTML = '<div class="loading">Error loading menu </div>';
            console.error(err);
        }
    }
    
    function attachCartEvents() {
        document.querySelectorAll('.product-card').forEach(card => {
            let minus = card.querySelector('.minus');
            let plus = card.querySelector('.plus');
            let count = card.querySelector('.count');
            let add = card.querySelector('.add-btn');
            let qty = 0;
            
            minus.onclick = () => { if (qty > 0) { qty--; count.textContent = qty; } };
            plus.onclick = () => { qty++; count.textContent = qty; };
            add.onclick = () => { 
                if (qty > 0) { 
                    alert(`Added ${qty} x ${add.dataset.name} to cart`); 
                    qty = 0; 
                    count.textContent = qty; 
                } else { 
                    alert('Please select quantity first'); 
                } 
            };
        });
    }
    
    // Main filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (this.classList.contains('dropdown-toggle')) return;
            e.preventDefault();
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.sub-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            loadMenu(this.dataset.cat);
        });
    });
    
    // Sub buttons (Western, Eastern)
    document.querySelectorAll('.sub-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.sub-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            // Also highlight the Desserts dropdown button
            const dessertsBtn = document.getElementById('dessertsDropdown');
            if (dessertsBtn) dessertsBtn.classList.add('active');
            loadMenu(this.dataset.cat);
            // Close dropdown after selection
            const dropdown = bootstrap.Dropdown.getInstance(dessertsBtn);
            if (dropdown) dropdown.hide();
        });
    });
    
    // Load all on start
    loadMenu('all');
</script>

<?php include 'includes/footer.php'; ?>