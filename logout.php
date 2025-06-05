<?php
session_start();
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "the_overcooked_db");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

// Delete session from the database
if (isset($_SESSION["sessionId"])) {
    $stmt = $conn->prepare("DELETE FROM session WHERE sessionId = ?");
    $stmt->bind_param("s", $_SESSION["sessionId"]);
    $stmt->execute();
    $stmt->close();
}

// Destroy PHP session
session_unset();
session_destroy();

echo json_encode(["success" => true, "message" => "Logout successful."]);
header("Location: signin_uppage.html");
exit();
?>
