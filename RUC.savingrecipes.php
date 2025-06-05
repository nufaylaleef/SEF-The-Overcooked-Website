<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["sessionId"]) || !isset($_SESSION["userId"])) {
    echo json_encode(["success" => false, "message" => "Please log in to save recipes"]);
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

// Check if recipe is already saved
$checkStmt = $conn->prepare("SELECT savedRecipeId FROM saved_recipe WHERE userId = ? AND recipeId = ?");
$checkStmt->bind_param("ii", $userId, $recipeId);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Recipe already saved"]);
    exit();
}

// Save the recipe
$stmt = $conn->prepare("INSERT INTO saved_recipe (userId, recipeId) VALUES (?, ?)");
$stmt->bind_param("ii", $userId, $recipeId);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Recipe saved successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error saving recipe"]);
}

$stmt->close();
$conn->close();
?>