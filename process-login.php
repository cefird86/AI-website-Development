<?php

// Start the session
session_start();

// Include the database connection
require_once('../config.php');

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Find the user in the database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        $_SESSION['error'] = 'Incorrect username or password.';
        header('Location: login.php');
        exit;
    }

    $row = mysqli_fetch_assoc($result);

    if (!password_verify($password, $row['password'])) {
        $_SESSION['error'] = 'Incorrect username or password.';
        header('Location: login.php');
        exit;
    }

    // Store the user's ID and username in the session
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];

    // Redirect the user to the home page
    header('Location: index.php');
    exit;
}

?>
