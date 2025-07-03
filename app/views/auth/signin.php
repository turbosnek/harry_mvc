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
    <section class="form">
        <form action="" method="POST">
<!--            <input type="text" name="first_name" class="reg-input" placeholder="Křestní jméno" value="--><?php //= htmlspecialchars(isset($_POST['first_name']) ? $_POST['first_name']:  '') ?><!--" required><br />-->
<!--            <input type="text" name="second_name" class="reg-input" placeholder="Příjmení" value="--><?php //= htmlspecialchars(isset($_POST['second_name']) ? $_POST['second_name']:  '') ?><!--" required><br />-->
<!--            <input type="email" name="email" class="reg-input" placeholder="Email" value="--><?php //= htmlspecialchars(isset($_POST['email']) ? $_POST['email']:  '') ?><!--" required><br />-->
<!--            <input type="text" name="anti-spam" class="reg-input" placeholder="Aktuální rok (Anti Spam)" required><br />-->
<!--            <input type="password" name="password" class="reg-input" placeholder="Heslo" required><br />-->
<!--            <input type="password" name="password-again" class="reg-input" placeholder="Heslo znovu" required><br />-->
            <h1>Přihlášení</h1>
            <input type="email" name="log-email" class="email" placeholder="Email" required><br />
            <input type="password" name="log-password" class="email" placeholder="Heslo" required><br />
            <?php if (!empty($data['errors'])): ?>
                <ul>
                    <?php foreach ($data['errors'] as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <input type="submit" class="btn" value="Přihlásit">
        </form>
    </section>
</main>

<?php $this->view("footer") ?>

<script src="<?= ASSETS ?>js/bootstrap.js"></script>
</body>
</html>