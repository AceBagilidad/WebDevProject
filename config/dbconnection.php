<?php
$host = 'localhost';
$db = 'luxe';
$user = 'root';
$pass = '';

// Create Connection
$conn = new mysqli($host, $user, $pass, $db);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>