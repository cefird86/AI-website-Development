<?php
session_start();
require_once('../config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Retrieve the list of all groups
$query = "SELECT * FROM my_groups";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nerdy Social Media Site - Groups</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include('menu.php'); ?>
    </header>

    <main>
        <h1>Groups</h1>

        <?php if (mysqli_num_rows($result) == 0): ?>
            <p>There are no groups available.</p>
        <?php else: ?>
            <ul>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <li><a href="group.php?id=<?php echo $row['id']; ?>"><?php echo $row['group_name']; ?></a></li>
            <?php endwhile; ?>
            </ul>
        <?php endif; ?>

        <a href="create-group.php" class="btn btn-primary">Create a new group</a>
    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>
