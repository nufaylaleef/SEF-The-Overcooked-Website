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

// SQL query to get trending recipes based on save count
$sql = "SELECT 
            pr.recipeId,
            pr.recipeName,
            pr.picture,
            pr.details,
            COUNT(sr.savedRecipeId) as save_count
        FROM posted_recipe pr
        LEFT JOIN saved_recipe sr ON pr.recipeId = sr.recipeId
        GROUP BY pr.recipeId, pr.recipeName, pr.picture, pr.details
        ORDER BY save_count DESC
        LIMIT 6"; // Limit to top 6 trending recipes

$result = $conn->query($sql);

// Fetch and display results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<a href='GRUC.recipedetails.php?recipeId=" . $row['recipeId'] . "' style='text-decoration: none; color: inherit;'>";
        echo "<div class='recipe-card'>";
        echo "<img src='" . $row['picture'] . "' alt='" . $row['recipeName'] . "'>";
        echo "<h2 class='recipe-name'>" . $row['recipeName'] . "</h2>";
        echo "<p class='recipe-details'>" . $row['details'] . "</p>";
        // show save count
        echo "<p class='save-count'><i class='fas fa-bookmark'></i> " . $row['save_count'] . " saves</p>";
        echo "</div>";
        echo "</a>";
    }
} else {
    echo "<p>No trending recipes found.</p>";
}

$conn->close();
?>