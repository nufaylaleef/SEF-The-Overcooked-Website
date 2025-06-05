<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>The OverCooked</title>
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
            <h1 class="recipes-title">Beverage</h1>
            <div class="recipes-grid">
                <?php 
                    include 'G.fetch_beverage_recipe.php';
                ?>
            </div>
        </section>
    </body>
</html>