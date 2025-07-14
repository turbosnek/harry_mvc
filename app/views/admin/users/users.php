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

    <link rel="stylesheet" href="<?= ASSETS ?>css/admin/admin-users.css" type="text/css"/>

    <title><?= $data['title'] ?></title>
</head>

<body>
    <?php $this->view("admin-header") ?>

    <main class="min-vh-100">
        <?php if (!isset($_SESSION['role']) or !in_array($_SESSION['role'], ["ROLE_SUPER_ADMIN", "ROLE_ADMIN"])): ?>
            <section class="security-error">
                <h1>Nemáte dostatečná oprávnění k&nbsp;přístupu na tuto&nbsp;stránku.</h1>
            </section>
        <?php else: ?>
            <section class="main-heading">
                <h1>Seznam uživatelů</h1>
            </section>

            <section class="filter">
                <input class="filter-input" type="text" placeholder="Filtrovat uživatele">
            </section>

            <section class="users-list">
                <?php if (!empty($data['errors'])): ?>
                    <?php foreach ($data['errors'] as $error): ?>
                        <h1><?= htmlspecialchars($error) ?></h1>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="all-users">
                        <?php foreach ($data['users'] as $one_user): ?>
                            <div class="one-user">
                                <h2><?= htmlspecialchars($one_user['first_name']) . " " . htmlspecialchars($one_user['second_name']) ?></h2>
                                <a href="/user/user/<?= $one_user['id'] ?>">Více informací</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>

    <?php $this->view("footer") ?>

    <script src="<?= ASSETS ?>js/bootstrap.js"></script>
    <script src="<?= ASSETS ?>js/filterStudents.js"></script>
</body>
</html>