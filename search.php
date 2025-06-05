<?php
function searchRecipes($searchTerm) {
    $conn = new mysqli("localhost", "root", "", "the_overcooked_db");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Search in recipe names, categories, tags, and ingredients
    $sql = "SELECT * FROM posted_recipe 
            WHERE recipeName LIKE ? 
            OR category LIKE ? 
            OR tag LIKE ?
            OR ingredients LIKE ?
            ORDER BY datetime_posted DESC";
            
    $searchPattern = "%$searchTerm%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $searchPattern, $searchPattern, $searchPattern, $searchPattern);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $recipes = [];
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
    
    $stmt->close();
    $conn->close();
    
    return $recipes;
}
?>