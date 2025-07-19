<header>
    <nav class="custom-navbar">
        <div class="nav-container">
            <div class="logo">
                <img src="<?= ASSETS ?>images/layout/hogwarts-logo.png" alt="Logo">
            </div>

            <!-- Hamburger menu -->
            <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">&#9776;</button>

            <!-- Navigační odkazy -->
            <div class="nav-menu" id="navMenu">
                <div class="nav-header mobile-only">
                    <span class="menu-title">Menu</span>
                    <button class="close-btn" id="closeMenu" aria-label="Close menu">&times;</button>
                </div>
                <ul class="nav-links">
                    <li><a href="/">Hlavní stránka</a></li>
                    <?php if(isset($_SESSION['id'])): ?>
                        <?php if ($_SESSION['role'] === 'ROLE_SUPER_ADMIN' or $_SESSION['role'] === 'ROLE_ADMIN'): ?>
                            <li><a href="<?= ROOT ?>/admin">Administrace</a></li>
                        <?php endif; ?>
                        <li><a href="#">Můj profil</a></li>
                        <li><a href="<?= ROOT ?>/logout">Odhlásit</a></li>
                    <?php else: ?>
                        <li><a href="<?= ROOT ?>/register">Registrace</a></li>
                        <li><a href="<?= ROOT ?>/login">Přihlášení</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>