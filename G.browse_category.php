<!DOCTYPE html>
<head>
    <title>Browse by Category</title>
    <link rel="stylesheet" href="GRUC.browse_category.css">
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
    <main>
        <div class="main-content">
            <h1>What recipe are we going to cook today?</h1>
            <div class="category-buttons">
                <a href="G.recipe_list_breakfast.php"><button>Breakfast</button></a>
                <a href="G.recipe_list_lunch.php"><button>Lunch</button></a>
                <a href="G.recipe_list_dinner.php"><button>Dinner</button></a>
                <a href="G.recipe_list_dessert.php"><button>Dessert</button></a>
                <a href="G.recipe_list_beverage.php"><button>Beverage</button></a>
            </div>
        </div>
    </main>
</body>
</html>

