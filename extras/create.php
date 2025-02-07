<?php
/** @var mysqli $db */
//Check if Post isset, else do nothing
if (isset($_POST['submit'])) {

//Require database in this file & image helpers
    require_once "includes/database.php";
    require_once "includes/image-helpers.php";

//Postback with the data showed to the user, first retrieve data from 'Super global'
    $name = mysqli_escape_string($db, $_POST['name']);
    $artist = mysqli_escape_string($db, $_POST['artist']);
    $genre = mysqli_escape_string($db, $_POST['genre']);
    $year = mysqli_escape_string($db, $_POST['year']);
    $tracks = mysqli_escape_string($db, $_POST['tracks']);

//Require the form validation handling
    require_once "includes/form-validation.php";

    //Special check for add form only
    if ($_FILES['image']['error'] == 4) {
        $errors['image'] = 'Image cannot be empty';
    }

    if (empty($errors)) {
        //Store image & retrieve name for database saving
        $image = addImageFile($_FILES['image']);

        //Save the record to the database
        $query = "INSERT INTO albums (name, artist, genre, year, tracks, image)                  
        VALUES ('$name', '$artist', '$genre', $year, $tracks, '$image')";

        $result = mysqli_query($db, $query) or die('Error: ' . mysqli_error($db) . ' with query ' . $query);

        if ($result) {
            header('Location: index.php');
            exit;
        } else {
            $errors['db'] = 'Something went wrong in your database query: ' . mysqli_error($db);
        }

        //Close connection
        mysqli_close($db);
    }

}

?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Music Collection Create</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>
<body>

<h1>Create album</h1>

<?php if (isset($errors['db'])) { ?>
    <div>
        <span class="errors"><?= $errors['db']; ?></span>
    </div>
<?php } ?>

<!-- enctype="multipart/form-data" no characters will be converted -->
<form action="" method="post" enctype="multipart/form-data">
    <div class="data-field">
        <label for="artist">Artist</label>
        <input id="artist" type="text" name="artist" value="<?= isset($artist) ? htmlentities($artist) : '' ?>"/>
        <span class="errors"><?= $errors['artist'] ?? '' ?></span>
    </div>

    <div class="data-field">
        <label for="name">Album</label>
        <input id="name" type="text" name="name" value="<?= isset($name) ? htmlentities($name) : '' ?>"/>
        <span class="errors"><?= $errors['name'] ?? '' ?></span>
    </div>

    <div class="data-field">
        <label for="genre">Genre</label>
        <input id="genre" type="text" name="genre" value="<?= isset($genre) ? htmlentities($genre) : '' ?>"/>
        <span class="errors"><?= $errors['genre'] ?? '' ?></span>
    </div>

    <div class="data-field">
        <label for="year">Year</label>
        <input id="year" type="text" name="year" value="<?= isset($year) ? htmlentities($year) : '' ?>"/>
        <span class="errors"><?= $errors['year'] ?? '' ?></span>
    </div>

    <div class="data-field">
        <label for="tracks">Tracks</label>
        <input id="tracks" type="number" name="tracks" value="<?= isset($tracks) ? htmlentities($tracks) : '' ?>"/>
        <span class="errors"><?= $errors['tracks'] ?? '' ?></span>
    </div>

    <div class="data-field">
        <label for="image">Image</label>
        <input type="file" name="image" id="image"/>
        <span class="errors"><?= $errors['image'] ?? '' ?></span>
    </div>

    <div class="data-submit">
        <input type="submit" name="submit" value="Save"/>
    </div>

</form>

<div>
    <a href="index.php">Go back to the list</a>
</div>

</body>
</html>
