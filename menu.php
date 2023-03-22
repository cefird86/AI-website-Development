<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="profile.php?id=<?php echo $_SESSION['user_id']; ?>">Profile</a></li>
        <li><a href="friends.php">Friends</a></li>
        <li><a href="groups.php">Groups</a></li>
        <li><a href="#">Settings</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>
