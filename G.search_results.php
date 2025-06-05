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
                <li><a href="G.dashboard.php">Home</a></li>
                <li><a href="G.browse_category.php">Browse by Category</a></li>
                <li><a href="signin_uppage.html">Sign Up</a></li>
            </ul>
        </nav>
        <form class="search-form" action="G.search_results.php" method="GET">
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
                        echo "<a href='G.recipedetails.php?recipeId=" . $recipe['recipeId'] . "' style='text-decoration: none; color: inherit;'>";
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