<?php

// Start the session
session_start();

// Include the database connection
require_once('../config.php');


 //Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nerdy Social Media Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include('menu.php'); ?>
    </header>

    <main>
        <section class="hero">
            <h1>Welcome to the Nerdy Social Media Site</h1>
            <p>You are logged in as <?php echo $_SESSION['username']; ?>.</p>
            <p>Connect with fellow nerds, join groups, and share your interests and hobbies.</p>
        </section>

        <section class="features">
            <div class="feature">
            </p>
                <?php
                    // Retrieve the 3 newest created groups
                    $query = "SELECT my_groups.*, COUNT(group_members.user_id) as member_count FROM my_groups LEFT JOIN group_members ON my_groups.id = group_members.group_id GROUP BY my_groups.id ORDER BY created_at DESC LIMIT 3";
                    $result = mysqli_query($conn, $query);

                    // Display the groups
                    if (mysqli_num_rows($result) > 0) {
                        echo '<div class="feature">';
                        echo '<h2>Newest Groups</h2>';
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<p><a href="group.php?id=' . $row['id'] . '">' . $row['group_name'] . '</a> - Created ' . date('F j, Y', strtotime($row['created_at'])) . ', ' . $row['member_count'] . ' members</p>';
                        }
                        echo '</div>';
                    }
                ?>

            </div>
            <div class="feature">
                <h2>Share your interests</h2>
                <p>Post about your favorite hobbies, games, and topics.</p>
            </div>
            <div class="feature">
                <h2>Connect with others</h2>
                <p>Message other users and make new friends.</p>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>
