<?php

session_start();
require_once('../config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if the group ID is specified in the URL
if (!isset($_GET['id'])) {
    header('Location: groups.php');
    exit;
}

$group_id = $_GET['id'];

// Retrieve the group information from the database
$query = "SELECT * FROM my_groups WHERE id = $group_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: groups.php');
    exit;
}

$row = mysqli_fetch_assoc($result);
$group_name = $row['group_name'];
$group_description = $row['group_description'];

// Check if the user is already a member of the group
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM group_members WHERE group_id = $group_id AND user_id = $user_id";
$result = mysqli_query($conn, $query);

$is_member = false;

if ($result && mysqli_num_rows($result) > 0) {
    $is_member = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $group_name; ?> - Nerdy Social Media Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include('menu.php'); ?>
    </header>

    <main>
        <h1><?php echo $group_name; ?></h1>

        <p><?php echo $group_description; ?></p>

        <?php if ($is_member): ?>
            <form action="leave-group.php" method="POST">
            <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
            <input type="submit" value="Leave group">
        <?php else: ?>
        <form method="post" action="join-group.php">
            <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
            <button type="submit" name="join-group">Join Group</button>
        </form>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>
