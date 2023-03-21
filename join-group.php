<?php
// Start the session and include the database connection
session_start();
require_once('../config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['join-group'])) {
    $user_id = $_SESSION['user_id'];
    $group_id = $_POST['group_id'];

    // Insert a new record into the group_members table
    $query = "INSERT INTO group_members (user_id, group_id) VALUES ('$user_id', '$group_id')";
    mysqli_query($conn, $query);

    // Redirect the user back to the group page
    header("Location: group.php?id=$group_id");
    exit;
}
?>
