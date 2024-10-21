<?php
// Get email from GET array
$username = $_GET['username'] ?? null;

// Ensure it's a valid username before bothering to check the database
if (strlen($username) === 0) {
    echo 'error';
    exit;
}

// Include the library file and connect to the database
require 'includes/library.php';
$pdo = connectDB();

// Query for record matching provided username
$stmt = $pdo->prepare("SELECT * FROM `assn_users` WHERE username = ?");
$stmt->execute([$username]);

// remember that fetch returns false when there were no records
if ($stmt->fetch()) {
  echo 'true'; // username was found
} else {
  echo 'false'; // username was not found
}
