<?php

// Start the session
session_start();

// Include the database connection
require_once('../config.php');

// Get the friend ID from the POST data
$friend_id = $_POST['friend_id'];

// Check if the friend request already exists
$query = "SELECT * FROM friendships WHERE (user_id = {$_SESSION['user_id']} AND friend_id = {$friend_id}) OR (user_id = {$friend_id} AND friend_id = {$_SESSION['user_id']})";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    // Friend request already exists
    header('Location: profile.php?id=' . $friend_id);
    exit;
}

// Send the friend request
$query = "INSERT INTO friendships (user_id, friend_id, status) VALUES ({$_SESSION['user_id']}, {$friend_id}, 'pending')";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: Could not send friend request.');
}

// Redirect the user back to the profile page with a success message
header('Location: profile.php?id=' . $friend_id . '&success=1');
exit;

?>
