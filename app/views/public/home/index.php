<!DOCTYPE html>
<html lang="cs-cz">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content=""/> <!-- Popis stránky -->
    <meta name="keywords" content=""/> <!-- Klíčová slova -->
    <meta name="author" content=""/> <!-- Autor webu -->
    <link rel="shortcut icon" href="images/ikona.ico"/>
    <link rel="stylesheet" href="<?= ASSETS ?>css/general.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>css/public/public-header.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>query/public/public-header.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>css/footer.css" type="text/css"/>

    <link rel="stylesheet" href="<?= ASSETS ?>css/public/home/index.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>query/public/home/index.css" type="text/css"/>

    <title><?= $data['title'] ?></title>
</head>

<body>
    <?php $this->view("public/header") ?>

    <main>
        <section class="main-heading">
            <img src="<?= ASSETS ?>images/layout/hogwarts-logo.png" alt="Škola čar a kouzel v Bradavicích logo">
            <h1>Škola čar a&nbsp;kouzel</h1>
            <h2>Bradavice</h2>
        </section>
    </main>

    <?php $this->view("footer") ?>
    <script src="<?= ASSETS ?>js/header.js"></script>
</body>
</html>