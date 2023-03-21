<?php

// Start the session
session_start();

// Clear the session data
session_unset();
session_destroy();

// Redirect the user to the login page
header('Location: login.php');
exit;

?>
