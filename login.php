<?php

// Start the session
session_start();

if (isset($_SESSION['error'])) {
    echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nerdy Social Media Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Nerdy Social Media Site</h1>
    </header>

    <main>
        <h1>Login to Your Account</h1>
        <form action="process-login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>
        <p>Don't have an account yet? <a href="register.php">Register now</a>.</p>
    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>
