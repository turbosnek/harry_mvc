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

    <link rel="stylesheet" href="<?= ASSETS ?>css/admin/user.css" type="text/css"/>

    <title><?= $data['title'] ?></title>
</head>

<body>
    <?php $this->view("admin-header") ?>

    <main>
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
                    <div class="profile-image">
                        <img src="<?= !empty($data['user']['profile_image']) ? htmlspecialchars($data['user']['profile_image']) : '/assets/images/layout/hogwarts-logo.png' ?>"
                             alt="Profilový obrázek uživatele">
                    </div>
                    <div class="one-user-box">
                        <table>
                            <thead>
                                <tr>
                                    <th>Jméno</th>
                                    <th>Příjmení</th>
                                    <th>Email</th>
                                    <?php if ($_SESSION['role'] === "ROLE_SUPER_ADMIN"): ?>
                                        <th>Uživatelská role</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= htmlspecialchars($data['user']['first_name']) ?></td>
                                    <td><?= htmlspecialchars($data['user']['second_name']) ?></td>
                                    <td><?= htmlspecialchars($data['user']['email']) ?></td>
                                    <?php if ($_SESSION['role'] === "ROLE_SUPER_ADMIN"): ?>
                                        <td><?= htmlspecialchars($data['user']['role']) ?></td>
                                    <?php endif; ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
<!--                    <div class="one-user-buttons">-->
<!--                        <a class="edit-one-user" href="/user/edit/--><?php //= $data['user']['id'] ?><!--">Editovat</a>-->
<!--                        <a class="delete-one-user" href="/user/delete/--><?php //= $data['user']['id'] ?><!--">Smazat</a>-->
<!--                    </div>-->
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>

    <?php $this->view("footer") ?>

    <script src="<?= ASSETS ?>js/header.js"></script>
</body>
</html>