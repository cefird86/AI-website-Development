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
// Include the config file using a relative file path
require_once '../config.php';

// Connect to the database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Check if the connection was successful
  if (!$conn) {
    die('Could not connect to the database: ' . mysqli_connect_error());
  }

  // Prepare a query to insert the new user into the database
  $query = "INSERT INTO users (username, password) VALUES (?, ?)";
  $stmt = mysqli_prepare($conn, $query);

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Bind the parameters to the query
  mysqli_stmt_bind_param($stmt, 'ss', $username, $hashed_password);

  // Execute the query
  if (mysqli_stmt_execute($stmt)) {
    // Sign-up was successful, set the session variables and redirect to the home page
    $_SESSION['user_id'] = mysqli_insert_id($conn);
    header('Location: index.php');
    exit();
  } else {
    // Sign-up failed, display an error message
    $error = 'Could not create user.';
  }

  // Close the database connection
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Website - Sign Up</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <header>
    <div class="container">
      <h1>My Website</h1>
      <nav>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="comments.php">Comments</a></li>
          <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="logout.php">Logout</a></li>
          <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li class="active"><a href="signup.php">Sign Up</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <div class="container">
      <h2>Sign Up</h2>
      <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
      <?php endif; ?>
      <form action="signup.php" method="POST">
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required>
		  </div>
        <div class="form-group">
          <button type="submit">Sign Up</button>
        </div>
      </form>
    </div>
  </main>
</body>
</html>