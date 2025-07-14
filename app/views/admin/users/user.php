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

    <link rel="stylesheet" href="<?= ASSETS ?>css/admin/admin-one-user.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>query/admin/one-user-query.css" type="text/css"/>

    <title><?= $data['title'] ?></title>
</head>

<body>
    <?php $this->view("admin-header") ?>

    <main class="min-vh-100">
        <?php if (!isset($_SESSION['role']) or !in_array($_SESSION['role'], ["ROLE_SUPER_ADMIN", "ROLE_ADMIN"])): ?>
            <section class="security-error">
                <h1>Nemáte dostatečná oprávnění k&nbsp;přístupu na&nbsp;tuto&nbsp;stránku.</h1>
            </section>
        <?php else: ?>
            <section class="one-user">
                <?php if (!empty($data['errors'])): ?>
                    <?php foreach ($data['errors'] as $error): ?>
                        <h1><?= htmlspecialchars($error) ?></h1>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="one-user-box">
                        <h2><?= htmlspecialchars($data['user']['first_name'] . " " . htmlspecialchars($data['user']['second_name'])) ?></h2>
                        <p>Email: <?= htmlspecialchars($data['user']['email']) ?></p>
                        <?php if ($_SESSION['role'] === "ROLE_SUPER_ADMIN"): ?>
                            <p>Kolej: <?= htmlspecialchars($data['user']['role']) ?></p>
                        <?PHP endif; ?>
                    </div>
                    <div class="one-user-buttons">
                        <a class="edit-one-user" href="/user/edit/<?= $data['student']['id'] ?>">Editovat</a>
                        <a class="edit-one-user" href="/user/password/<?= $data['student']['id'] ?>">Změnit heslo</a>
                        <?php if ($_SESSION['role'] === "ROLE_SUPER_ADMIN"): ?>
                            <a class="delete-one-user" href="/user/delete/<?= $data['user']['id'] ?>">Smazat</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>

    <?php $this->view("footer") ?>

    <script src="<?= ASSETS ?>js/bootstrap.js"></script>
</body>
</html>