<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "the_overcooked_db");

// validate_chef.php

if ($conn->connect_error) {
    echo json_encode(["exists" => false]);
    exit;
}

$chefId = $_GET["chefId"];
$sql = "SELECT COUNT(*) AS count FROM users WHERE userId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $chefId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row["count"] > 0) {
    echo json_encode(["exists" => true]);
} else {
    echo json_encode(["exists" => false]);
}

$stmt->close();
$conn->close();
?>
