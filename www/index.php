<?php include('menu.php'); ?>


<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
 //Uncomment the next line if you want to see what the $_SESSION key is
//print_r($_SESSION);

 //Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // User is not logged in, redirect to the login page
//header('Location: login.php');
  //exit();
}

// Connect to the database
// Include the config file
require_once '../config.php';

// Connect to the database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check if the connection was successful
if (!$conn) {
  die('Could not connect to the database: ' . mysqli_connect_error());
}

// Query the database for the user's username
$query = "SELECT username FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $username);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Website</title>
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
      <?php if (isset($_SESSION['user_id'])): ?>
        <p>Welcome, <?php echo $username; ?>, to My Website!</p>
        <p>This is a website created with ChatGPT.</p><br>
      <?php else: ?>
        <h2>Welcome to My Website</h2>
        <p>This is a website created with ChatGPT.</p>
      <?php endif; ?>
      <p>This website is entirely designed, developed, and maintained using the power of artificial intelligence, specifically OpenAI's ChatGPT. Through human prompts and collaboration, ChatGPT helps create and refine the content you see here.</p>
            <p>Artificial intelligence has come a long way in recent years, and one of its most notable capabilities is natural language processing. ChatGPT is an AI model that can understand and generate human-like text based on the prompts it receives. By harnessing this technology, we're able to build and maintain this website with minimal manual coding.</p>
            <p>If you're interested in contributing or learning more, please visit our GitHub repository at <a href="https://github.com/cefird86/chatGPTwebsite" target="_blank">https://github.com/cefird86/chatGPTwebsite</a>. Join us in exploring the potential of AI-driven web development and experience the cutting edge of human-AI collaboration!</p>
      <img src="\images\neural.jpg" alt="A beautiful image">
    </div>
  </main>
  <footer>
    <div class="container">
      <p>&copy; 2023 My Website. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>