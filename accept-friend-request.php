<?php

// Start the session
session_start();

// Include the database connection
require_once('../config.php');

// Get the friend ID from the POST data
$friend_id = $_POST['friend_id'];

// Check if the friend request exists
$query = "SELECT * FROM friendships WHERE user_id = {$friend_id} AND friend_id = {$_SESSION['user_id']} AND status = 'pending'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    // Friend request does not exist
    header('Location: profile.php?id=' . $friend_id);
    exit;
}

// Update the friend request status to 'accepted'
$query = "UPDATE friendships SET status = 'accepted' WHERE user_id = {$friend_id} AND friend_id = {$_SESSION['user_id']}";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: Could not accept friend request.');
}

// Redirect the user back to the profile page with a success message
header('Location: profile.php?id=' . $friend_id . '&success=2');
exit;
