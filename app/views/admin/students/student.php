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

    <link rel="stylesheet" href="<?= ASSETS ?>css/admin/students/student.css" type="text/css"/>

    <title><?= $data['title'] ?></title>
</head>

<body>
    <?php $this->view("admin/admin-header") ?>

    <main>
        <?php if (!isset($_SESSION['role']) or !in_array($_SESSION['role'], ["ROLE_SUPER_ADMIN", "ROLE_ADMIN"])): ?>
            <section class="security-error">
                <h1>Nemáte dostatečná oprávnění k&nbsp;přístupu na&nbsp;tuto&nbsp;stránku.</h1>
            </section>
        <?php else: ?>
            <section class="one-student">
                <?php if (!empty($data['errors'])): ?>
                    <?php foreach ($data['errors'] as $error): ?>
                        <h1><?= htmlspecialchars($error) ?></h1>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="profile-image">
                        <img src="<?= !empty($data['student']['profile_image']) ? htmlspecialchars($data['student']['profile_image']) : '/assets/images/layout/hogwarts-logo.png' ?>"
                             alt="Profilový obrázek studenta <?= $data['student']['first_name'] . " " . $data['student']['second_name'] ?>">
                    </div>

                    <div class="one-student-box">
                        <h2><?= htmlspecialchars($data['student']['first_name'] . " " . htmlspecialchars($data['student']['second_name'])) ?></h2>
                        <p>Věk: <?= htmlspecialchars($data['student']['age']) ?></p>
                        <p>Informace o žákovi: <?= htmlspecialchars($data['student']['life']) ?></p>
                        <p>Kolej: <?= htmlspecialchars($data['student']['college']) ?></p>
                    </div>
                    <div class="one-student-buttons">
                        <a class="edit-one-student" href="<?= ROOT ?>/admin/students/edit/<?= $data['student']['id'] ?>">Editovat</a>
                        <a class="delete-one-student" href="<?= ROOT ?>/admin/students/delete/<?= $data['student']['id'] ?>">Smazat</a>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>

    <?php $this->view("footer") ?>

    <script src="<?= ASSETS ?>js/header.js"></script>
</body>
</html>