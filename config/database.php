<?php
// Database configuration
$db_host = 'localhost';     // Database host
$db_name = 'strnghtf';   // Database name
$db_user = 'damian';   // Database username
$db_pass = 'zaq1@WSX';   // Database password

// Establish a database connection
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set PDO error mode to exception for easier error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle database connection errors gracefully
    die("Database connection failed: " . $e->getMessage());
}