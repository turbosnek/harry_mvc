document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menuToggle');
    const closeMenu = document.getElementById('closeMenu');
    const navMenu = document.getElementById('navMenu');
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    const submenuItems = document.querySelectorAll('.has-submenu');

    const isMobile = () => window.innerWidth <= 768;

    // Otevření mobilního menu
    menuToggle?.addEventListener('click', () => {
        navMenu.classList.add('active');
    });

    closeMenu?.addEventListener('click', () => {
        navMenu.classList.remove('active');
    });

    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();

            const parentLi = this.closest('.has-submenu');
            const submenu = parentLi.querySelector('.submenu');
            const isOpen = parentLi.classList.contains('open');

            // Zavři všechna ostatní submenu
            submenuItems.forEach(item => {
                if (item !== parentLi) {
                    item.classList.remove('open');
                    const sub = item.querySelector('.submenu');
                    if (sub) sub.style.maxHeight = null;
                }
            });

            if (isOpen) {
                parentLi.classList.remove('open');
                submenu.style.maxHeight = null;
            } else {
                parentLi.classList.add('open');
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
            }
        });
    });

    // Zavření submenu při kliknutí mimo
    document.addEventListener('click', function (e) {
        if (![...submenuToggles].some(toggle => toggle.contains(e.target))) {
            submenuItems.forEach(item => {
                item.classList.remove('open');
                const submenu = item.querySelector('.submenu');
                if (submenu) submenu.style.maxHeight = null;
            });
        }
    });

    // Reset při změně velikosti okna
    window.addEventListener('resize', () => {
        submenuItems.forEach(item => {
            item.classList.remove('open');
            const submenu = item.querySelector('.submenu');
            if (submenu) submenu.style.maxHeight = null;
        });

        if (!isMobile()) {
            navMenu.classList.remove('active');
        }
    });
});