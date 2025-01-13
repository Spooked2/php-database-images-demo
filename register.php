<?php
namespace classes;

require_once 'includes/classes/Database.php';
require_once 'includes/classes/User.php';
require_once 'includes/classes/Image.php';
require_once 'includes/settings.php';

//if the submit button has been clicked in the form
if (isset($_POST['submit'])) {

    $errors = [];

    //Connect to database
    $database = new Database();
    $db = $database->connect();

    $userClass = new User();

    //Check if any form field were left empty
    if (empty($_POST['name'])) {
        $errors['name'] = 'Name can not be empty';
    }
    if (empty($_POST['email'])) {
        $errors['email'] = 'Email can not be empty';
    } //Check if email already exists
    else if ($userClass->selectByEmail($db, $_POST['email'])) {
        $errors['email'] = 'Email already in use';
    }

    if (empty($_POST['password'])) {
        $errors['password'] = 'Password can not be empty';
    }

    if (!empty($_FILES['image']['type'])) {

        match ($_FILES['image']['type']) {
            'image/png',
            'image/jpg',
            'image/jpeg',
            'image/gif' => null,
            default => ($errors[] = 'Image must be of type PNG, GIF or JPG')
        };

        if ($_FILES['image']['size'] > 10000000) {
            $errors[] = 'Image file can not be larger than 10 MB';
        }
    }

    //Store the image if one was uploaded
    $image_path = null;
    if (empty($errors) && !empty($_FILES['image']['type'])) {

        $image = new Image();

        $image_path = $image->save($_FILES['image']);

    }


    //If the form is properly filled in
    if (empty($errors)) {

        //Store $_POST data in user array
        $user = ['name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'image_path' => $image_path
        ];

        //Insert new user into database
        //if successful
        if ($userClass->createNew($user, $db)) {

            //Close database
            $database->disconnect();

            //Redirect to login page
            header('location: ' . BASE_PATH . '/login.php');

            //Exit code
            exit;

        } //If not successful
        else {
            $errors[] = 'Something went wrong with saving data, please try again';
        }

    }

    //Disconnect from the database
    $database->disconnect();

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
    <title>Register</title>
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

        <h1>Register new account</h1>

        <!--    Display errors if there are any-->
        <?php if (isset($errors)) { ?>

            <ul>

                <?php foreach ($errors as $error) { ?>

                    <li> <?= $error ?> </li>

                <?php } ?>

            </ul>

        <?php } ?>

        <form action="" method="post" enctype="multipart/form-data">

            <div>

                <label for="name">Name</label>
                <input type="text" name="name" id="name" maxlength="64"
                       value="<?= htmlentities($_POST['name'] ?? '') ?>">

            </div>

            <div>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlentities($_POST['email'] ?? '') ?>">

            </div>

            <div>

                <label for="password">Password</label>
                <input type="password" name="password" id="password">

            </div>

            <div>

                <label for="image">Image</label>
                <input type="file" name="image" id="image">

            </div>

            <input type="submit" name="submit" value="Create account">

        </form>

    </section>

    <section id="loginRegisterLink">

        <h2>Already have an account?</h2>

        <a href="<?= BASE_PATH ?>/login.php">Log in</a>

    </section>

</main>

</body>
</html>