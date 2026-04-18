function closeMenu() {
    if ($('.navbar-collapse').hasClass('show')) {
        $('.navbar-toggler').click();
    }
}
$('.nav-link').click(closeMenu);


var loopTrack = document.getElementById('loop');
var images = loopTrack.innerHTML;
loopTrack.innerHTML = images + images;


function AddressShow(show) {
    const address= document.getElementById('address-div');
    if (show) {
        address.style.display = 'block';
    } else {
        address.style.display = 'none';
    }
}

