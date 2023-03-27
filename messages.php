<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection
require_once('../config.php');

// Get the user's information from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit;
}

$row = mysqli_fetch_assoc($result);

// Get the user's messages from the database
$query = "SELECT m.*, u.first_name, u.last_name FROM messages m INNER JOIN users u ON m.sender_id = u.id WHERE (m.receiver_id = $user_id OR m.sender_id = $user_id) AND m.parent_id IS NULL ORDER BY m.created_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: Could not get the user\'s messages.');
}

$messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Nerdy Social Media Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include('menu.php'); ?>
    </header>

    <main>
        <h1>Messages</h1>
        
        <!-- Add a link to send-message.php -->
        <p><a href="send-message.php">Send A New Message</a></p>

        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>From</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message) { ?>
                    <tr>
                        <td><a href="read-message.php?id=<?php echo $message['id']; ?>"><?php echo $message['subject']; ?></a></td>
                        <td><?php echo $message['first_name'] . ' ' . $message['last_name']; ?></td>
                        <td><?php echo date('F j, Y, g:i a', strtotime($message['created_at'])); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>

