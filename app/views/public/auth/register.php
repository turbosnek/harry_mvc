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

    <link rel="stylesheet" href="<?= ASSETS ?>css/public/auth/register.css" type="text/css"/>

    <title><?= $data['title'] ?></title>
</head>

<body>
    <?php $this->view("public/header") ?>

    <main>
        <section class="registration-form">
            <form action="" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $data['csrfToken'] ?? '' ?>">
                <input type="text" name="first_name" class="reg-input" placeholder="Křestní jméno" value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"><br />
                <input type="text" name="second_name" class="reg-input" placeholder="Příjmení" value="<?= htmlspecialchars($_POST['second_name'] ?? '') ?>"><br />
                <input type="email" name="email" class="reg-input" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"><br />
                <input type="text" name="anti_spam" class="reg-input" placeholder="Aktuální rok (Anti Spam)"><br />
                <input type="password" name="password" class="reg-input" placeholder="Heslo"><br />
                <input type="password" name="password_again" class="reg-input" placeholder="Heslo znovu"><br />
                <?php if (!empty($data['errors'])): ?>
                    <ul>
                        <?php foreach ($data['errors'] as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <input type="submit" class="btn" value="Zaregistrovat">
            </form>
        </section>
    </main>

    <?php $this->view("footer") ?>

    <script src="<?= ASSETS ?>js/header.js"></script>
</body>
</html>