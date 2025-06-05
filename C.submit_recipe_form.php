<?php
session_start();
header("Content-Type: application/json"); // Ensure JSON response

// Ensure the user is logged in
if (!isset($_SESSION["userId"]) || !isset($_SESSION["roleId"])) {
    echo json_encode(["status" => "error", "message" => "User not logged in. Please log in first."]);
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "the_overcooked_db");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Retrieve form data
$chefId = $_SESSION["userId"]; // Use userId as chefId
$recipeName = $_POST["recipeName"];
$category = $_POST["category"];
$tag = $_POST["tag"];
$note = $_POST["note"];
$details = $_POST["details"];
$ingredients = $_POST["ingredients"];
$instruction = $_POST["instruction"];
$submittedAt = date("Y-m-d H:i:s");

// Handle image upload
$targetDir = "uploads/";
$uploadedFile = "";
if (!empty($_FILES["pictures"]["name"][0])) {
    $fileName = basename($_FILES["pictures"]["name"][0]);
    $targetFilePath = $targetDir . time() . "_" . $fileName;
    if (move_uploaded_file($_FILES["pictures"]["tmp_name"][0], $targetFilePath)) {
        $uploadedFile = $targetFilePath;
    }
}

// Insert into `pending_recipe` table
$stmt = $conn->prepare("INSERT INTO pending_recipe (chefId, recipeName, category, tag, note, details, ingredients, instruction, picture, submitted_at, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
$stmt->bind_param("isssssssss", $chefId, $recipeName, $category, $tag, $note, $details, $ingredients, $instruction, $uploadedFile, $submittedAt);

if ($stmt->execute()) {
    // Determine profile redirection based on role
    $profileLink = ($_SESSION["roleId"] == 1) 
        ? "profile.php?userId=" . $_SESSION["userId"]
        : "C.chef_profile.php?userId=" . $_SESSION["userId"];

    echo json_encode(["status" => "success", "message" => "Recipe submitted for review!", "redirect" => $profileLink]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to submit recipe. Please try again."]);
}

$stmt->close();
$conn->close();
?>
