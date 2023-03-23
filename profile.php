<?php
// Start the session
session_start();

// Include the database connection
require_once('../config.php');

// Check if the user ID is provided in the URL, or use the logged-in user's ID
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    $user_id = $_SESSION['user_id'];
}

// Retrieve the user's data from the database
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die('Error: User not found.');
}

$user = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $user['username']; ?>'s Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include('menu.php'); ?>
    </header>

    <main>
    <h1><?php echo $user['username']; ?>'s Profile</h1>
    <?php if ($user['photo_url']) { ?>
        <img class="profile-photo" src="<?php echo $user['photo_url']; ?>" alt="Profile Photo">
    <?php } ?>
    <div class="profile-details">
        <p class="profile-email"><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <p class="profile-bio"><strong>Bio:</strong> <?php echo $user['bio']; ?></p>
    </div>

    <?php
            // If the logged-in user is viewing their own profile, display a link to edit-profile.php
            if ($_SESSION['user_id'] == $user_id) {
                echo '<a href="edit-profile.php">Edit Profile</a><br>';
            }

            // Check if the logged-in user and the user being viewed are already friends
            if ($_SESSION['user_id'] != $user_id) {
                $query = "SELECT * FROM friendships WHERE (user_id = {$_SESSION['user_id']} AND friend_id = {$user_id}) OR (user_id = {$user_id} AND friend_id = {$_SESSION['user_id']})";
                $result = mysqli_query($conn, $query);

                // If the two users are not friends, display a button to send a friend request
                if (!$result || mysqli_num_rows($result) == 0) {
                    echo '<form action="send-friend-request.php" method="POST">';
                    echo '<input type="hidden" name="friend_id" value="' . $user_id . '">';
                    echo '<input type="submit" value="Add friend">';
                    echo '</form>';
                    // Update the has_pending_requests column in the users table
                    $query = "UPDATE users SET has_pending_requests = 1 WHERE id = {$_SESSION['user_id']}";
                    $result = mysqli_query($conn, $query);

                    if (!$result) {
                        die('Error: Could not update has_pending_requests.');
                    }
                } else {
                    $friendship = mysqli_fetch_assoc($result);
                    if ($friendship['status'] == 'pending' && $friendship['user_id'] != $_SESSION['user_id']) {
                        echo '<p>You have a friend request from this user.</p>';
                    }
                }
            }
        ?>
    </main>

    <footer> 
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>
