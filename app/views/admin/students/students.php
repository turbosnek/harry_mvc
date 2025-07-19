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

    <link rel="stylesheet" href="<?= ASSETS ?>css/admin/students/students.css" type="text/css"/>
    <link rel="stylesheet" href="<?= ASSETS ?>query/admin/students/students.css" type="text/css"/>

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
            <section class="main-heading">
                <h1>Seznam žáků školy</h1>
            </section>

            <section class="students-list">
                <?php if (!empty($data['errors'])): ?>
                    <div class="errors">
                        <?php foreach ($data['errors'] as $error): ?>
                            <h1><?= htmlspecialchars($error) ?></h1>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="all-students">
                        <?php foreach ($data['students'] as $one_student): ?>
                            <div class="one-student">
                                <h2><?= htmlspecialchars($one_student['first_name']) . " " . htmlspecialchars($one_student['second_name']) ?></h2>
                                <a href="/student/student/<?= $one_student['id'] ?>">Více informací</a>
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