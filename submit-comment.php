<?php
// Get the form data
$name = $_POST['name'];
$email = $_POST['email'];
$comment = $_POST['comment'];

// Create a new comment object
$commentObject = new stdClass();
$commentObject->name = $name;
$commentObject->email = $email;
$commentObject->comment = $comment;
$commentObject->timestamp = time();

// Save the comment object to a file
$filename = 'comments.json';
$comments = [];

// Check if the file exists
if (file_exists($filename)) {
  // Get the existing comments from the file
  $json = file_get_contents($filename);
  $comments = json_decode($json);
}

// Add the new comment to the array
array_push($comments, $commentObject);

// Save the updated comments array to the file
$json = json_encode($comments, JSON_PRETTY_PRINT);
file_put_contents($filename, $json);

// Redirect back to the comments page
header('Location: comments.php');
exit();
?>
