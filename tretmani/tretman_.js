const navbar = document.getElementById('navbar');
let scrolled = false;

window.onscroll = function () {
    if (window.pageYOffset > 100) {
        navbar.classList.remove('top');
        if (!scrolled) {
            navbar.style.transform = 'translateY(-100px)';
        }
        setTimeout(function () {
            navbar.style.transform = 'translateY(0)';
            scrolled = true;
        }, 200);
    } else {
        navbar.classList.add('top');
        scrolled = false;
    }
};

var counter = 1;
if (document.getElementById('radio' + counter + 'f')) {
    setInterval(function () {
        document.getElementById('radio' + counter + 'f').checked = true;
        counter++;
        if (counter > 4) {
            counter = 1;
        }
    }, 3000);
}

else if (document.getElementById('radio' + counter + 't')) {
    setInterval(function () {
        document.getElementById('radio' + counter + 't').checked = true;
        counter++;
        if (counter > 3) {
            counter = 1;
        }
    }, 4000);
}