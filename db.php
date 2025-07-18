<?php
$host = "localhost";
$user = "root";
$password = ""; // Use your actual DB password here
$database = "customers";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
