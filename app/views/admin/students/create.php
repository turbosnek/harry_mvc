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
    <?php $this->view("admin-header") ?>

    <main class="min-vh-100 d-flex flex-column justify-content-center align-items-center">
        <section class="add-form">
            <?php if (!isset($_SESSION['role']) or !in_array($_SESSION['role'], ["ROLE_SUPER_ADMIN", "ROLE_ADMIN"])): ?>
                <h1>Nemáte dostatečná oprávnění k&nbsp;přístupu na tuto stránku.</h1>
            <?php else: ?>
                <form action="" method="POST">
                    <input type="text" name="first_name" placeholder="Křestní jméno" value="<?= htmlspecialchars(isset($_POST['first_name']) ? $_POST['first_name']:  '') ?>" required>
                    <input type="text" name="second_name" placeholder="Příjmení" value="<?= htmlspecialchars(isset($_POST['second_name']) ? $_POST['second_name']:  '') ?>" required>
                    <input type="number" name="age" placeholder="Věk" value="<?= htmlspecialchars(isset($_POST['age']) ? $_POST['age']:  '') ?>" min="10" required>
                    <input type="text" name="college" placeholder="Kolej" value="<?= htmlspecialchars(isset($_POST['college']) ? $_POST['college']:  '') ?>" required>
                    <textarea name="life" placeholder="Informace o žákovi" required><?= htmlspecialchars(isset($_POST['college']) ? $_POST['college']:  '') ?></textarea>
                    <?php if (!empty($data['errors'])): ?>
                        <ul>
                            <?php foreach ($data['errors'] as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <input type="submit" class="btn" value="Přidat žáka">
                </form>
            <?php endif; ?>
        </section>
    </main>

    <?php $this->view("footer") ?>

    <script src="<?= ASSETS ?>js/bootstrap.js"></script>
</body>
</html>