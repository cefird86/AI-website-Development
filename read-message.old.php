<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection
require_once('../config.php');

// Get the message ID from the query string
$message_id = $_GET['id'];

// Get the message from the database
$query = "SELECT * FROM messages WHERE id = $message_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: messages.php');
    exit;
}

$message = mysqli_fetch_assoc($result);

// Get the sender's information from the database
$sender_id = $message['sender_id'];
$query = "SELECT * FROM users WHERE id = $sender_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: messages.php');
    exit;
}

$sender = mysqli_fetch_assoc($result);

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the form data
    $recipient_id = $sender_id;
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // Insert the message into the database
    $query = "INSERT INTO messages (sender_id, receiver_id, subject, content, created_at, updated_at) VALUES ($_SESSION[user_id], $recipient_id, 'Re: " . mysqli_real_escape_string($conn, $message['subject']) . "', '$content', NOW(), NOW())";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Error: Could not send the message.');
    }

    // Redirect the user to the messages page
    header('Location: messages.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $message['subject']; ?> - Nerdy Social Media Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include('menu.php'); ?>
    </header>

    <main>
        <h1><?php echo $message['subject']; ?></h1>

        <p>From: <?php echo $sender['first_name'] . ' ' . $sender['last_name']; ?></p>
        <p>Sent: <?php echo $message['created_at']; ?></p>
        <p><?php echo $message['content']; ?></p>

        <form method="POST">
            <label for="content">Reply:</label>
            <textarea name="content" required></textarea>

            <input type="submit" value="Send">
        </form>
    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>