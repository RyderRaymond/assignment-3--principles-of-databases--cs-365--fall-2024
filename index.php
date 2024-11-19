<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwords Database</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Passwords Database</h1>

<?php
require_once "includes/config.php";
require_once "includes/helpers.php";

$option = (isset($_POST['submitted']) ? $_POST['submitted'] : null);

if ($option != null) {
    switch ($option) {
        case 1:
            search($_POST['search-term'], $_POST['table-to-search']);
            break;
        case 2:
            update($_POST['update-attribute'], $_POST['new-value'], $_POST['query-attribute'], $_POST['pattern']);
            break;
        case 3:
            insert_user($_POST['user-first-name'], $_POST['user-last-name']);
            break;
        case 4:
            insert_website($_POST['website-name'], $_POST['website-url']);
            break;
        case 5:
            insert_account($_POST['website-id'], $_POST['user-id'], $_POST['username'], $_POST['password'], $_POST['email-address'], $_POST['comment']);
            break;
        case 6:
            delete($_POST['delete-from'], $_POST['delete-query-attribute'], $_POST['delete-pattern']);
            break;
    }
}
?>

    <form id="clear-results" method="post"
            action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input id="clear-form-button" type="submit" value="Clear all Fields">
    </form>
    <form id="search" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
        <legend>Search for this Term</legend>
        <p>
            <label for="table-to-search">Select the Table to Search in:</label>
            <select name="table-to-search" id="table-to-search">
                <option>Users</option>
                <option>Websites</option>
                <option>Accounts</option>
                <option>Full Entries</option>
            </select>
        </p>
        <p>
            <label for="search-term">Search Term:</label>
            <input type="text" id="search-term" name="search-term">
        </p>
        <input type="hidden" name="submitted" value="1">
        <p><input id="search-button" type="submit" value="Search" /></p>
        </fieldset>
    </form>
    <form id="update" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
        <legend>Update a User, Website, or Account</legend>
        <p>
            <label for="update-attribute">Attribute to Update:</label>
            <select name="update-attribute" id="update-attribute">
                <option>first_name</option>
                <option>last_name</option>
                <option>user_id</option>
                <option>site_name</option>
                <option>url</option>
                <option>site_id</option>
                <option>username</option>
                <option>password</option>
                <option>email_address</option>
                <option>comment</option>
                <option>time_stamp</option>
            </select>
            <label for="new-value">New Value for this Attribute:</label>
            <input type="text" id="new-value" name="new-value">
        </p>
        <p>
            <label for="query-attribute">Attribute to Match off of:</label>
            <select name="query-attribute" id="query-attribute">
                <option>first_name</option>
                <option>last_name</option>
                <option>user_id</option>
                <option>site_name</option>
                <option>url</option>
                <option>site_id</option>
                <option>username</option>
                <option>password</option>
                <option>email_address</option>
                <option>comment</option>
                <option>time_stamp</option>
            </select>
            <label for="pattern">Value to match off of:</label>
            <input type="text" id="pattern" name="pattern">
        </p>
        <p><input type="hidden" name="submitted" value="2"></p>
        <p><input id="update-button" type="submit" value="Update" /></p>
        </fieldset>
    </form>
    <form id="insert-user" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
        <legend>Add a New User</legend>
        <p>
            <label for="user-first-name">User's First Name:</label>
            <input type="text" name="user-first-name" placeholder="Jim" required>
        </p>
        <p>
            <label for="user-last-name">User's Last Name:</label>
            <input type="text" name="user-last-name" placeholder="Bob" required>
        </p>
        <p><input type="hidden" name="submitted" value="3"></p>
        <p><input id="insert-user-button" type="submit" value="Add User" /></p>
        </fieldset>
    </form>
    <form id="insert-website" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
        <legend>Add a New Website</legend>
        <p>
            <label for="website-name">Website Name:</label>
            <input type="text" name="website-name" placeholder="Example Website" required>
        </p>
        <p>
            <label for="website-url">Website URL:</label>
            <input type="url" name="website-url" placeholder="https://example.com" required>
        </p>
        <p><input type="hidden" name="submitted" value="4"></p>
        <p><input id="insert-website-button" type="submit" value="Add Website" /></p>
        </fieldset>
    </form>
    <form id="insert-account" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
        <legend>Register a New Account</legend>
        <p>
            <label for="website-id">Website ID:</label>
            <input type="text" name="website-id" placeholder="Enter a Number" required>
        </p>
        <p>
            <label for="user-id">User ID:</label>
            <input type="text" name="user-id" placeholder="Enter a Number" required>
        </p>
        <p>
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="username" required>
        </p>
        <p>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
        </p>
        <p>
            <label for="email-address">Email:</label>
            <input type="email" name="email-address" placeholder="username@example.com" required>
        </p>
        <p>
            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" rows="5" cols="35"></textarea>
        </p>
        <p><input type="hidden" name="submitted" value="5"></p>
        <p><input id="insert-website-button" type="submit" value="Register" /></p>
        </fieldset>
    </form>
    <form id="delete" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
        <legend>Delete a User, Website, or Account</legend>
        <p>
            <label for="delete-from">Table to Delete from:</label>
            <select name="delete-from" id="delete-from">
                <option>users</option>
                <option>websites</option>
                <option>accounts</option>
            </select>
        </p>
        <p>
            <label for="delte-query-attribute">Query to Match off of:</label>
            <select name="delete-query-attribute" id="delete-query-attribute">
                <option>first_name</option>
                <option>last_name</option>
                <option>user_id</option>
                <option>site_name</option>
                <option>url</option>
                <option>site_id</option>
                <option>username</option>
                <option>password</option>
                <option>email_address</option>
                <option>comment</option>
                <option>time_stamp</option>
            </select>
            <label for="delete-pattern">Value to match off of:</label>
            <input type="text" id="delete-pattern" name="delete-pattern">
        </p>
        <p><input type="hidden" name="submitted" value="6"></p>
        <p><input id="delete-button" type="submit" value="Delete" /></p>
        </fieldset>
    </form>
</body>
</html>
