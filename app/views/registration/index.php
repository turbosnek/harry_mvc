<?php
if (isset($_SESSION["user_id"])) {
    header("Location: admin/index");
    exit();
}

?>

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

    <title><?= $data['title'] ?></title>
</head>

<body>
    <?php $this->view("header") ?>

    <main class="min-vh-100 d-flex flex-row justify-content-center align-items-center">
        <section class="main-heading">

        </section>
    </main>

    <?php $this->view("footer") ?>

    <script src="<?= ASSETS ?>js/bootstrap.js"></script>
</body>
</html>