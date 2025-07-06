<!DOCTYPE html>
<html lang="cs-cz">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content=""/> <!-- Popis stránky -->
    <meta name="keywords" content=""/> <!-- Klíčová slova -->
    <meta name="author" content=""/> <!-- Autor webu -->
    <link rel="shortcut icon" href="images/ikona.ico"/>
    <link rel="stylesheet" href="<?= ASSETS ?>css/bootstrap.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>css/font-awesome.min.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>css/general.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>css/public/public-header.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>query/public/public-header-query.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>css/public/footer.css" type="text/css"/>

    <link rel="stylesheet" href="<?= ASSETS ?>css/admin/index.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>query/admin/index-query.css" type="text/css"/>

    <title><?= $data['title'] ?></title>
</head>

<body>
    <?php $this->view("admin-header") ?>

    <main class="min-vh-100 d-flex flex-column justify-content-center align-items-center">
        <section class="main-heading">
            <?php if (!isset($_SESSION['role']) or !in_array($_SESSION['role'], ["ROLE_SUPER_ADMIN", "ROLE_ADMIN"])): ?>
                <h1>Nemáte dostatečná oprávnění k&nbsp;přístupu na tuto stránku.</h1>
            <?php else: ?>
                <h1>Vítejte v&nbsp;administraci</h1>
                <h2>Jsi přihlášen jako: <?= $_SESSION['first_name'] . "&nbsp;" . $_SESSION['second_name'] ?></h2>
                <h2>Tvoje uživatelská role je: <?= str_replace(["ROLE_", "_"], " ", $_SESSION['role']) ?></h2>
            <?php endif; ?>
        </section>
    </main>

    <?php $this->view("footer") ?>

    <script src="<?= ASSETS ?>js/bootstrap.js"></script>
</body>
</html>