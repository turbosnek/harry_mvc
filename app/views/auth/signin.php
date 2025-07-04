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

    <link rel="stylesheet" href="<?= ASSETS ?>css/public/signin.css" type="text/css"/>

    <title><?= $data['title'] ?></title>
</head>

<body>
    <?php $this->view("header") ?>

    <main class="min-vh-100 d-flex flex-row justify-content-center align-items-center">
        <section class="form">
            <form action="" method="POST">
                <h1>Přihlášení</h1>
                <input type="email" name="log-email" class="email" placeholder="Email" value="<?= htmlspecialchars(isset($_POST['log-email']) ? $_POST['log-email']:  '') ?>" required><br />
                <input type="password" name="log-password" class="password" placeholder="Heslo" required><br />
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