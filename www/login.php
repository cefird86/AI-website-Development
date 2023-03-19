<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
  // User is already logged in, redirect to the home page
  header('Location: index.php');
  exit();
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the form data
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Connect to the database
// Connect to the database
// Include the config file
require_once '../config.php';

// Connect to the database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check if the connection was successful
if (!$conn) {
  die('Could not connect to the database: ' . mysqli_connect_error());
}

  // Prepare a query to retrieve the user with the specified username
  $query = "SELECT id, password FROM users WHERE username = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 's', $username);

  // Execute the query
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // Check if a user was found with the specified username
  if ($row = mysqli_fetch_assoc($result)) {
    // Verify the password
    if (password_verify($password, $row['password'])) {
      // Password is correct, set the session variables and redirect to the home page
      $_SESSION['user_id'] = $row['id'];
      header('Location: index.php');
      exit();
    } else {
      // Password is incorrect, display an error message
      $error = 'Invalid username or password.';
    }
  } else {
    // User not found, display an error message
    $error = 'User not found';
  }

  // Close the database connection
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}
?>

<?php include('menu.php'); ?>

<!DOCTYPE html>
<html>
<head>
  <title>My Website - Login</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <header>
    <div class="container">
      <h1>My Website</h1>
    </div>
  </header>
  <main>
    <div class="container">
      <h2>Login</h2>
      <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
      <?php endif; ?>
      <form action="login.php" method="POST">
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
      </form>
    </div>
  </main>
  <footer>
    <div class="container">
      <p>&copy; 2023 My Website. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>

