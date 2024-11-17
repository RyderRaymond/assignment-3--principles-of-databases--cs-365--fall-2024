<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwords Database</title>
</head>
<body>
    <form id="clear-fields" method="post"
            action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input id="clear-form-button" type="submit" value="Clear all Fields">
    </form>
    <form id="search" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
        <legend>Search all Entries for this Term</legend>
        <p>
            <label for="search-term">Search Term:</label>
            <input type="text" id="search-term" name="search-term">
        </p>
        <input type="hidden" name="submitted" value="1">
        <p><input id="search-button" type="submit" value="search" /></p>
        </fieldset>
    </form>
    <form id="update" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
        <legend>Update a User, Website, or Account</legend>
        <p>
            <label for="first-name">Search Term:</label>
            <input required autofocus type="text" id="search-term" name="search-term">
        </p>
        <p><input type="hidden" name="submitted" value="1"></p>
        <p><input id="search-button" type="submit" value="search" /></p>
        </fieldset>
    </form>
    <form id="insert" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
        <legend>Add a new User, Website, or Account</legend>
        <p>
            <label for="first-name">Search Term:</label>
            <input required autofocus type="text" id="search-term" name="search-term">
        </p>
        <p><input type="hidden" name="submitted" value="1"></p>
        <p><input id="search-button" type="submit" value="search" /></p>
        </fieldset>
    </form>
    <form id="delete" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
        <legend>Delete a User, Website, or Account</legend>
        <p>
            <label for="first-name">Search Term:</label>
            <input required autofocus type="text" id="search-term" name="search-term">
        </p>
        <p><input type="hidden" name="submitted" value="1"></p>
        <p><input id="search-button" type="submit" value="search" /></p>
        </fieldset>
    </form>
</body>
</html>

<?php
require_once "includes/config.php";
require_once "includes/helpers.php";

$option = (isset($_POST['submitted']) ? $_POST['submitted'] : null);

if ($option != null) {
    switch ($option) {
        case 1:
            search($_POST['search-term']);
    }
}


?>
