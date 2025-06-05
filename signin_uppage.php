<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$email = $data['email'];
$password = $data['password']; // Store password in plain text
$reentryPassword = $data['reentrypassword'];

// Database connection
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "the_overcooked_db";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

// Ensure passwords match before inserting
if ($password !== $reentryPassword) {
    die(json_encode(["success" => false, "message" => "Passwords do not match."]));
}

// Check if email already exists
$stmt = $conn->prepare("SELECT userId FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email is already registered."]);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();

// Store password in plain text (removed password_hash)
$roleId = 1; // Default role for new users (Registered User)
$stmt = $conn->prepare("INSERT INTO users (name, email, password, roleId) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $username, $email, $password, $roleId);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Signup successful! You can now log in."]);
} else {
    echo json_encode(["success" => false, "message" => "Signup failed. Please try again."]);
}

$stmt->close();
$conn->close();
?>
