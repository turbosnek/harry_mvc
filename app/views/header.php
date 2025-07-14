<header>
    <nav class="custom-navbar">
        <div class="nav-container">
            <!-- Logo -->
            <div class="logo">
                <img src="<?= ASSETS ?>images/layout/hogwarts-logo.png" alt="Škola čar a kouzel v Bradavicích logo">
            </div>

            <!-- Hamburger ikonka -->
            <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
                &#9776;
            </button>

            <!-- Menu -->
            <div class="nav-menu" id="navMenu">
                <div class="nav-header">
                    <h5>Menu</h5>
                    <button class="close-btn" id="closeMenu" aria-label="Close menu">&times;</button>
                </div>
                <ul class="nav-links">
                    <li><a href="/">Hlavní stránka</a></li>
                    <?php if(isset($_SESSION['id'])): ?>
                        <?php if ($_SESSION['role'] === 'ROLE_SUPER_ADMIN' or $_SESSION['role'] === 'ROLE_ADMIN'): ?>
                            <li><a href="admin/">Administrace</a></li>
                        <?php endif; ?>
                        <li><a href="#">Můj profil</a></li>
                        <li><a href="/auth/logout">Odhlásit</a></li>
                    <?php else: ?>
                        <li><a href="/auth/register">Registrace</a></li>
                        <li><a href="/auth/login">Přihlášení</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>