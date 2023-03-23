<?php

// Include the database connection
require_once('config.php');

// Get a user by ID
function getUserById($id) {
    global $conn;
    $query = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

?>
