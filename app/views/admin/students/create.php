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
    <link rel="stylesheet" href="<?= ASSETS ?>query/admin/general.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>css/admin/admin-header.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>query/admin/admin-header.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>css/footer.css" type="text/css"/>

    <link rel="stylesheet" href="<?= ASSETS ?>css/admin/students/create.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>query/admin/students/create.css" type="text/css"/>

    <title><?= $data['title'] ?></title>
</head>

<body>
    <?php $this->view("admin/admin-header") ?>

    <main>
        <?php if (!isset($_SESSION['role']) or !in_array($_SESSION['role'], ["ROLE_SUPER_ADMIN", "ROLE_ADMIN"])): ?>
            <section class="security-error">
                <h1>Nemáte dostatečná oprávnění k&nbsp;přístupu na tuto&nbsp;stránku.</h1>
            </section>
        <?php else: ?>
            <section class="add-form">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $data['csrfToken'] ?? '' ?>">
                    <input type="text" name="first_name" placeholder="Křestní jméno" value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
                    <input type="text" name="second_name" placeholder="Příjmení" value="<?= htmlspecialchars($_POST['second_name'] ?? '') ?>">
                    <input type="number" name="age" placeholder="Věk" value="<?= htmlspecialchars($_POST['age'] ?? '') ?>">
                    <input type="text" name="college" placeholder="Kolej" value="<?= htmlspecialchars($_POST['college'] ?? '') ?>">
                    <textarea name="life" placeholder="Informace o žákovi"><?= htmlspecialchars($_POST['life'] ?? '') ?></textarea>
                    <label class="label-text">Profilová fotka (volitelná, .jpg/.jpeg/.png/.gif):</label>
                    <label for="profile_image" id="choose-file-text">Vybrat Fotku</label>
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