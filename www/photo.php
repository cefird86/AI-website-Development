<?php
session_start();

if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit;
}

include('../config.php');

$user_id = $_SESSION['user_id'];

// Check if the user has submitted a new profile picture
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
  $file = $_FILES['profile_picture'];
  $upload_dir = 'uploads/';
  $filename = $file['name'];
  $tempname = $file['tmp_name'];
  $filetype = $file['type'];
  $filesize = $file['size'];
  $filepath = $upload_dir . $filename;

  // Check if the file is a valid image file type
  if ($filetype == 'image/png' || $filetype == 'image/jpeg' || $filetype == 'image/gif') {
    // Check if the file size is within the maximum allowed limit (e.g. 2 MB)
    if ($filesize <= 2 * 1024 * 1024) {
      // Upload the file to the server
      move_uploaded_file($tempname, $filepath);

      // Update the user's profile picture in the database
      $stmt = $pdo->prepare('UPDATE users SET profile_picture = :profile_picture WHERE user_id = :user_id');
      $stmt->bindParam(':profile_picture', $filename);
      $stmt->bindParam(':user_id', $user_id);
      $stmt->execute();

      // Display a success message
      echo '<p>Your profile picture has been updated.</p>';
    } else {
      // Display an error message if the file is too large
      echo '<p>The file size exceeds the maximum allowed limit.</p>';
    }
  } else {
    // Display an error message if the file type is not allowed
    echo '<p>Only PNG, JPEG, and GIF files are allowed.</p>';
  }
}

// Get the user's information from the database
$stmt = $pdo->prepare('SELECT * FROM users WHERE user_id = :user_id');
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Photo</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
    /* Style the profile picture */
    .profile-picture {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      object-position: center;
    }

    /* Style the file input */
    input[type=file] {
      margin-top: 16px;
    }
  </style>
</head>
<body>
  <?php include('menu.php'); ?>
  <h1>Welcome, <?php echo $user['name']; ?>!</h1>
  <img class="profile-picture" src="uploads/<?php echo $user['profile_picture']; ?>" alt="Profile picture">
  <form method="post" enctype="multipart/form-data">
    <input type="file" name="profile_picture">
    <button type="submit">Upload</button>
  </form>
</body>
</html>
