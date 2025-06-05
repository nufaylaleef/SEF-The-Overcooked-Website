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
    <title>Search Results - The OverCooked</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="GRUC.recipe_list.css">
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
    </header>

    <section class="recipes-view">
        <h1 class="recipes-title">Search Results</h1>
        <?php if (isset($_GET['q']) && !empty($_GET['q'])): ?>
            <h2>Results for: "<?php echo htmlspecialchars($_GET['q']); ?>"</h2>
        <?php endif; ?>
        
        <div class="recipes-grid">
            <?php
            include 'search.php';
            
            if (isset($_GET['q']) && !empty($_GET['q'])) {
                $searchResults = searchRecipes($_GET['q']);
                
                if (empty($searchResults)) {
                    echo '<div class="no-results">No recipes found matching your search.</div>';
                } else {
                    foreach ($searchResults as $recipe) {
                        echo "<a href='GRUC.recipedetails.php?recipeId=" . $recipe['recipeId'] . "' style='text-decoration: none; color: inherit;'>";
                        echo "<div class='recipe-card'>";
                        echo "<img src='" . $recipe['picture'] . "' alt='" . $recipe['recipeName'] . "'>";
                        echo "<h2 class='recipe-name'>" . $recipe['recipeName'] . "</h2>";
                        echo "<p class='recipe-details'>" . $recipe['category'] . "</p>";
                        echo "<p class='recipe-details'>" . $recipe['tag'] . "</p>";
                        echo "</div>";
                        echo "</a>";
                    }
                }
            }
            ?>
        </div>
    </section>
</body>
</html>