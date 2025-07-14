document.getElementById('menuToggle').addEventListener('click', function () {
    document.getElementById('navMenu').classList.add('active');
});

document.getElementById('closeMenu').addEventListener('click', function () {
    document.getElementById('navMenu').classList.remove('active');
});