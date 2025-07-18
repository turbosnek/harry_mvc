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

    <link rel="stylesheet" href="<?= ASSETS ?>css/admin/users.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>query/admin/users.css" type="text/css"/>

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
            <section class="users-list">
                <?php if (!empty($data['errors'])): ?>
                    <div class="errors">
                        <?php foreach ($data['errors'] as $error): ?>
                            <h1><?= htmlspecialchars($error) ?></h1>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="all-users">
                        <?php foreach ($data['users'] as $one_user): ?>
                            <div class="one-user">
                                <h2><?= htmlspecialchars($one_user['first_name']) . " " . htmlspecialchars($one_user['second_name']) ?></h2>
                                <h4><?= htmlspecialchars($one_user['email']) ?></h4>
                                <a href="/user/user/<?= $one_user['id'] ?>">Více informací</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>

    <?php $this->view("footer") ?>

    <script src="<?= ASSETS ?>js/header.js"></script>
</body>
</html>