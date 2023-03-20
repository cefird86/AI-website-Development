<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="about.php">About</a></li>
    <li><a href="comments.php">Comments</a></li>
    <li><a href="hangman.php">Hangman</a></li>
	<?php if (isset($_SESSION['user_id'])): ?>
      <li><a href="profile.php">My Profile</a></li>
      <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
      <li><a href="login.php">Login</a></li>
      <li class="active"><a href="signup.php">Sign Up</a></li>
    <?php endif; ?>
  </ul>
</nav>
