<?php
namespace classes;

require_once 'includes/classes/Database.php';
require_once 'includes/classes/Post.php';
require_once 'includes/classes/Image.php';
require_once 'includes/settings.php';

if (!isset($_SESSION['user'])) {
    header('location: ' . BASE_PATH . '/login.php');
    exit;
}

if (isset($_POST['submit'])) {

    $errors = [];

    if (empty($_POST['title'])) {
        $errors['title'] = 'Title can not be empty';
    }

    //Put the image information in a separate variable for ease of use
    $uploadedImage = $_FILES['image'];
//        print_r($_FILES['image']);
//        exit;

    if (empty($uploadedImage)) {
        $errors['image'] = 'An image must be uploaded';
    }

    if (!empty($uploadedImage['type'])) {

        match ($uploadedImage['type']) {
            'image/png',
            'image/jpg',
            'image/jpeg',
            'image/gif' => null,
            default => ($errors[] = 'Image must be of type PNG, GIF or JPG')
        };

        if ($uploadedImage['size'] > 10000000) {
            $errors[] = 'Image file can not be larger than 10 MB';
        }
    }

    //Store the image if one was uploaded
    if (empty($errors) && !empty($uploadedImage['type'])) {

        $image = new Image();

        $image_path = $image->save($uploadedImage);

    }

    if (empty($errors) && isset($image_path)) {

        //Connect to the database
        $database = new Database();
        $db = $database->connect();

        //Store all the post data in an object
        $newPost = [
            'title' => $_POST['title'],
            'image_path' => $image_path,
            'user_id' => $_SESSION['user']['id']
        ];

        //Save the post in the database
        $postClass = new Post();
        $postClass->createNew($newPost, $db);

        //Disconnect from the database
        $database->disconnect();

        //Redirect the user to the index
        header('location: ' . BASE_PATH . '/index.php');

        //Exit the code
        exit;
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
    <title>Create post</title>
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

        <h1>Create new post</h1>

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

                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="<?= htmlentities($_POST['title'] ?? '') ?>">

            </div>

            <div>

                <label for="image">Image</label>
                <input type="file" name="image" id="image">

            </div>

            <input type="submit" name="submit" value="Create post">

        </form>

    </section>

</main>

</body>
</html>