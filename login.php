<?php
error_reporting(0);
session_start();
header("Content-Type: application/json");

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$password = $data['password'];

$conn = new mysqli("localhost", "root", "", "the_overcooked_db");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

// Fetch user details
$stmt = $conn->prepare("SELECT userId, username, password, roleId FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($userId, $username, $dbPassword, $roleId);
    $stmt->fetch();

    if ($password === $dbPassword) {  
        $_SESSION["sessionId"] = session_id();
        $_SESSION["userId"] = $userId;
        $_SESSION["username"] = $username;
        $_SESSION["roleId"] = $roleId;

        // Insert session data into database
        $stmt = $conn->prepare("
            INSERT INTO session (sessionId, userId, roleId, last_activity) 
            VALUES (?, ?, ?, NOW()) 
            ON DUPLICATE KEY UPDATE sessionId = VALUES(sessionId), last_activity = NOW()
        ");
        $stmt->bind_param("sii", $_SESSION["sessionId"], $userId, $roleId);
        $stmt->execute();
        $stmt->close();

        if ($roleId == 3) {
            echo json_encode(["success" => true, "redirect" => "./A.dashboard_page.html"]);
            exit;
        }

        echo json_encode(["success" => true, "redirect" => "./GRUC.dashboard.php"]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid email or password."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid email or password."]);
    }
$stmt->close();
$conn->close();
?>
