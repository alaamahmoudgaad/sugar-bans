
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

document.addEventListener("submit", function (e) {

    const form = e.target;
    /* ================= REGISTER ================= */
     if (form.id === "register") {

        const fname = form.querySelector("#fname")?.value.trim();
        const lname = form.querySelector("#lname")?.value.trim();
        const email = form.querySelector("#email")?.value.trim();
        const pass = form.querySelector("#pass")?.value;
        const phone = form.querySelector("#phone")?.value.trim();
        const address = form.querySelector("#address")?.value.trim();

        const namePattern = /^[A-Za-z]{2,}$/;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        const phonePattern = /^01[0-9]{9}$/;

        if (!namePattern.test(fname)) {
            alert("First name invalid.");
            e.preventDefault();
            return;
        }

        if (!namePattern.test(lname)) {
            alert("Last name invalid.");
            e.preventDefault();
            return;
        }

        if (!emailPattern.test(email)) {
            alert("Invalid email.");
            e.preventDefault();
            return;
        }

        if (!passwordPattern.test(pass)) {
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
    /* ================= LOGIN ================= 
    else if (form.id === "loginForm") {

        const email = form.querySelector("#email")?.value.trim();
        const pass = form.querySelector("#pass")?.value;

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

        if (!emailPattern.test(email)) {
            alert("Invalid email.");
            e.preventDefault();
            return;
        }

        if (!passwordPattern.test(pass)) {
            alert("Invalid password.");
            e.preventDefault();
            return;
        }
    }
*/

    /* ================= CONTACT ================= */
    else if (form.id === "contact") {

        const fname = form.querySelector("#fname")?.value.trim();
        const lname = form.querySelector("#lname")?.value.trim();
        const subject = form.querySelector("#Subject")?.value;
        const message = form.querySelector("#message")?.value.trim();

        const namePattern = /^[A-Za-z]{2,}$/;

        if (!namePattern.test(fname) || !namePattern.test(lname)) {
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


document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('button[name="cancel_order"]').addEventListener('click', function (e) {
        let ok = confirm("Are you sure you want to cancel the order?");
        if (!ok) {
            e.preventDefault();
        }
    });
});