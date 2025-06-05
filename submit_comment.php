<?php
session_start();

// Redirect to login if session is not active
if (!isset($_SESSION["sessionId"]) || !isset($_SESSION["userId"])) {
    header("Location: signin_uppage.html");
    exit();
}

// Database credentials
$host = "localhost";
$username = "root";
$password = "";
$database = "the_overcooked_db";

// Enable MySQLi exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = mysqli_connect($host, $username, $password, $database);
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

// Ensure connection exists
if (!$conn) {
    die("Database connection is not available.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipeId = intval($_POST["recipeId"]);
    $userId = intval($_POST["userId"]); 
    $commentContent = trim($_POST["commentContent"]);

    if (!empty($commentContent) && $recipeId > 0 && $userId > 0) {
        // Changed 'comments' to 'comment' to match your database table name
        $sql = "INSERT INTO comment (commentContent, commentDatetime, userId, recipeId) VALUES (?, NOW(), ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $commentContent, $userId, $recipeId);

        if ($stmt->execute()) {
            // Changed redirect to match your actual filename
            header("Location: GRUC.recipedetails.php?recipeId=" . $recipeId . "&success=1");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Invalid comment data. Please ensure all fields are filled correctly.";
    }
} else {
    echo "Invalid request method. Only POST requests are allowed.";
}

$conn->close();
?>