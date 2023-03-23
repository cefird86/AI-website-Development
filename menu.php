<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('../functions.php');

$user = isset($_SESSION['user_id']) ? getUserById($_SESSION['user_id']) : null;
?>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="groups.php">Groups</a></li>
        <li><a href="friends.php">Friends</a></li>
        <?php if (isset($_SESSION['user_id'])) { ?>
            <li class="user-profile">
                <a href="profile.php">
                    <?php if ($user['photo_url']) { ?>
                        <img class="profile-image" src="<?php echo $user['photo_url']; ?>" alt="Profile Photo">
                    <?php } else { ?>
                        <img class="profile-image" src="default_profile.jpg" alt="Default Profile Photo">
                    <?php } ?>
                    <span class="profile-name"><?php echo $_SESSION['username']; ?></span>
                </a>
            </li>
            <li><a href="logout.php">Log Out</a></li>
        <?php } else { ?>
            <li><a href="login.php">Log In</a></li>
            <li><a href="register.php">Register</a></li>
        <?php } ?>
    </ul>
</nav>

