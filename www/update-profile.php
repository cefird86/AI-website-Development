<?php
include '../config.php';
include '../altconfig.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $profile_picture = $_FILES['profile_picture'];

    // Get current user data from database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :user_id');
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Update user data in database
    $stmt = $pdo->prepare('UPDATE users SET name = :name, email = :email, bio = :bio WHERE id = :user_id');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':bio', $bio);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Check if a new profile picture was uploaded
    if ($profile_picture['error'] === UPLOAD_ERR_OK) {
        // Delete old profile picture from /uploads folder
        if ($user['profile_picture'] !== 'default.png') {
            unlink('uploads/' . $user['profile_picture']);
        }

        // Move new profile picture to /uploads folder
        $ext = pathinfo($profile_picture['name'], PATHINFO_EXTENSION);
        $filename = 'user_' . $user_id . '.' . $ext;
        move_uploaded_file($profile_picture['tmp_name'], 'uploads/' . $filename);

        // Update profile picture in database
        $stmt = $pdo->prepare('UPDATE users SET profile_picture = :profile_picture WHERE id = :user_id');
        $stmt->bindParam(':profile_picture', $filename);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }

    // Redirect to profile page
    header('Location: profile.php');
    exit();
}

// Get current user data from database
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :user_id');
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <h1>Update Profile</h1>

    <form method="post" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>"><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>"><br>

        <label>Bio:</label>
        <textarea name="bio"><?php echo $user['bio']; ?></textarea><br>

        <label>Profile Picture:</label>
        <input type="file" name="profile_picture"><br>

        <img src="<?php echo 'uploads/' . $user['profile_picture']; ?>" width="100"><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>
