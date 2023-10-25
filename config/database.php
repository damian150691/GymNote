<?php
// Database configuration
$db_host = 'localhost';     // Database host
$db_name = 'gymnote';      // Database name
$db_user = 'damian';        // Database username
$db_pass = 'zaq1@WSX';      // Database password

$db = new mysqli($db_host, $db_user, $db_pass, $db_name);



// Check for connection errors
if ($db->connect_error) {
    die("Database connection failed: " . $db->connect_error);
} 