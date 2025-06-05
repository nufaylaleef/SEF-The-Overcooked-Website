<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json"); 

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "the_overcooked_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    session_start();
    $chefId = $_SESSION['userId'] ?? null;
    if (!$chefId) {
        echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
        exit();
    }

    $pendingId = $_POST["pendingId"] ?? null;
    if (!$pendingId) {
        echo json_encode(["status" => "error", "message" => "Recipe ID is missing."]);
        exit();
    }

    $recipeName = $_POST["recipeName"] ?? '';
    $category = $_POST["category"] ?? '';
    $tag = $_POST["tag"] ?? '';
    $note = $_POST["note"] ?? '';
    $details = $_POST["details"] ?? '';
    $ingredients = $_POST["ingredients"] ?? '';
    $instruction = $_POST["instruction"] ?? '';

    // Check if recipe exists and belongs to the chef
    $checkRecipe = $conn->prepare("SELECT status FROM pending_recipe WHERE pendingId = ? AND chefId = ?");
    $checkRecipe->bind_param("ii", $pendingId, $chefId);
    $checkRecipe->execute();
    $result = $checkRecipe->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Recipe not found or unauthorized access."]);
        exit();
    }

    $recipe = $result->fetch_assoc();
    // If the recipe was rejected, reset status to pending and clear rejection reason
    if ($recipe['status'] === 'rejected') {
        $newStatus = 'pending';
        $clearRejectionReason = ", rejection_reason = NULL"; // 🆕 New line to reset rejection reason
    } else {
        $newStatus = $recipe['status'];
        $clearRejectionReason = "";
    }


    $checkRecipe->close();

    $sqlUpdate = "UPDATE pending_recipe 
    SET recipeName = ?, category = ?, tag = ?, note = ?, details = ?, ingredients = ?, instruction = ?, status = ?, submitted_at = NOW() 
    $clearRejectionReason  -- 🆕 This ensures rejection_reason is cleared when needed
    WHERE pendingId = ? AND chefId = ?";

    $stmt = $conn->prepare($sqlUpdate);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "SQL prepare failed: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("ssssssssii", $recipeName, $category, $tag, $note, $details, $ingredients, $instruction, $newStatus, $pendingId, $chefId);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Your recipe has been updated successfully."]);
        exit();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update recipe: " . $stmt->error]);
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
