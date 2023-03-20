<?php
require_once '../config.php';
require_once '../altconfig.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :user_id');
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container">
        <h1>My Profile</h1>

        <div class="profile-info">
            <div class="profile-picture">
                <?php if ($user['profile_picture']): ?>
                  <img src="<?php echo 'uploads/' . $user['profile_picture']; ?>" alt="Profile Picture" width="200" height="200">
                <?php else: ?>
                    <img src="/uploads/default-profile-picture.jpg" alt="Default Profile Picture" width="200" height="200">
                <?php endif; ?>
            </div>

            <div class="profile-details">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
            </div>
        </div>

        <?php if ($_SESSION['user_id'] == $user['id']): ?>
            <a href="update-profile.php">Update Profile</a>
        <?php endif; ?>       
    </div>
</body>
</html>
