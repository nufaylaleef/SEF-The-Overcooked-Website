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
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>The OverCooked - Recipe Sharing Platform</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="GRUC.dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
    <main class="page">
        <section class="hero">
            <div class="hero-container">
                <div class="hero-text">
                    <h1>From <i>Kitchens</i> to <i>Community</i>:</h1>
                    <h1>Share and Find Trending Recipes</h1>
                </div>
            </div>
            <div class="button-hero">
                <a class="browse-recipes" href="GRUC.browse_category.php">
                    <button>Browse our recipes</button>
                </a>
            </div>
        </section>
        <section class="recipe-section">
            <div class="recipe-grid">
                <div class="chickpea-grid">
                    <img src="assets/hpgp1.png" alt="Recipe 1">
                    <h3>Chickpea and Tomato Soup —</h3>
                    <p>The warm and hearty soup to keep you full throughout the winter season.</p>
                </div>
                <div class="lasagna-ingredients-grid">
                    <img src="assets/hpgp2.png" alt="Recipe 2">
                    <h3>Lasagna —</h3>
                    <p>A dish made with layers of pasta sheets, rich meat or vegetable sauce, creamy
                        béchamel or ricotta, and melted cheese, baked to golden perfection.</p>
                    <img src="assets/hpgp3.png" alt="Recipe 3">
                    <h3>Beets, Lemon, Heirloom Tomato, and Gluten-Free Bread —</h3>
                    <p>Recipes made from organic and locally harvested ingredients will result in the
                        most amazing meals one has ever consumed.</p>
                </div>
            </div>
        </section>
        <section class="about-us">
            <div class="about-content">
                <h2>WHAT WE BELIEVE</h2>
                <p>We believe in the power of inspiration and collaboration. Recipes are more than just
                    instructions—they're stories, traditions, and creativity shared from one kitchen to
                    another...</p>
            </div>
        </section>
    </main>

    <script>
        document.getElementById("logout-button").addEventListener("click", async function () {
            try {
                const response = await fetch("logout.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" }
                });

                const data = await response.json();
                console.log("Logout Response:", data); // Debugging

                if (data.success) {
                    console.log("Redirecting to:", data.redirect);
                    window.location.href = data.redirect;
                } else {
                    alert("Logout failed. Please try again.");
                }
            } catch (error) {
                console.error("Error:", error);
                alert("An error occurred while logging out.");
            }
        });
    </script>
</body>
</html>
