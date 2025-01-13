<?php
namespace classes;

require_once 'includes/classes/Database.php';
require_once 'includes/classes/Post.php';
require_once 'includes/settings.php';

if (isset($_POST['submit'])) {

    $amount = intval($_POST['amount']);

    $database = new Database();
    $db = $database->connect();

    $post = new Post();

    $post->plink($db, $amount);

    $database->disconnect();

    //Redirect to home page
    header('location: ' . BASE_PATH . '/index.php');

    //Exit code
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
    <title>Plink</title>
</head>
<body>

<main>

    <section id="plink">

        <form action="" method="post">

            <h1>Plink</h1>

            <div>
                <label for="amount">Amount of plinks</label>
                <input type="number" name="amount" id="amount" min="1" max="50" step="1" value="1">
            </div>

            <input type="submit" id="submit" name="submit" value="plink">

        </form>

    </section>

</main>

</body>
</html>
