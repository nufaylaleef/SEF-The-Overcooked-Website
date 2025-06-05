<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json"); // Ensure the response is JSON

// Enable error reporting to catch issues
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
    $chefId = $_SESSION['userId'] ?? null; // ✅ Fixed session variable (previously used 'chef_id')
    if (!$chefId) {
        echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
        exit();
    }

    $originalId = $_POST["recipeId"] ?? null; // ✅ This should be the posted_recipe ID
    if (!$originalId) {
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

    // Debugging - Log received data
    error_log("Received Data: " . json_encode($_POST));

    // ✅ Check if the recipe actually exists in posted_recipe before proceeding
    $checkRecipeExists = $conn->prepare("SELECT * FROM posted_recipe WHERE recipeId = ? AND chefId = ?");
    $checkRecipeExists->bind_param("ii", $originalId, $chefId);
    $checkRecipeExists->execute();
    $recipeResult = $checkRecipeExists->get_result();

    if ($recipeResult->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Recipe not found or unauthorized access."]);
        exit();
    }
    $checkRecipeExists->close();

    // ✅ Check if an edit for this recipe is already pending in pending_recipe
    $checkPending = $conn->prepare("SELECT * FROM pending_recipe WHERE originalId = ? AND status = 'pending'");
    $checkPending->bind_param("i", $originalId);
    $checkPending->execute();
    $pendingResult = $checkPending->get_result();
    
    if ($pendingResult->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "This recipe already has a pending edit."]);
        exit();
    }
    $checkPending->close();

    // ✅ Insert new pending edit into pending_recipe table, linking to original posted recipe
    $sqlInsert = "INSERT INTO pending_recipe 
        (recipeName, category, tag, note, details, ingredients, instruction, chefId, submitted_at, status, originalId) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pending', ?)";
    
    $stmt = $conn->prepare($sqlInsert);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "SQL prepare failed: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("sssssssii", $recipeName, $category, $tag, $note, $details, $ingredients, $instruction, $chefId, $originalId);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Your recipe edit has been submitted for approval."]);
        exit();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to submit recipe edit: " . $stmt->error]);
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
