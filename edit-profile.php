<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection
require_once('../config.php');

// Get the user's information from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit;
}

$row = mysqli_fetch_assoc($result);

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);

    // Update the user's information in the database
    $query = "UPDATE users SET username = '$username', email = '$email', bio = '$bio' WHERE id = $user_id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Error: Could not update the user information.');
    }

    // Redirect the user to the profile page
    header('Location: profile.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Nerdy Social Media Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include('menu.php'); ?>
    </header>

    <main>
        <h1>Edit Your Profile</h1>

        <form method="POST" action="edit-profile.php">
            <label for="username">Username</label>
            <input type="text" name="username" value="<?php echo $row['username']; ?>" required>

            <label for="email">Email</label>
            <input type="email" name="email" value="<?php echo $row['email']; ?>" required>

            <label for="bio">Bio</label>
            <textarea name="bio"><?php echo $row['bio']; ?></textarea>

            <input type="submit" value="Save Changes">
        </form>
    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>
