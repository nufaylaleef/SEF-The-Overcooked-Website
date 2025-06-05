<?php
session_start();
header("Content-Type: application/json");

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$email = $data['email'];
$password = $data['password'];
$reentryPassword = $data['reentrypassword'];

// Database connection
$conn = new mysqli("localhost", "root", "", "the_overcooked_db");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

// Check if email already exists
$checkStmt = $conn->prepare("SELECT userId FROM users WHERE email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();
if ($checkStmt->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email is already registered."]);
    exit();
}
$checkStmt->close();

// Validate password re-entry
if ($password !== $reentryPassword) {
    echo json_encode(["success" => false, "message" => "Passwords do not match."]);
    exit();
}

$defaultRoleId = 1; // Assign roleId 1 (Registered User) by default

// Insert new user **(Password is stored in plain text)**
$insertStmt = $conn->prepare("INSERT INTO users (name, username, password, email, roleId) VALUES (?, ?, ?, ?, ?)");
$insertStmt->bind_param("ssssi", $username, $username, $password, $email, $defaultRoleId);

if ($insertStmt->execute()) {
    echo json_encode(["success" => true, "message" => "Signup successful! Redirecting to login..."]);
} else {
    echo json_encode(["success" => false, "message" => "Signup failed. Please try again."]);
}

$insertStmt->close();
$conn->close();
?>
