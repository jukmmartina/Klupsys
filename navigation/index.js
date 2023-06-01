const navbar = document.getElementById('navbar');
let scrolled = false;

window.onscroll = function () {
    if (window.pageYOffset > 100) {
        navbar.classList.add('top');
        if (!scrolled) {
            navbar.style.transform = 'translateY(-100px)';
        }
        setTimeout(function () {
            navbar.style.transform = 'translateY(0)';
            scrolled = true;
        }, 200);
    } else {
        navbar.classList.remove('top');
        scrolled = false;
    }
};

var counter = 1;
setInterval(function () {
    document.getElementById('radio' + counter).checked = true;
    counter++;
    if (counter > 7) {
        counter = 1;
    }
}, 3000);