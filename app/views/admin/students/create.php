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
    <link rel="stylesheet" href="<?= ASSETS ?>query/general-query.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>css/header.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>query/header-query.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>css/footer.css" type="text/css"/>

    <link rel="stylesheet" href="<?= ASSETS ?>css/admin/create-student.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>query/admin/create-student.css" type="text/css"/>

    <title><?= $data['title'] ?></title>
</head>

<body>
    <?php $this->view("admin-header") ?>

    <main>
        <?php if (!isset($_SESSION['role']) or !in_array($_SESSION['role'], ["ROLE_SUPER_ADMIN", "ROLE_ADMIN"])): ?>
            <section class="security-error">
                <h1>Nemáte dostatečná oprávnění k&nbsp;přístupu na tuto&nbsp;stránku.</h1>
            </section>
            <?php else: ?>
                <section class="add-form">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= isset($data['csrfToken']) ? $data['csrfToken'] : '' ?>">
                        <input type="text" name="first_name" placeholder="Křestní jméno" value="<?= htmlspecialchars(isset($_POST['first_name']) ? $_POST['first_name']:  '') ?>">
                        <input type="text" name="second_name" placeholder="Příjmení" value="<?= htmlspecialchars(isset($_POST['second_name']) ? $_POST['second_name']:  '') ?>">
                        <input type="number" name="age" placeholder="Věk" value="<?= htmlspecialchars(isset($_POST['age']) ? $_POST['age']:  '') ?>">
                        <input type="text" name="college" placeholder="Kolej" value="<?= htmlspecialchars(isset($_POST['college']) ? $_POST['college']:  '') ?>">
                        <textarea name="life" placeholder="Informace o žákovi"><?= htmlspecialchars(isset($_POST['life']) ? $_POST['life']:  '') ?></textarea>
                        <label for="profile_image">Profilová fotka (volitelná, .jpg/.jpeg/.png/.gif):</label>
                        <input type="file" name="profile_image" id="profile_image" accept=".jpg,.jpeg,.png,.gif">
                        <?php if (!empty($data['errors'])): ?>
                            <ul>
                                <?php foreach ($data['errors'] as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <input type="submit" value="Přidat žáka">
                    </form>
                </section>
            <?php endif; ?>
    </main>

    <?php $this->view("footer") ?>

    <script src="<?= ASSETS ?>js/header.js"></script>
</body>
</html>