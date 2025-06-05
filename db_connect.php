<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'the_overcooked_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>