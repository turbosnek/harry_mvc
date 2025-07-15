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
    <link rel="stylesheet" href="<?= ASSETS ?>css/header.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>query/header-query.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>css/footer.css" type="text/css"/>

    <link rel="stylesheet" href="<?= ASSETS ?>css/admin/edit-student.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>query/admin/edit-student.css" type="text/css"/>

    <title><?= $data['title'] ?></title>
</head>

<body>
    <?php $this->view("admin-header") ?>

    <main>
        <?php if (!isset($_SESSION['role']) or !in_array($_SESSION['role'], ["ROLE_SUPER_ADMIN", "ROLE_ADMIN"])): ?>
            <section class="security-error">
                <h1>Nemáte dostatečná oprávnění k&nbsp;přístupu na tuto stránku.</h1>
            </section>
        <?php else: ?>
            <section class="add-form">
                    <form action="" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= isset($data['csrfToken']) ? $data['csrfToken'] : '' ?>">
                        <input type="text" name="first_name" placeholder="Křestní jméno" value="<?= htmlspecialchars(isset($data['student']['first_name']) ? $data['student']['first_name']:  '') ?>">
                        <input type="text" name="second_name" placeholder="Příjmení" value="<?= htmlspecialchars(isset($data['student']['second_name']) ? $data['student']['second_name']:  '') ?>">
                        <input type="number" name="age" placeholder="Věk" value="<?= htmlspecialchars(isset($data['student']['age']) ? $data['student']['age']:  '') ?>">
                        <input type="text" name="college" placeholder="Kolej" value="<?= htmlspecialchars(isset($data['student']['college']) ? $data['student']['college']:  '') ?>">
                        <textarea name="life" placeholder="Informace o žákovi"><?= htmlspecialchars(isset($data['student']['life']) ? $data['student']['life']:  '') ?></textarea>
                        <?php if (!empty($data['errors'])): ?>
                            <ul>
                                <?php foreach ($data['errors'] as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <input type="submit" value="Uložit">
                    </form>
            </section>
        <?php endif; ?>
    </main>

    <?php $this->view("footer") ?>

    <script src="<?= ASSETS ?>js/header.js"></script>
</body>
</html>