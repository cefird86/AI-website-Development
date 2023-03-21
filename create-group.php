<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection
require_once('../config.php');

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the form data
    $group_name = $_POST['group_name'];
    $group_description = $_POST['group_description'];

    // Insert the group into the database
    $query = "INSERT INTO my_groups (group_name, group_description) VALUES ('$group_name', '$group_description')";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Error: Could not create group.');
    }

    // Redirect the user to the groups page
    header('Location: groups.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Group</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include('menu.php'); ?>
    </header>

    <main>
        <h1>Create a Group</h1>

        <form method="POST" action="create-group.php">
            <label for="group_name">Group Name</label>
            <input type="text" name="group_name" required>

            <label for="group_description">Group Description</label>
            <textarea name="group_description" required></textarea>

            <input type="submit" value="Create Group">
        </form>
    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>
