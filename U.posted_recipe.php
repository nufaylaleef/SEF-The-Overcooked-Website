<!-- / /approved_recipe.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>The OverCooked - Recipes</h1>
    </header>

    <div class="container">
        <h2>Browse Recipes</h2>
        <div class="recipe-grid">
            <?php
            $conn = new mysqli("localhost", "root", "", "the_overcooked_db");

            if ($conn->connect_error) {
                die("Database connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM posted_recipe ORDER BY datetime_posted DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='recipe-card'>
                            <img src='" . htmlspecialchars($row['picture']) . "' alt='" . htmlspecialchars($row['recipeName']) . "' class='recipe-image'>
                            <h3>" . htmlspecialchars($row['recipeName']) . "</h3>
                            <p><strong>Chef ID:</strong> " . htmlspecialchars($row['chefId']) . "</p>
                            <p><strong>Tag:</strong> " . htmlspecialchars($row['tag']) . "</p>
                            <p><strong>Date Posted:</strong> " . htmlspecialchars($row['datetime_posted']) . "</p>
                            <a href='U.posted_recipe_details.php?id=" . htmlspecialchars($row['recipeId']) . "' class='details-button'>View Details</a>
                        </div>";
                }
            } else {
                echo "<p>No approved recipes available.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
