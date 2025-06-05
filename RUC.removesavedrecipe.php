<?php
// RUC.removerecipe.php
session_start();

// Check if user is logged in
if (!isset($_SESSION["sessionId"]) || !isset($_SESSION["userId"])) {
    echo json_encode(["success" => false, "message" => "Please log in to remove recipes"]);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$userId = intval($data['userId']);
$recipeId = intval($data['recipeId']);

// Validate data
if (!$userId || !$recipeId) {
    echo json_encode(["success" => false, "message" => "Invalid data"]);
    exit();
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "the_overcooked_db");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit();
}

// Remove the saved recipe
$stmt = $conn->prepare("DELETE FROM saved_recipe WHERE userId = ? AND recipeId = ?");
$stmt->bind_param("ii", $userId, $recipeId);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Recipe removed successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error removing recipe"]);
}

$stmt->close();
$conn->close();
?>