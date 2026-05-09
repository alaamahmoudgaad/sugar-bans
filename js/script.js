function closeMenu() {
    if ($('.navbar-collapse').hasClass('show')) {
        $('.navbar-toggler').click();
    }
}
$('.nav-link').click(closeMenu);

var loopTrack = document.getElementById('loop');

if (loopTrack) {
    var images = loopTrack.innerHTML;
    loopTrack.innerHTML = images + images;
}

function AddressShow(show) {
    const address= document.getElementById('address-div');
    if (show) {
        address.style.display = 'block';
    } else {
        address.style.display = 'none';
    }
}

//Main page
let mybutton = document.getElementById("btn-back-to-top");

    window.onscroll = function () {
        scrollFunction();
    };

    function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
    }

    mybutton.addEventListener("click", backToTop);

    function backToTop() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }
//vaildation
document.addEventListener("submit", function (e) {
      
    if (e.target && e.target.id === "loginForm") {
        
        let firstName = document.getElementById("fname").value.trim();
        let lastName = document.getElementById("lname").value.trim();
        let email = document.getElementById("email").value.trim();
        let password = document.getElementById("pass").value;

        let namePattern = /^[A-Za-z]{2,}$/;
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

        if (!namePattern.test(firstName)) {
            alert("The first name consists of letters only (two letters or more)");
            e.preventDefault();
            return;
        }
        
        if (!namePattern.test(lastName)) {
            alert("The Last Name consists of letters only (two letters or more)");
            e.preventDefault();
            return;
        }

        if (!emailPattern.test(email)) {
            alert("Email is incorrect.");
            e.preventDefault();
            return;
        }

        if (!passwordPattern.test(password)) {
            alert("Password must be 8 characters long and include a capital letter, a lowercase letter, and a number.");      
            e.preventDefault();
            return;
        }
    }
});

//register form
document.addEventListener("submit", function (e) {
    
    if (e.target && (e.target.id === "register")) {
        
        let firstName = document.getElementById("fname").value.trim();
        let lastName = document.getElementById("lname").value.trim();
        let email = document.getElementById("email").value.trim();
        let password = document.getElementById("pass").value;
        let phone = document.getElementById("phone").value.trim();
        let address = document.getElementById("address").value.trim();


        let namePattern = /^[A-Za-z]{2,}$/;
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        let phonePattern = /^[0-9]{11}$/;

        if (!namePattern.test(firstName)) {
            alert("First name must be letters only and at least 2 characters.");
            e.preventDefault();
            return;
        }

        if (!namePattern.test(lastName)) {
            alert("Last name must be letters only and at least 2 characters.");
            e.preventDefault();
            return;
        }

        if (!emailPattern.test(email)) {
            alert("Please enter a valid email address.");
            e.preventDefault();
            return;
        }

        if (!passwordPattern.test(password)) {
            alert("Password must be 8+ characters, with an uppercase, lowercase, and a number.");
            e.preventDefault();
            return;
        }

        if (!phonePattern.test(phone)) {
            alert("Please enter a valid 11-digit phone number.");
            e.preventDefault();
            return;
        }

        if (address.length < 10) {
            alert("Please provide a more detailed address.");
            e.preventDefault();
            return;
        }
    }
});

//contact us form
document.addEventListener("submit", function (e) {

    if (e.target && e.target.id === "contact") {
        
        let firstName = document.getElementById("fname").value.trim();
        let lastName = document.getElementById("lname").value.trim();
        let subject = document.getElementById("Subject").value;
        let message = document.getElementById("message").value.trim();

        let namePattern = /^[A-Za-z]{2,}$/;


        if (!namePattern.test(firstName) || !namePattern.test(lastName)) {
            alert("Names must be at least 2 characters long and contain only letters.");
            e.preventDefault();
            return;
        }

        
        if (subject === "" || subject === null) {
            alert("Please select a subject for your message.");
            e.preventDefault();
            return;
        }

        
        if (message.length < 10) {
            alert("Please write a message with at least 10 characters.");
            e.preventDefault();
            return;
        }
    }
});





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