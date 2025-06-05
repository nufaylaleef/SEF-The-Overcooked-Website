<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_POST['userId'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Now treated as normal field
    $email = $_POST['email'];
    $roleId = $_POST['roleId'];

    // Update all fields, including password
    $stmt = $conn->prepare("UPDATE users SET name=?, username=?, password=?, email=?, roleId=? WHERE userId=?");
    $stmt->bind_param("ssssii", $name, $username, $password, $email, $roleId, $userId);

    if ($stmt->execute()) {
        echo "User updated successfully!";
    } else {
        echo "Error updating user: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
