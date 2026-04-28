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
    const form = e.target;
    
    if (form.id === "loginForm") {
        let firstName = document.getElementById("fname")?.value.trim();
        let lastName = document.getElementById("lname")?.value.trim();
        let email = document.getElementById("email")?.value.trim();
        let password = document.getElementById("pass")?.value;

        let namePattern = /^[A-Za-z]{2,}$/;
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

        if (!firstName || !namePattern.test(firstName)) {
            alert("The first name consists of letters only (two letters or more)");
            e.preventDefault();
            return;
        }
        
        if (!lastName || !namePattern.test(lastName)) {
            alert("The Last Name consists of letters only (two letters or more)");
            e.preventDefault();
            return;
        }

        if (!email || !emailPattern.test(email)) {
            alert("Email is incorrect.");
            e.preventDefault();
            return;
        }

        if (!password || !passwordPattern.test(password)) {
            alert("Password must be 8 characters long and include a capital letter, a lowercase letter, and a number.");      
            e.preventDefault();
            return;
        }
    }
    
    else if (form.id === "register") {
        let firstName = document.getElementById("fname")?.value.trim();
        let lastName = document.getElementById("lname")?.value.trim();
        let email = document.getElementById("email")?.value.trim();
        let password = document.getElementById("pass")?.value;
        let phone = document.getElementById("phone")?.value.trim();
        let address = document.getElementById("address")?.value.trim();

        let namePattern = /^[A-Za-z]{2,}$/;
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        let phonePattern = /^[0-9]{11}$/;

        if (!firstName || !namePattern.test(firstName)) {
            alert("First name must be letters only and at least 2 characters.");
            e.preventDefault();
            return;
        }

        if (!lastName || !namePattern.test(lastName)) {
            alert("Last name must be letters only and at least 2 characters.");
            e.preventDefault();
            return;
        }

        if (!email || !emailPattern.test(email)) {
            alert("Please enter a valid email address.");
            e.preventDefault();
            return;
        }

        if (!password || !passwordPattern.test(password)) {
            alert("Password must be 8+ characters, with an uppercase, lowercase, and a number.");
            e.preventDefault();
            return;
        }

        if (!phone || !phonePattern.test(phone)) {
            alert("Please enter a valid 11-digit phone number.");
            e.preventDefault();
            return;
        }

        if (!address || address.length < 10) {
            alert("Please provide a more detailed address (at least 10 characters).");
            e.preventDefault();
            return;
        }
    }
    
    else if (form.id === "contact") {
        let firstName = document.getElementById("fname")?.value.trim();
        let lastName = document.getElementById("lname")?.value.trim();
        let subject = document.getElementById("Subject")?.value;
        let message = document.getElementById("message")?.value.trim();

        let namePattern = /^[A-Za-z]{2,}$/;

        if (!firstName || !lastName || !namePattern.test(firstName) || !namePattern.test(lastName)) {
            alert("Names must be at least 2 characters long and contain only letters.");
            e.preventDefault();
            return;
        }
        
        if (!subject || subject === "") {
            alert("Please select a subject for your message.");
            e.preventDefault();
            return;
        }
        
        if (!message || message.length < 10) {
            alert("Please write a message with at least 10 characters.");
            e.preventDefault();
            return;
        }
    }
});

//menue





