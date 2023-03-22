<?php

// Start the session
session_start();

// Include the database connection
require_once('../config.php');

// Get the user ID from the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    $user_id = $_SESSION['user_id'];
}

// Query the database to retrieve the user's information
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die('Error: User not found.');
}

$row = mysqli_fetch_assoc($result);

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
        <?php include('menu.php'); ?>
    </header>

    <main>
        <h1><?php echo $row['username']; ?>'s Profile</h1>
        <p>Email: <?php echo $row['email']; ?></p>
        <p>Bio: <?php echo $row['bio']; ?></p>
<?php

// Check if the logged-in user is already friends with the user whose profile is being viewed
if ($_SESSION['user_id'] != $user_id) {
    $query = "SELECT * FROM friendships WHERE (user_id = {$_SESSION['user_id']} AND friend_id = {$user_id}) OR (user_id = {$user_id} AND friend_id = {$_SESSION['user_id']})";
    $result = mysqli_query($conn, $query);

    // If the two users are not friends, display a button to send a friend request
    if (!$result || mysqli_num_rows($result) == 0) {
        echo '<form action="send-friend-request.php" method="POST">';
        echo '<input type="hidden" name="friend_id" value="' . $user_id . '">';
        echo '<input type="submit" value="Add friend">';
        echo '</form>';
    } else {
        $friendship = mysqli_fetch_assoc($result);
        if ($friendship['status'] == 'pending' && $friendship['user_id'] != $_SESSION['user_id']) {
            echo '<p>You have a friend request from this user.</p>';
        }
    }
}

// Display accepted friends of the user whose profile is being viewed
$query = "SELECT users.id, users.username FROM users JOIN friendships ON users.id = friendships.friend_id WHERE friendships.user_id = {$user_id} AND friendships.status = 'accepted'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: Could not get accepted friends.');
}

$num_accepted_friends = mysqli_num_rows($result);

// Display the number of accepted friends, if any
if ($num_accepted_friends > 0) {
    echo '<p>Friends List:</p>';
    echo '<ul>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<li><a href="profile.php?id=' . $row['id'] . '">' . $row['username'] . '</a></li>';
    }
    echo '</ul>';
}


// Check if the logged-in user has any pending friend requests
$query = "SELECT * FROM friendships WHERE friend_id = {$_SESSION['user_id']} AND status = 'pending'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: Could not check for friend requests.');
}

$num_pending_requests = mysqli_num_rows($result);

// Display the number of pending friend requests, if any
if ($num_pending_requests > 0) {
    echo '<p>You have <a href="accept-friends.php">' . $num_pending_requests . ' pending friend request(s)</a>.</p>';
}

// Check if the user whose profile is being viewed has sent a friend request to the logged-in user
$query = "SELECT * FROM friendships WHERE user_id = {$_SESSION['user_id']} AND friend_id = {$user_id} AND status = 'pending'";
$result = mysqli_query($conn, $query);

// Display the accept friend request button if the logged-in user has a pending friend request from the user whose profile is being viewed
if ($num_pending_requests > 0) {
    echo '<form action="accept-friend-request.php" method="POST">';
    echo '<input type="hidden" name="user_id" value="' . $_SESSION['user_id'] . '">';
    echo '<input type="hidden" name="friend_id" value="' . $row['id'] . '">';
    echo '<input type="submit" value="Accept friend request">';
    echo '</form>';
}


?>


    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>
