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
    <title>Submitted Recipes</title>
    <link rel="stylesheet" href="C.chef_pending_recipe.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .recipe-row {
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .recipe-row:hover {
            background-color: #f0f0f0;
        }
    </style>
    <script>
        function redirectToDetails(recipeId) {
            if (recipeId) {
                window.location.href = "C.pending_recipe_details.php?id=" + encodeURIComponent(recipeId);
            } else {
                alert("Invalid recipe ID.");
            }
        }
    </script>
</head>
<body>
<header>
    <img class="logo" src="assets/brand_logo.svg" alt="logo">
    <a href="GRUC.dashboard.php" class="home-link">Home</a>
    <nav>
        <ul class="navigation_link">
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
    <main class="page">
        <div class="container">
            <?php
            // Database connection
            $conn = new mysqli("localhost", "root", "", "the_overcooked_db");

            if ($conn->connect_error) {
                die("Database connection failed: " . $conn->connect_error);
            }

            // Get chef ID from the query parameter
            $chefId = isset($_GET['chefId']) ? intval($_GET['chefId']) : 0;

            if ($chefId <= 0) {
                echo "<p>Invalid chef ID provided.</p>";
                $conn->close();
                exit;
            }

            // Fetch submitted recipes by the chef
            $sql = "SELECT * FROM pending_recipe WHERE chefId = ? ORDER BY submitted_at DESC";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo "<p>Failed to prepare the query.</p>";
                $conn->close();
                exit;
            }

            $stmt->bind_param("i", $chefId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table class='recipe-table'>";
                echo "<thead>
                        <tr>
                            <th>Recipe Name</th>
                            <th>Category</th>
                            <th>Tag</th>
                            <th>Status</th>
                            <th>Rejection Reason</th>
                            <th>Date Submitted</th>
                        </tr>
                    </thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    $recipeId = htmlspecialchars($row['pendingId']);
                    echo "<tr class='recipe-row' onclick='redirectToDetails(\"$recipeId\")'>
                            <td>" . htmlspecialchars($row['recipeName']) . "</td>
                            <td>" . htmlspecialchars($row['category']) . "</td>
                            <td>" . htmlspecialchars($row['tag']) . "</td>
                            <td>" . htmlspecialchars($row['status']) . "</td>
                            <td>" . htmlspecialchars($row['rejection_reason'] ?? 'N/A') . "</td>
                            <td>" . htmlspecialchars($row['submitted_at']) . "</td>
                        </tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>No submitted recipes found for this chef.</p>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </main>
</body>
</html>