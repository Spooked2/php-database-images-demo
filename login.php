<?php
namespace classes;

require_once 'includes/classes/Database.php';
require_once 'includes/classes/User.php';
require_once 'includes/settings.php';

//Check if already logged in
if (isset($_SESSION['user'])) {

    //Redirect to home page
    header('location: ' . BASE_PATH . '/index.php');

    //Exit code
    exit;
}

//Check if form has been submitted
if (isset($_POST['submit'])) {

    //Check if form fields are empty
    if (empty($_POST['email'])) {
        //If they are: add error to $errors
        $errors[] = 'Email can not be empty';
    }
    if (empty($_POST['password'])) {
        $errors[] = 'Password can not be empty';
    }

    //Check if there are any errors before proceeding
    if (empty($errors)) {
        //Connect to database
        $database = new Database();
        $db = $database->connect();

        //Check if email exists
        $userClass = new User();

        if (empty($userClass->selectByEmail($db, $_POST['email']))) {
            //If it doesn't: add error to $errors
            $errors[] = 'Something went wrong, please try again';
        } else {

            //If it does: store selected user
            $user = $userClass->selectByEmail($db, $_POST['email']);

            //Check if password matches
            if (password_verify($_POST['password'], $user['password'])) {

                //If it does: add data to $_SESSION
                $sessionUser = [
                    'email' => $user['email'],
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'image_path' => $user['image_path'],
                ];

                $_SESSION['user'] = $sessionUser;

                //Disconnect from database
                $database->disconnect();

                //Redirect to home page
                header('location: ' . BASE_PATH . '/index.php');

                //Exit code
                exit;

            } else {
                //If it doesn't: add error to $errors
                $errors[] = 'Something went wrong, please try again';

                //Delete $user variable
                unset($user);

            }

        }

        //Disconnect from database
        $database->disconnect();

    }

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
    <title>Login</title>
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

<main>

    <section>

        <h1>Log in</h1>

        <!--    Display errors if there are any-->
        <?php if (isset($errors)) { ?>

            <ul>

                <?php foreach ($errors as $error) { ?>

                    <li> <?= $error ?> </li>

                <?php } ?>

            </ul>

        <?php } ?>

        <form action="" method="post">

            <div>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" maxlength="255"
                       value="<?= htmlentities($_POST['email'] ?? '') ?>">

            </div>

            <div>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" maxlength="255">

            </div>

            <input type="submit" name="submit" value="Log in">

        </form>

    </section>

    <section id="loginRegisterLink">

        <h2>No account yet?</h2>

        <a href="<?= BASE_PATH ?>/register.php">Register new account</a>

    </section>

</main>

</body>
</html>