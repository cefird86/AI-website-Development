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

// Get the selected message's information from the database
$message_id = $_GET['id'];
$query = "SELECT * FROM messages WHERE id = $message_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: messages.php');
    exit;
}

$message = mysqli_fetch_assoc($result);

// Get all messages in the conversation
$parent_id = $message['parent_id'] ? $message['parent_id'] : $message['id'];
$query = "SELECT m.*, u.first_name AS sender_name FROM messages m INNER JOIN users u ON m.sender_id = u.id WHERE (m.id = $parent_id OR m.parent_id = $parent_id) ORDER BY created_at ASC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error: Could not get the conversation.');
}

$conversation = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the form data
    $receiver_id = $message['sender_id'];
    $content = mysqli_real_escape_string($conn, $_POST['reply_content']);
    $subject = mysqli_real_escape_string($conn, $message['subject']);
    $parent_id = $message['parent_id'] ? $message['parent_id'] : $message['id']; // Update this line to get the correct parent_id
    
    // Insert the reply into the database
    $query = "INSERT INTO messages (sender_id, receiver_id, subject, content, parent_id, created_at, updated_at) VALUES ($_SESSION[user_id], $receiver_id, '" . mysqli_real_escape_string($conn, $message['subject']) . "', '$content', $parent_id, NOW(), NOW())"; // Update the query to include parent_id
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Error: Could not send the reply.');
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

        <table>
        <tbody>
            <?php foreach ($conversation as $msg) { ?>
                <tr>
                    <td><?php echo $msg['sender_id'] == $user_id ? "You" : $msg['sender_name']; ?>:</td>
                    <td><?php echo $msg['content']; ?></td>
                    <td><?php echo date('F j, Y, g:i a', strtotime($msg['created_at'])); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <form method="POST" action="read-message.php?id=<?php echo $message_id; ?>">
    <input type="hidden" name="id" value="<?php echo $message_id; ?>">
    <label for="reply_content">Reply:</label>
    <textarea name="reply_content" required></textarea>
    <input type="submit" value="Reply">
    </form>

        </div>
    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>