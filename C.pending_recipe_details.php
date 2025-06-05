<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION["userId"]) || !isset($_SESSION["roleId"])) {
    header("Location: signin_uppage.html");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "the_overcooked_db");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get recipe ID from URL and validate it
$recipeId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($recipeId <= 0) {
    die("Invalid recipe ID.");
}

// Fetch recipe details
$sql = "SELECT * FROM pending_recipe WHERE pendingId = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL error: " . $conn->error);
}

$stmt->bind_param("i", $recipeId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    die("Recipe not found.");
}

$recipe = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Define profile and dashboard links
$dashboardLink = "GRUC.dashboard.php";
$profileLink = ($_SESSION["roleId"] == 1) 
    ? "profile.php?userId=" . $_SESSION["userId"]
    : "C.chef_profile.php?userId=" . $_SESSION["userId"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Recipe Details</title>
    <link rel="stylesheet" href="C.pending_recipe_details.css">
</head>
<style>
        .recipe-details-image {
            display: block !important;
            width: 300px !important;
            height: 300px !important;
            margin: 20px auto !important;
            border-radius: 8px !important;
            object-fit: cover !important;
        }

        .container {
            max-width: 800px !important;
            margin: 30px auto !important;
            padding: 20px !important;
            background-color: #fff !important;
            border-radius: 8px !important;
            box-shadow: 0 0 10px rgba(0,0,0,0.1) !important;
            overflow: hidden !important;
        }
</style>
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

<div class="container">
    <h2><?php echo htmlspecialchars($recipe['recipeName']); ?></h2>
    <div class="image-wrapper">
        <img src="<?php echo htmlspecialchars($recipe['picture']); ?>" alt="Recipe Image" class="recipe-details-image">
    </div>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($recipe['category']); ?></p>
    <p><strong>Tag:</strong> <?php echo htmlspecialchars($recipe['tag']); ?></p>
    <p><strong>Note:</strong> <?php echo htmlspecialchars($recipe['note']); ?></p>
    <p><strong>Details:</strong> <?php echo htmlspecialchars($recipe['details']); ?></p>
    <p><strong>Ingredients:</strong> <?php echo htmlspecialchars($recipe['ingredients']); ?></p>
    <p><strong>Instructions:</strong> <?php echo htmlspecialchars($recipe['instruction']); ?></p>

    <div class='button-container'>
        <button onclick="document.getElementById('editRecipeForm').style.display = 'block';">Edit</button>
    </div>

    <!-- Edit Recipe Form -->
    <form id="editRecipeForm" style="display: none;" onsubmit="handleEdit(event, <?php echo $recipeId; ?>)">
        <h3>Edit Recipe</h3>
        <input type="hidden" name="pendingId" value="<?php echo $recipeId; ?>">
        
        <label for="recipeName">Recipe Name:</label>
        <input type="text" id="recipeName" name="recipeName" value="<?php echo htmlspecialchars($recipe['recipeName']); ?>" required>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="Breakfast" <?php echo ($recipe['category'] == 'Breakfast' ? 'selected' : ''); ?>>Breakfast</option>
            <option value="Lunch" <?php echo ($recipe['category'] == 'Lunch' ? 'selected' : ''); ?>>Lunch</option>
            <option value="Dinner" <?php echo ($recipe['category'] == 'Dinner' ? 'selected' : ''); ?>>Dinner</option>
            <option value="Dessert" <?php echo ($recipe['category'] == 'Dessert' ? 'selected' : ''); ?>>Dessert</option>
            <option value="Beverages" <?php echo ($recipe['category'] == 'Beverages' ? 'selected' : ''); ?>>Beverages</option>
        </select>

        <label for="tag">Tag:</label>
        <input type="text" id="tag" name="tag" value="<?php echo htmlspecialchars($recipe['tag']); ?>" required>

        <label for="note">Note:</label>
        <textarea id="note" name="note" required><?php echo htmlspecialchars($recipe['note']); ?></textarea>

        <label for="details">Details:</label>
        <textarea id="details" name="details" required><?php echo htmlspecialchars($recipe['details']); ?></textarea>

        <label for="ingredients">Ingredients:</label>
        <textarea id="ingredients" name="ingredients" required><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>

        <label for="instruction">Instructions:</label>
        <textarea id="instruction" name="instruction" required><?php echo htmlspecialchars($recipe['instruction']); ?></textarea>

        <button type="submit">Submit Changes</button>
        <button type="button" onclick="document.getElementById('editRecipeForm').style.display = 'none';">Cancel</button>
    </form>
</div>

<script>
    function showNotification(message) {
        alert(message);
        window.location.href = `C.chef_pending_recipe.php?chefId=<?php echo $_SESSION['userId']; ?>`;
    }

    async function handleEdit(event, recipeId) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('editRecipeForm'));

    try {
        const response = await fetch('./C.edit_pending_recipe.php', { 
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();
        if (result.status === "success") {
            showNotification(result.message);

            // Wait 2 seconds before redirecting (smooth transition)
            setTimeout(() => {
                window.location.href = `C.chef_pending_recipe.php?userId=<?php echo $_SESSION['userId']; ?>`;
            }, 2000);

        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
}

</script>

</body>
</html>
