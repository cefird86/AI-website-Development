<?php
session_start();

require_once('../config.php');
require_once('../altconfig.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  // Validate input
  // ...

  // Update password in database
  $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE username = :username');
  $stmt->execute(['password' => password_hash($new_password, PASSWORD_DEFAULT), 'username' => $username]);

  // Redirect user to login page
  header('Location: login.php');
  exit();
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">
  <h2>Forgot Password</h2>
  <form method="post">
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>
    </div>
    <div class="form-group">
      <label for="new_password">New Password</label>
      <input type="password" id="new_password" name="new_password" required>
    </div>
    <div class="form-group">
      <label for="confirm_password">Confirm Password</label>
      <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <button type="submit">Reset Password</button>
  </form>
</div>

</body>
</html>
