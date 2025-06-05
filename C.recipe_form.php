<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION["userId"]) || !isset($_SESSION["roleId"])) {
    header("Location: signin_uppage.html");
    exit();
}

// Single dashboard for all users
$dashboardLink = "GRUC.dashboard.php";

// Set profile link dynamically based on role
$profileLink = ($_SESSION["roleId"] == 1) 
    ? "profile.php?userId=" . $_SESSION["userId"]
    : "C.chef_profile.php?userId=" . $_SESSION["userId"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Recipe</title>
    <link rel="stylesheet" href="C.recipe_form.css">
</head>
<body>
    <header>
        <img class="logo" src="assets/brand_logo.svg" alt="logo">
        <nav>
            <ul class="navigation_link">
                <li><a href="<?php echo $dashboardLink; ?>">Home</a></li>
                <li><a href="GRUC.recipe_list_trending.php">Trending Recipes</a></li>
                <li><a href="GRUC.browse_category.php">Browse by Category</a></li>
                <li><a href="<?php echo $profileLink; ?>">Profile</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <form id="recipeForm" enctype="multipart/form-data">
            <h2>Submit Your Recipe</h2>
            <label for="recipeName">Recipe Name:</label>
            <input type="text" id="recipeName" name="recipeName" placeholder="Example: Sambal Kentang" required>

            <input type="hidden" id="chefId" name="chefId" value="<?php echo $_SESSION['userId']; ?>">

            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="" disabled selected>Select a category</option>
                <option value="Breakfast">Breakfast</option>
                <option value="Lunch">Lunch</option>
                <option value="Dinner">Dinner</option>
                <option value="Dessert">Dessert</option>
                <option value="Beverages">Beverages</option>
            </select>

            <label for="tag">Tag:</label>
            <input type="text" id="tag" name="tag" placeholder="Example: Indonesian, Vegetarian" required>

            <label for="pictures">Add Pictures:</label>
            <input type="file" id="pictures" name="pictures[]" accept="image/*" multiple required>

            <label for="note">Note:</label>
            <textarea id="note" name="note" placeholder="Example: This is a delicious recipe from the island of Java" required></textarea>

            <label for="details">Details:</label>
            <textarea id="details" name="details" placeholder="Example: Prep time: 10 min, Cook time: 30 min"></textarea>

            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" placeholder="Example: 3 potatoes, 2 bags of smashed chillis" required></textarea>

            <label for="instruction">Instructions:</label>
            <textarea id="instruction" name="instruction" placeholder="Example: 1. Boil potatoes for half an hour. 2. Smash chillis and mix with potatoes." required></textarea>

            <button type="submit">Publish</button>
        </form>
    </main>

    <script>
        document.getElementById("recipeForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission
            
            const formData = new FormData(this);

            fetch("C.submit_recipe_form.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    alert(data.message); // Show success message
                    setTimeout(() => {
                        window.location.href = data.redirect; // Redirect smoothly
                    }, 2000); // Delay for 2 seconds
                } else {
                    alert(data.message); // Show error message
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Something went wrong. Please try again.");
            });
        });
    </script>
</body>
</html>
