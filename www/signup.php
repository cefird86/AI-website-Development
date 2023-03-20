<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Connect to the database and insert the new user
  include('../config.php');
  include('../altconfig.php');
 
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $email = $_POST['email'];
  
    // Check if the user has submitted a new profile picture
    if (isset($_FILES['profile_picture'])) {
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
  
          // Insert the new user into the database with the profile picture
          $stmt = $pdo->prepare('INSERT INTO users (username, password, name, email, profile_picture) VALUES (:username, :password, :name, :email, :profile_picture)');
          $stmt->bindParam(':username', $username);
          $stmt->bindParam(':password', $password);
          $stmt->bindParam(':name', $name);
          $stmt->bindParam(':email', $email);
          $stmt->bindParam(':profile_picture', $filename);
          $stmt->execute();
        } else {
          // Display an error message if the file is too large
          echo '<p>The file size exceeds the maximum allowed limit.</p>';
        }
      } else {
        // Display an error message if the file type is not allowed
        echo '<p>Only PNG, JPEG, and GIF files are allowed.</p>';
      }
    } else {
      // Insert the new user into the database without the profile picture
      $stmt = $pdo->prepare('INSERT INTO users (username, password, name, email) VALUES (:username, :password, :name, :email)');
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':password', $password);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':email', $email);
      $stmt->execute();
    }
  
    // Display a success message
    echo '<p>Your account has been created. You can now <a href="login.php">log in</a>.</p>';
  }
  ?>
  
  <!DOCTYPE html>
  <html>
  <head>
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
      /* Center the form on the page */
      form {
        margin: auto;
        max-width: 400px;
        padding: 20px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 5px;
      }
  
      /* Style the form fields */
      input[type=text], input[type=password], input[type=email] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
    }

/* Style the file input */
input[type=file] {
  margin-top: 16px;
}

/* Style the submit button */
button[type=submit] {
  background-color: #4CAF50;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  width: 100%;
}

/* Style the cancel button */
button[type=button] {
  background-color: #ddd;
  color: black;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  width: 100%;
}

/* Style the container for the cancel button */
.cancel-container {
  text-align: center;
  margin-top: 16px;
}

/* Style the link to the login page */
.login-link {
  color: #4CAF50;
}
</style>
</head>
<body>
  <?php include('menu.php'); ?>
  <h1>Sign Up</h1>
  <form method="post" enctype="multipart/form-data">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <label for="profile_picture">Profile Picture:</label>
    <input type="file" id="profile_picture" name="profile_picture">
    <button type="submit">Sign Up</button>
    <div class="cancel-container">
      <button type="button" onclick="location.href='index.php'">Cancel</button>
    </div>
  </form>
</body>
</html>
  