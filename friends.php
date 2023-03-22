<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection
require_once('../config.php');

// Get all users except the logged-in user
$query = "SELECT * FROM users WHERE id != {$_SESSION['user_id']}";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends - Nerdy Social Media Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include('menu.php'); ?>
    </header>

    <main>
        <h1>Friends</h1>

        <ul>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <li>
                    <a href="profile.php?id=<?php echo $row['id']; ?>"><?php echo $row['username']; ?></a>
                </li>
            <?php } ?>
        </ul>
    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>
