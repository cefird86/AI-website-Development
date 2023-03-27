<?php

// Start the session
session_start();

// Include the database connection
require_once('../config.php');
require_once('../functions.php');

// Get the user ID from the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    $user_id = $_SESSION['user_id'];
}

// If the user ID is not provided in the URL, use the logged-in user's ID
$profile_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : $_SESSION['user_id'];

// Retrieve the user's data from the database
if ($user_id) {
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    header('Location: error.php?error=User not found');
    exit();
}

if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: error.php?error=User not found');
    exit();
}

$row = mysqli_fetch_assoc($result);

$current_user_id = $_SESSION['user_id'];
$profile_user_id = $_GET['id']; // Get the user ID from the URL or any other source

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['username']; ?>'s Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php  include('menu.php');?>
    </header>

    <main>
    <h1><?php echo $row['first_name']; ?>'s Profile</h1>
    <?php if ($row['photo_url']) { ?>
        <img class="profile-photo" src="<?php echo $row['photo_url']; ?>" alt="Profile Photo">
    <?php } ?>
    <div class="profile-details">
        <c><p class="profile-bio"><strong>Bio:</strong> <?php echo $row['bio']; ?></p></c>
    </div>

    <?php
            // If the logged-in user is viewing their own profile, display a link to edit-profile.php
            if ($_SESSION['user_id'] == $user_id) {
                echo '<a href="edit-profile.php">Edit Profile</a><br>';
            }

            $query = "SELECT * FROM friendships 
            WHERE (user_id = {$current_user_id} AND friend_id = {$user_id}) 
            OR (user_id = {$user_id} AND friend_id = {$current_user_id})";
  
            $result = mysqli_query($conn, $query);
  
            $is_friend = false;
            $is_pending_request = false;

            if ($result && mysqli_num_rows($result) > 0) {
                $friendship = mysqli_fetch_assoc($result);
                if ($friendship['status'] == 'accepted') {
                    $is_friend = true;
                } elseif ($friendship['status'] == 'pending') {
                    $is_pending_request = true;
                }
            }

            if ($is_friend): ?>
                <p>You are friends with this user.</p>
            <?php else: ?>
                <p>You are not friends with this user.</p>
            <?php endif; ?>
  
        <?php
            // If the two users are not friends, display a button to send a friend request or accept a request
            if (!$is_friend && !$is_pending_request) {
                echo '<form action="send-friend-request.php" method="POST">';
                echo '<input type="hidden" name="friend_id" value="' . $user_id . '">';
                echo '<input type="submit" value="Add friend">';
                echo '</form>';
            } elseif ($is_pending_request) {
                if ($friendship['user_id'] == $_SESSION['user_id']) {
                    echo '<p>You have sent a friend request to this user.</p>';
                } elseif ($friendship['user_id'] == $user_id) {
                    echo '<form action="accept-friend-request.php" method="POST">';
                    echo '<input type="hidden" name="friend_id" value="' . $user_id . '">';
                    echo '<input type="submit" value="Accept Request">';
                    echo '</form>';
                }
            }
        ?>
    </main>

   

    <footer> 
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>
