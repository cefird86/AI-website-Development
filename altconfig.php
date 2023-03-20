<?php
$host = 'localhost';
$dbname = 'testdb';
$username = 'admin';
$password = 'admin';

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
  $pdo = new PDO($dsn, $username, $password);
  // Set PDO to throw exceptions on errors
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  // Handle database connection errors
  echo 'Error connecting to the database: ' . $e->getMessage();
  exit;
}
?>