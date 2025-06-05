<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db_connect.php';

// Fetch Pending Recipes
$pendingSql = "SELECT pendingId, recipeName, chefId, submitted_at,originalId, category, tag, picture, note,details, ingredients, instruction FROM pending_recipe WHERE status = 'pending'";
$pendingResult = $conn->query($pendingSql);

// Fetch Posted Recipes
$postedSql = "SELECT recipeId, recipeName, chefId, datetime_posted FROM posted_recipe";
$postedResult = $conn->query($postedSql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes</title>
    <link rel="stylesheet" href="./A.global.css">
    <link rel="stylesheet" href="./A.recipes_style.css">
</head>
<body>
    <div class="main-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo">
            <a href="./A.dashboard_page.html">
                    <img src="./brand_logo.svg" alt="The OverCooked" class="logo-img">
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="./A.dashboard_page.html">Dashboard</a></li>
                    <li><a href="./A.adminProfile_page.php">Admin Profile</a></li>
                    <li class="divider">Manage</li>
                    <li><a href="#" class="active">Recipes</a></li>
                    <li><a href="./A.comments_page.php">Comments</a></li>
                    <li><a href="./A.usersManagement_page.php">User Accounts</a></li>
                    <li class="divider">System</li>
                    <li><a href="./A.backups_page.php">Backups and Recovery</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="content">
            <!-- Header Bar -->
            <header class="header-bar">
                <div class="left">
                    <h1>Recipes</h1>
                </div>
                <div class="right">
                    <form method="POST" action="logout.php" style="display:inline;">
                        <button type="submit" id="logout-button" class="logout-btn">Logout</button>
                    </form>
                    <span class="icon"><a href ="./A.dashboard_page.html">🏠</a></span>
                    <span class="icon"><a href ="./A.adminProfile_page.php">👤</a></span> <!-- Profile Placeholder -->
                </div>
            </header>

            <!-- Recipes Content -->
            <section class="recipes-content">
                <h2>Pending Recipe Approvals</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Pending ID</th>
                            <th>Chef ID</th>
                            <th>Recipe Name</th>
                            <th>Submitted Date</th>
                            <th>Category</th>
                            <th>Tag</th>
                            <th>Note</th>
                            <th>Details</th>
                            <th>Ingredients</th>
                            <th>Instruction</th>
                            <th>Approval</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($pendingResult->num_rows > 0) {
                            while ($row = $pendingResult->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['pendingId']}</td>
                                    <td>{$row['chefId']}</td>
                                    <td>{$row['recipeName']}</td>
                                    <td>{$row['submitted_at']}</td>
                                    <td>{$row['category']}</td>
                                    <td>{$row['tag']}</td>
                                    <td>{$row['note']}</td>
                                    <td>{$row['details']}</td>
                                    <td>{$row['ingredients']}</td>
                                    <td>{$row['instruction']}</td>
                                    <td>
                                        <form method='POST' action='A.recipes_page.php'>
                                            <input type='hidden' name='recipe_id' value='{$row['pendingId']}'>
                                            <button type='submit' name='action' value='approve' class='approve-btn'>Approve</button>
                                            <button type='submit' name='action' value='reject' class='reject-btn'>Reject</button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No recipes pending approval.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <h2>Posted Recipes</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Recipe ID</th>
                            <th>Recipe Name</th>
                            <th>Posted Date</th>
                            <th>Chef ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($postedResult->num_rows > 0) {
                            while ($row = $postedResult->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['recipeId']}</td>
                                    <td>{$row['recipeName']}</td>
                                    <td>{$row['datetime_posted']}</td>
                                    <td>{$row['chefId']}</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No posted recipes available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>

    <?php
    // Handle Approve/Reject Actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['recipe_id'])) {
        $recipeId = $conn->real_escape_string($_POST['recipe_id']);
        $action = $_POST['action'] === 'approve' ? 1 : 0; // 1 for approved, 0 for rejected

        // Check if originalId is 0
        $originalIdCheckSql = "SELECT originalId FROM pending_recipe WHERE pendingId = '$recipeId'";
        $originalIdResult = $conn->query($originalIdCheckSql);
        $originalIdRow = $originalIdResult->fetch_assoc();
        $originalId = $originalIdRow['originalId'];

        if ($action === 1) {

            if ($originalId == 0) {
                // Insert without originalId
                
            // Move recipe to `posted_recipe` table
                $moveSql = "INSERT INTO posted_recipe (recipeName, chefId, category, tag, picture,note,details,ingredients,instruction)
                SELECT recipeName,chefId,category,tag,picture,note,details,ingredients,instruction
                FROM pending_recipe
                WHERE pendingId = '$recipeId'";
                $deleteSql = "DELETE FROM pending_recipe WHERE pendingId = '$recipeId'";

            } else {
                // Insert with originalId as recipeId
                $deletePostedSql = "DELETE FROM posted_recipe WHERE recipeId = '$originalId'";
                $conn->query($deletePostedSql);
                
                $moveSql = "INSERT INTO posted_recipe (recipeId, recipeName, chefId, category, tag, picture, note, details, ingredients, instruction)
                    SELECT originalId, recipeName, chefId, category, tag, picture, note, details, ingredients, instruction
                    FROM pending_recipe
                    WHERE pendingId = '$recipeId'";
                $deleteSql = "DELETE FROM pending_recipe WHERE pendingId = '$recipeId'";
            }

            if ($conn->query($moveSql) === TRUE && $conn->query($deleteSql) === TRUE) {
                echo "<script>alert('Recipe has been approved and moved to posted recipes!'); window.location.href='A.recipes_page.php';</script>";
            } else {
                echo "<script>alert('Error moving recipe: " . $conn->error . "');</script>";
            }
        } else {
            // Reject recipe with reason
            echo "<script>
                var reason = prompt('Please enter the reason for rejection:');
                if (reason !== null && reason !== '') {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'A.recipes_page.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            alert('Recipe has been rejected!');
                            window.location.href = 'A.recipes_page.php';
                        } else if (xhr.readyState === 4) {
                            alert('Error rejecting recipe: ' + xhr.responseText);
                        }
                    };
                    xhr.send('action=reject&recipe_id={$recipeId}&reason=' + encodeURIComponent(reason));
                }
            </script>";

            if (isset($_POST['reason'])) {
                $reason = $conn->real_escape_string($_POST['reason']);
                $updateSql = "UPDATE pending_recipe SET status = 'rejected', rejection_reason = '$reason' WHERE pendingId = '$recipeId'";
                if ($conn->query($updateSql) !== TRUE) {
                    echo "<script>alert('Error rejecting recipe: " . $conn->error . "');</script>";
                }
            }
        }
    }
    ?>
</body>
</html>
