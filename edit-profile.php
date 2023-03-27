<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection
require_once('../config.php');

// Get the user's information from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit;
}

$row = mysqli_fetch_assoc($result);

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);


    // Upload the user's photo if a file was selected
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/";
        $user_id = $_SESSION['user_id'];
        $file_name = "user_" . $user_id . "_photo." . strtolower(pathinfo($_FILES['photo']['name'],PATHINFO_EXTENSION));
        $target_file = $target_dir . $file_name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["photo"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo_url = $target_file;

                // Delete the old photo if it exists
                if (!empty($row['photo_url'])) {
                    unlink($row['photo_url']);
                }

                // Update the user's photo URL in the database
                $query = "UPDATE users SET photo_url = '$photo_url' WHERE id = $user_id";
                $result = mysqli_query($conn, $query);

                if (!$result) {
                    die('Error: Could not update the user information.');
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Update the user's information in the database
    $query = "UPDATE users SET username = IF('$username' = '', username, '$username'), email = IF('$email' = '', email, '$email'), bio = IF('$bio' = '', bio, '$bio'), first_name = IF('$first_name' = '', first_name, '$first_name'), last_name = IF('$last_name' = '', last_name, '$last_name') WHERE id = $user_id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Error: Could not update the user information.');
    }

    // Redirect the user to the profile page
    header('Location: profile.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Nerdy Social Media Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include('menu.php'); ?>
    </header>

    <main>
        <h1>Edit Your Profile</h1>

        <form method="POST" action="edit-profile.php" enctype="multipart/form-data">
            <label for="username">Username</label>
            <input type="text" name="username" value="<?php echo $row['username']; ?>" required>

            <label for="email">Email</label>
            <input type="email" name="email" value="<?php echo $row['email']; ?>" required>

            <label for="first_name">First Name</label>
            <input type="text" name="first_name" value="<?php echo $row['first_name']; ?>" required>

            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" value="<?php echo $row['last_name']; ?>" required>

            <label for="bio">Bio</label>
            <textarea name="bio"><?php echo $row['bio']; ?></textarea>

            <?php if ($row['photo_url']) { ?>
            <label for="photo-current">Current Photo</label>
            <img src="<?php echo $row['photo_url']; ?>" alt="Current Profile Photo">
            <?php } ?>

            <label for="photo">New Photo</label>
            <input type="file" name="photo">

            <input type="submit" value="Save Changes">
        </form>
    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>
