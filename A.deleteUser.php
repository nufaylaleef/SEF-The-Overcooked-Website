<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_POST['userId'];

    // Prepare and execute delete statement
    $stmt = $conn->prepare("DELETE FROM users WHERE userId=?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        echo "User deleted successfully!";
    } else {
        echo "Error deleting user: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
