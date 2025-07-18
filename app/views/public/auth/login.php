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

    <link rel="stylesheet" href="<?= ASSETS ?>css/public/auth/login.css" type="text/css"/>

    <title><?= $data['title'] ?></title>
</head>

<body>
    <?php $this->view("public/header") ?>

    <main>
        <section class="form">
            <form action="" method="POST">
                <h1>Přihlášení</h1>
                <input type="hidden" name="csrf_token" value="<?= isset($data['csrfToken']) ? $data['csrfToken'] : '' ?>">
                <input type="email" name="log_email" class="email" placeholder="Email" value="<?= htmlspecialchars(isset($_POST['log_email']) ? $_POST['log_email']:  '') ?>"><br />
                <input type="password" name="log_password" class="password" placeholder="Heslo"><br />
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

    <script src="<?= ASSETS ?>js/header.js"></script>
</body>
</html>