
function closeMenu() {
    if ($('.navbar-collapse').length && $('.navbar-collapse').hasClass('show')) {
        $('.navbar-toggler').click();
    }
}

if (document.querySelectorAll('.nav-link').length > 0) {
    $('.nav-link').click(closeMenu);
}


const loopTrack = document.getElementById('loop');

if (loopTrack) {
    const images = loopTrack.innerHTML;
    loopTrack.innerHTML = images + images;
}


function AddressShow(show) {
    const address = document.getElementById('address-div');

    if (!address) return;

    address.style.display = show ? 'block' : 'none';
}



const mybutton = document.getElementById("btn-back-to-top");

if (mybutton) {

    window.addEventListener("scroll", function () {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    });

    mybutton.addEventListener("click", function () {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
}

/* =========================
   LOGIN FORM VALIDATION

document.addEventListener("submit", function (e) {

    if (e.target && e.target.id === "loginForm") {

        const firstName = document.getElementById("fname")?.value.trim();
        const lastName = document.getElementById("lname")?.value.trim();
        const email = document.getElementById("email")?.value.trim();
        const password = document.getElementById("pass")?.value;

        const namePattern = /^[A-Za-z]{2,}$/;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

        if (!namePattern.test(firstName)) {
            alert("First name must be at least 2 letters.");
            e.preventDefault();
            return;
        }

        if (!namePattern.test(lastName)) {
            alert("Last name must be at least 2 letters.");
            e.preventDefault();
            return;
        }

        if (!emailPattern.test(email)) {
            alert("Invalid email.");
            e.preventDefault();
            return;
        }

        if (!passwordPattern.test(password)) {
            alert("Password must include uppercase, lowercase, number and 8+ characters.");
            e.preventDefault();
            return;
        }
    }
});
*/
/* =========================
   REGISTER FORM VALIDATION
========================= */

document.addEventListener("submit", function (e) {

    if (e.target && e.target.id === "register") {

        const firstName = document.getElementById("fname")?.value.trim();
        const lastName = document.getElementById("lname")?.value.trim();
        const email = document.getElementById("email")?.value.trim();
        const password = document.getElementById("pass")?.value;
        const phone = document.getElementById("phone")?.value.trim();
        const address = document.getElementById("address")?.value.trim();

        const namePattern = /^[A-Za-z]{2,}$/;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        const phonePattern = /^[0-9]{11}$/;

        if (!namePattern.test(firstName)) {
            alert("First name invalid.");
            e.preventDefault();
            return;
        }

        if (!namePattern.test(lastName)) {
            alert("Last name invalid.");
            e.preventDefault();
            return;
        }

        if (!emailPattern.test(email)) {
            alert("Invalid email.");
            e.preventDefault();
            return;
        }

        if (!passwordPattern.test(password)) {
            alert("Weak password.");
            e.preventDefault();
            return;
        }

        if (!phonePattern.test(phone)) {
            alert("Phone must be 11 digits.");
            e.preventDefault();
            return;
        }

        if (address.length < 10) {
            alert("Address too short.");
            e.preventDefault();
            return;
        }
    }
});

/* =========================
   CONTACT US VALIDATION
========================= */

document.addEventListener("submit", function (e) {

    if (e.target && e.target.id === "contact") {

        const firstName = document.getElementById("fname")?.value.trim();
        const lastName = document.getElementById("lname")?.value.trim();
        const subject = document.getElementById("Subject")?.value;
        const message = document.getElementById("message")?.value.trim();

        const namePattern = /^[A-Za-z]{2,}$/;

        if (!namePattern.test(firstName) || !namePattern.test(lastName)) {
            alert("Invalid name.");
            e.preventDefault();
            return;
        }

        if (!subject) {
            alert("Select subject.");
            e.preventDefault();
            return;
        }

        if (message.length < 10) {
            alert("Message too short.");
            e.preventDefault();
            return;
        }
    }
});




const toggleBtn = document.getElementById('toggleBtn');

if (toggleBtn) {
    toggleBtn.onclick = function () {
        const sidebar = document.getElementById('mainSidebar');

        if (sidebar) {
            sidebar.classList.toggle('collapsed');
        }
    };
}

document.querySelectorAll('.dropdown-sidebar').forEach(btn => {

    btn.onclick = () => {
        btn.nextElementSibling.classList.toggle('show');
    };

});

document.querySelectorAll('.product-card').forEach(card => {

    let stock = parseInt(card.dataset.stock || 0);

    let qty = 0;

    let minus = card.querySelector('.minus');
    let plus = card.querySelector('.plus');
    let count = card.querySelector('.count');
    let addBtn = card.querySelector('.add-btn');

    if (!addBtn) {
        return;
    }

    plus.onclick = () => {

        if (stock > 0 && qty >= stock) {
            alert('You reached maximum available stock');
            return;
        }

        qty++;

        count.innerText = qty;
    };

    minus.onclick = () => {

        if (qty > 0) {

            qty--;

            count.innerText = qty;
        }

    };

    addBtn.onclick = () => {

        if (!isLoggedIn) {

            alert('Please login first');

            window.location.href = 'login.php';

            return;
        }

        if (qty <= 0) {

            alert('Please select quantity');

            return;
        }

        let name = card.querySelector('h4').innerText;

        if (!confirm(`Add ${qty} × ${name} to cart?`)) {
            return;
        }

        let formData = new FormData();

        formData.append('id', card.dataset.id);
        formData.append('qty', qty);
        formData.append('type', card.dataset.type);

        fetch('cart.php', {
            method: 'POST',
            body: formData
        })

        .then(response => response.text())

        .then(() => {

            alert('Added successfully');

            qty = 0;

            count.innerText = 0;

        })

        .catch(() => {

            alert('Something went wrong');

        });

    };

});