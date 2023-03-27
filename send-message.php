<?php
session_start();

// Check if the useusernamer is not logged in
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

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Check if the receiver_id is set
        if (!isset($_POST['receiver_id'])) {
            header('Location: send-message.php?error=Recipient is not selected');
            exit;
        }

    // Get the parent message ID, if it exists
    $parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : null;

    // Get the form data
    $receiver_id = $_POST['receiver_id'];
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

// Check if the recipient is a friend of the sender
$query = "SELECT * FROM friendships WHERE (user_id = $user_id AND friend_id = $receiver_id AND status = 'accepted') OR (user_id = $receiver_id AND friend_id = $user_id AND status = 'accepted')";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: Could not check if recipient is a friend.');
}

if (mysqli_num_rows($result) == 0) {
    header('Location: send-message.php?error=Recipient is not your friend');
    exit;
}

// Insert the message into the database
$parent_id_value = is_null($parent_id) ? "NULL" : $parent_id;
$query = "INSERT INTO messages (sender_id, receiver_id, subject, content, parent_id, created_at, updated_at) VALUES ($user_id, $receiver_id, '$subject', '$content', $parent_id_value, NOW(), NOW())";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: Could not send the message.');
}

// Redirect the user to the messages page
header('Location: messages.php');
exit;

}

// Get the user's friends from the database
$query = "SELECT u.id, u.first_name, u.last_name FROM users u INNER JOIN friendships f ON u.id = f.friend_id WHERE f.user_id = $user_id AND f.status = 'accepted'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: Could not get the user\'s friends.');
}

$friends = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message - Nerdy Social Media Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include('menu.php'); ?>
    </header>

    <main>
        <h1>Send Message</h1>

        <form method="POST" action="send-message.php">
            <label for="receiver_id">To:</label>
            <select name="receiver_id" required>
                <option value="">Select a friend</option>
                <?php foreach ($friends as $friend) { ?>
                    <option value="<?php echo $friend['id']; ?>"><?php echo $friend['first_name'] . ' ' . $friend['last_name']; ?></option>
                <?php } ?>
            </select>

            <label for="subject">Subject:</label>
            <input type="text" name="subject" required>

            <label for="content">Message:</label>
            <textarea name="content" required></textarea>

            <input type="submit" value="Send">
        </form>
    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>