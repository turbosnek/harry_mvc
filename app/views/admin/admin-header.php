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
                    <?php if (isset($_SESSION['id'])): ?>
                        <?php if ($_SESSION['role'] === 'ROLE_SUPER_ADMIN' || $_SESSION['role'] === 'ROLE_ADMIN'): ?>
                            <li><a href="/admin/">Hlavní stránka administrace</a></li>
                            <li class="has-submenu">
                                <a href="#" class="submenu-toggle">Žáci</a>
                                <ul class="submenu">
                                    <li><a href="<?= ROOT ?>/admin/students/students">Seznam žáků</a></li>
                                    <li><a href="<?= ROOT ?>/admin/students/create">Přidat žáka</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <li><a href="#">Můj profil</a></li>
                        <li><a href="/auth/logout">Odhlásit</a></li>
                    <?php else: ?>
                        <li><a href="/">Hlavní stránka</a></li>
                        <li><a href="<?= ROOT ?>/register">Registrace</a></li>
                        <li><a href="<?= ROOT ?>/login">Přihlášení</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>