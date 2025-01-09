<?php

require_once 'includes/settings.php';

if (isset($_POST['submit'])) {
    session_destroy();
    header('location: ' . BASE_PATH . '/index.php');
    exit;
}

if (!isset($_SESSION['user'])) {
    header('location: ' . BASE_PATH . '/login.php');
    exit;
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/css/style.css">
    <title>Logout</title>
</head>
<body>

<nav>

    <a href="<?= BASE_PATH ?>/index.php">Home</a>

    <?php if (empty($_SESSION['user'])) { ?>

        <a href="<?= BASE_PATH ?>/login.php">Login</a>

    <?php } else { ?>

        <a href="<?= BASE_PATH ?>/create-post.php">Create post</a>

        <a href="<?= BASE_PATH ?>/logout.php">Logout</a>

    <?php } ?>
</nav>
<main id="delete">

    <h1>Are you sure you wish to log out?</h1>

    <div>

        <form action="" method="post">
            <input type="submit" name="submit" value="Log out">
        </form>

    </div>

</main>

</body>
</html>