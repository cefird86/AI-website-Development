<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection
require_once('../config.php');

// Get the ID of the group to leave
$group_id = $_POST['group_id'];

// Remove the user from the group
$query = "DELETE FROM group_members WHERE group_id = $group_id AND user_id = {$_SESSION['user_id']}";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: Could not leave group.');
}

// Redirect the user back to the group page
header("Location: group.php?id=$group_id");
exit;
?>
