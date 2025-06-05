<?php
// Redirect if not logged in
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

// Get saved recipes for the logged-in user
$userId = $_SESSION['userId'];
$sql = "SELECT pr.recipeId, pr.recipeName, pr.picture, pr.details 
        FROM posted_recipe pr 
        INNER JOIN saved_recipe sr ON pr.recipeId = sr.recipeId 
        WHERE sr.userId = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Check if any recipes are saved
if ($result->num_rows === 0) {
    echo "<div class='no-recipes'>";
    echo "<p>You haven't saved any recipes yet.</p>";
    echo "</div>";
} else {
    // Fetch and display results
    while ($row = $result->fetch_assoc()) {
        echo "<a href='GRUC.recipedetails.php?recipeId=" . $row['recipeId'] . "' style='text-decoration: none; color: inherit;'>";
        echo "<div class='recipe-card'>";
        echo "<img src='" . $row['picture'] . "' alt='" . $row['recipeName'] . "'>";
        echo "<h2 class='recipe-name'>" . $row['recipeName'] . "</h2>";
        echo "<p class='recipe-details'>" . $row['details'] . "</p>";
        echo "</div>";
        echo "</a>";
    }
}

$stmt->close();
$conn->close();
?>