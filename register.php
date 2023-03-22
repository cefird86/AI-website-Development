<?php


// Start the session
session_start();


// Set up a connection to the database

require_once('../config.php');

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate the form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $bio = $_POST['bio'];

    // Perform basic form validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        die('Error: Please fill in all required fields.');
    }

    if ($password != $confirm_password) {
        die('Error: Passwords do not match.');
    }

    // Validate the email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Error: Invalid email address.');
    }

    // Hash the password using bcrypt
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Save the data to the database
    $query = "INSERT INTO users (username, email, password, bio) VALUES ('$username', '$email', '$hashed_password', '$bio')";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Error: Could not save the user information.');
    }

    // Redirect the user to the login page
    header('Location: login.php');
    exit;
}

?>
<!-- Display the registration form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Nerdy Social Media Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php //include('menu.php'); ?> 
    </header>

    <main>
        <h1>Register for an Account</h1>
        <form action="register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio"></textarea>

            <input type="submit" value="Register">
        </form>
    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>
