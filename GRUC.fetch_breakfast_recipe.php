<?php
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

$category = 'Breakfast';
$sql = "SELECT recipeId, recipeName, picture, details FROM posted_recipe WHERE category = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

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

$stmt->close();
$conn->close();
?>