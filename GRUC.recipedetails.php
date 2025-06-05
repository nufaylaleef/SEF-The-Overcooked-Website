<?php
session_start();

// Redirect to login if session is not active
if (!isset($_SESSION["sessionId"]) || !isset($_SESSION["userId"])) {
    header("Location: signin_uppage.html");
    exit();
}

// Assign userId to chefId (since all chefs are also users)
$chefId = $_SESSION['userId'];

$profileLink = ($_SESSION["roleId"] == 1) 
    ? "profile.php?userId=" . $_SESSION["userId"]
    : "C.chef_profile.php?userId=" . $_SESSION["userId"];

// Database connection
$conn = new mysqli("localhost", "root", "", "the_overcooked_db");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Retrieve user information
$userId = $_SESSION["userId"];
$stmt = $conn->prepare("SELECT name FROM users WHERE userId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($name);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>The OverCooked</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="GRUC.recipedetails.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Newsreader:opsz@6..72&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,100..900&display=swap');
    </style>
</head>

<body>
    <header>
        <img class="logo" src="assets/brand_logo.svg" alt="logo">
        <nav>
            <ul class="navigation_link">
                <li><a href="GRUC.dashboard.php">Home</a></li>
                <li><a href="GRUC.recipe_list_trending.php">Trending Recipes</a></li>
                <li><a href="GRUC.browse_category.php">Browse by Category</a></li>
                <li><a href="<?php echo $profileLink; ?>">Profile</a></li>
            </ul>
        </nav>
        <form class="search-form" action="RUC.search_results.php" method="GET">
            <input type="search" name="q" placeholder="Search for recipes!">
            <i class="fa fa-search"></i>
        </form>
        <script src="RUC.saverecipe.js"></script>
    </header>

    <main class="recipe-details">
        <?php
            if (isset($_GET['recipeId'])) {
                $recipeId = intval($_GET['recipeId']); // Ensure it's an integer
            } else {
                die("Recipe ID is missing!");
            }

            //$sql = "SELECT recipeName, picture, chefId, tag, note, details, ingredients, instruction 
            //        FROM posted_recipe 
            //        WHERE recipeId = ?";

            $sql = "SELECT users.username, posted_recipe.recipeName, posted_recipe.picture, posted_recipe.chefId, posted_recipe.tag, posted_recipe.note, posted_recipe.details, posted_recipe.ingredients, posted_recipe.instruction 
                    FROM posted_recipe 
                    JOIN users ON posted_recipe.chefId = users.userId
                    WHERE posted_recipe.recipeId = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $recipeId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result-> fetch_assoc()) {
                echo "<section class='recipe-header'>";
                echo "<h1>{$row['recipeName']}</h1>";

                echo "<div class='recipe-tags'>";
                echo "<span>{$row['tag']}</span>";
                echo "</div>";

                echo "<img src='{$row['picture']}' alt='{$row['recipeName']}'>";
                echo "</section>";

                // Check if recipe is already saved
                $checkSavedStmt = $conn->prepare("SELECT savedRecipeId FROM saved_recipe WHERE userId = ? AND recipeId = ?");
                $checkSavedStmt->bind_param("ii", $_SESSION['userId'], $recipeId);
                $checkSavedStmt->execute();
                $savedResult = $checkSavedStmt->get_result();
                $isRecipeSaved = $savedResult->num_rows > 0;

                echo "<section class='save-recipe'>";
                if ($isRecipeSaved) {
                    echo "<button class='saved' onclick='removeRecipe(" . $recipeId . ", " . $_SESSION['userId'] . ")'>Remove Recipe</button>";
                } else {
                    echo "<button onclick='saveRecipe(" . $recipeId . ", " . $_SESSION['userId'] . ")'>Save Recipe</button>";
                }
                echo "</section>";

                echo "<section class='recipe-chef'>";
                echo "<img src='{$row['picture']}' alt='{$row['chefId']}'>";
                echo "<p>{$row['username']}</p>";
                echo "</section>";

                echo "<section class='recipe-notes'>";
                echo "<h2>Chef's Notes</h2>";
                echo "<p>{$row['note']}</p>";
                echo "</section>";

                echo "<section class='recipe-details-section'>";
                echo "<h2>Details</h2>";
                echo "<ul>";
                echo "<p>{$row['details']}</p>";
                echo "</ul>";
                echo "</section>";

                echo "<section class='recipe-ingredients'>";
                echo "<h2>Ingredients</h2>";
                echo "<p>{$row['ingredients']}</p>";
                echo "</section>";

                echo "<section class='recipe-directions'>";
                echo "<h2><b>Directions</b></h2>";
                echo "<p>{$row['instruction']}</p>";
                echo "</section>";

                $stmt->close();
            }
        ?>

        <section class="recipe-comments">
            <h2>Comments</h2>

                <?php
                    if (isset($_GET['recipeId'])) {
                    $recipeId = intval($_GET['recipeId']); // Ensure it's an integer
                } else {
                    die("Recipe ID is missing!");
                }

                $sql = "SELECT users.profile_pic, users.username, comment.commentContent, comment.commentDatetime 
                        FROM comment 
                        JOIN users ON comment.userId = users.userId
                        WHERE comment.recipeId = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $recipeId);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo "<div>";
                    echo "<img src={$row['profile_pic']} alt='User'>";
                    echo "<p class='user-name'>{$row['username']}</p>";
                    echo "<p>{$row['commentDatetime']}</p>";
                    echo "<p class='user-comment'>{$row['commentContent']}</p>";
                    echo "</div>";
                }
            ?>
        </section>

        <section class="comment-form">
            <form action="submit_comment.php" method="POST">
                <input type="hidden" name="recipeId" value="<?php echo htmlspecialchars($_GET['recipeId']); ?>">
                <input type="hidden" name="userId" value="<?php echo $_SESSION['userId']; ?>"> 
                <textarea name="commentContent" placeholder="Write your comment here..." required></textarea>
                <button type="submit">Post</button>
            </form>
        </section>
    </main>
</body>

</html>