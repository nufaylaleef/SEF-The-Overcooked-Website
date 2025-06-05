<!-- / /posted_recipe_details.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>The OverCooked - Recipe Details</h1>
    </header>

    <div class="container">
        <?php
        $conn = new mysqli("localhost", "root", "", "the_overcooked_db");

        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        $recipeId = $_GET['id'];
        $sql = "SELECT * FROM posted_recipe WHERE recipeId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $recipeId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $recipe = $result->fetch_assoc();
            echo "<h2>" . htmlspecialchars($recipe['recipeName']) . "</h2>";
            echo "<img src='" . htmlspecialchars($recipe['picture']) . "' alt='" . htmlspecialchars($recipe['recipeName']) . "' class='recipe-details-image'>";
            echo "<p><strong>Chef ID:</strong> " . htmlspecialchars($recipe['chefId']) . "</p>";
            echo "<p><strong>Category:</strong> " . htmlspecialchars($recipe['category']) . "</p>";
            echo "<p><strong>Tag:</strong> " . htmlspecialchars($recipe['tag']) . "</p>";
            echo "<p><strong>Note:</strong> " . htmlspecialchars($recipe['note']) . "</p>";
            echo "<p><strong>Details:</strong> " . htmlspecialchars($recipe['details']) . "</p>";
            echo "<p><strong>Ingredients:</strong> " . htmlspecialchars($recipe['ingredients']) . "</p>";
            echo "<p><strong>Instructions:</strong> " . htmlspecialchars($recipe['instruction']) . "</p>";
        } else {
            echo "<p>Recipe not found.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
