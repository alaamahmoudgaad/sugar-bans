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

//login form
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



