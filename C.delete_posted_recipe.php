<?php

$conn = new mysqli("localhost", "root", "", "the_overcooked_db");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $recipeId = $_POST["recipeId"];

    $sql = "DELETE FROM posted_recipe WHERE recipeId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recipeId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Recipe deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete recipe."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

$conn->close();
?>
