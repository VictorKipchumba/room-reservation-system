<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost"; // XAMPP MySQL server
$username = "root";        // Default XAMPP MySQL username
$password = "";            // Default XAMPP MySQL password (empty)
$dbname = "room_reservation_system"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>