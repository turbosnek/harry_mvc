<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <!-- Logo start -->
            <div class="logo">
                <img src="<?= ASSETS ?>images/background/hogwarts-logo.png" alt="Škola čar a kouzel v Bradavicích logo">
            </div>
            <!-- Logo end -->

            <!-- Responsive menu Icon start -->
            <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Responsive menu Icon end -->

            <!-- Sidebar menu start -->
            <div class="sidebar offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <!-- Sidebar Header start -->
                <div class="offcanvas-header text-white border-bottom">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <!-- Sidebar Header end -->

                <!-- Sidebar Body start -->
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item mx-2">
                            <a class="nav-link" href="home">Hlavní stránka</a>
                        </li>
                        <?php if(isset($_SESSION['id'])): ?>
                            <?php if ($_SESSION['role'] === 'ROLE_SUPER_ADMIN' or $_SESSION['role'] === 'ROLE_ADMIN'): ?>
                                <li class="nav-item mx-2">
                                    <a class="nav-link" href="admin/">Administrace</a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item mx-2">
                                <a class="nav-link" href="auth/logout">Odhlásit</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item mx-2">
                                <a class="nav-link" href="/auth/register">Registrace</a>
                            </li>
                            <li class="nav-item mx-2">
                                <a class="nav-link" href="auth">Přihlášení</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <!-- Sidebar Body end -->
            </div>
            <!-- Sidebar menu end -->
        </div>
    </nav>
</header>