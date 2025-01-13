<?php
namespace classes;

require_once 'includes/classes/Database.php';
require_once 'includes/classes/Post.php';
require_once 'includes/settings.php';

//Get all the posts from the database
//Connect to the database
$database = new Database();
$db = $database->connect();

//Use the Post class to fetch all the data
$postClass = new Post();
$posts = $postClass->selectAll($db);

//Reverse the order of the posts so we see the newest first
$posts = array_reverse($posts);

//Disconnect from the database
$database->disconnect();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/css/style.css">
    <title>PHP database images</title>
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

<main id="index">

    <h1>Image gallery</h1>

    <div id="imageArticleContainer">

        <?php if (!empty($posts)) { ?>

            <?php foreach ($posts as $post) { ?>

                <article class="post">

                    <div class="postInformation">

                        <h2><?= htmlentities($post['title']) ?></h2>

                        <div class="userInfo">

                            <p>Posted by <?= $post['name'] ?></p>

                            <div class="imageContainer">

                                <?php if ($post['user_image_path']) { ?>

                                    <img src="<?= UPLOAD_PATH . $post['user_image_path'] ?>"
                                         alt="User image">

                                <?php } else { ?>

                                    <img src="<?= BASE_PATH . '/includes/images/defaultUser.jpg' ?>"
                                         alt="User image">

                                <?php } ?>

                            </div>

                        </div>

                    </div>

                    <div class="imageContainer">
                        <img src="<?= UPLOAD_PATH . $post['image_path'] ?>" alt="Post image">
                    </div>

                </article>

            <?php } ?>

        <?php } ?>

    </div>

</main>

</body>
</html>
