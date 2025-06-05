<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION["userId"]) || !isset($_SESSION["roleId"])) {
    header("Location: signin_uppage.html");
    exit();
}

// Assign userId to chefId (since all chefs are also users)
$chefId = $_SESSION['userId'];

// Single dashboard for all users
$dashboardLink = "GRUC.dashboard.php";

// Set profile link dynamically based on role
$profileLink = ($_SESSION["roleId"] == 1) 
    ? "profile.php?userId=" . $_SESSION["userId"]
    : "C.chef_profile.php?userId=" . $_SESSION["userId"];

// Database connection
$conn = new mysqli("localhost", "root", "", "the_overcooked_db");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch chef's published recipes
$sql = "SELECT * FROM posted_recipe WHERE chefId = ? ORDER BY datetime_posted DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $chefId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>My Recipes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="C.chef_own_recipe.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Newsreader:opsz@6..72&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,100..900&display=swap');
    </style>
</head>
<body>
    <header>
        <img class="logo" src="assets/brand_logo.svg" alt="logo">
        <a href="<?php echo $dashboardLink; ?>" class="home-link">Home</a>
        <nav>
            <ul class="navigation_link">
                <li><a href="GRUC.recipe_list_trending.php">Trending Recipes</a></li>
                <li><a href="GRUC.browse_category.php">Browse by Category</a></li>
                <li><a href="<?php echo $profileLink; ?>">Profile</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>My Published Recipes</h1>

        <?php
        if ($result->num_rows > 0) {
            echo "<div class='recipe-grid'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='recipe-card'>
                        <img src='" . htmlspecialchars($row['picture']) . "' alt='" . htmlspecialchars($row['recipeName']) . "' class='recipe-image'>
                        <h3>" . htmlspecialchars($row['recipeName']) . "</h3>
                        <p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>
                        <p><strong>Tag:</strong> " . htmlspecialchars($row['tag']) . "</p>
                        <p><strong>Date Posted:</strong> " . htmlspecialchars($row['datetime_posted']) . "</p>
                        <a href='C.chef_recipe_details.php?id=" . htmlspecialchars($row['recipeId']) . "'><button id='details-button'>View Details</button></a>
                      </div>";
            }
            echo "</div>";
        } else {
            echo "<p>You have not published any recipes yet.</p>";
        }
        ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
