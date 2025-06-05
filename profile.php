<?php
session_start();

// Redirect to login if no session exists
if (!isset($_SESSION["sessionId"]) || !isset($_SESSION["userId"])) {
    header("Location: signin_uppage.html");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "the_overcooked_db");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Verify if sessionId matches userId
$sessionId = $_SESSION["sessionId"];
$userId = $_SESSION["userId"];

$stmt = $conn->prepare("SELECT userId FROM session WHERE sessionId = ? AND userId = ?");
$stmt->bind_param("si", $sessionId, $userId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    session_destroy(); // Destroy invalid session
    header("Location: signin_uppage.html");
    exit();
}

$stmt->close();

// Fetch user details dynamically from the database
$stmt = $conn->prepare("SELECT profile_pic, name, email, gender, language, country FROM users WHERE userId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($profile_pic, $name, $email, $gender, $language, $country);
$stmt->fetch();

// Debugging: Print fetched data
error_log("Fetched data: profile_pic=$profile_pic, name=$name, email=$email, gender=$gender, language=$language, country=$country");

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>User Profile</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <header>
        <img class="logo" src="assets/brand_logo.svg" alt="logo">
        <a href="GRUC.dashboard.php" class="home-link">Home</a>
        <nav>
            <ul class="navigation_link">
                <li><a href="GRUC.recipe_list_trending.php">Trending Recipes</a></li>
                <li><a href="GRUC.browse_category.php">Browse by Category</a></li>
                <li><a href="profile.php?userId=<?php echo $_SESSION['userId']; ?>">Profile</a></li>
            </ul>
        </nav>
    </header>
    <main class="page">
        <div class="profile-container">
            <div class="profile-image">
                <img id="profile-picture" src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture">
                <label for="image-upload" id="change-picture-label">Change Picture</label>
                <input type="file" id="image-upload" accept="image/*">
            </div>
            <div class="header-details">
                <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
                <button id="edit-button">Edit</button>
            </div>
            <div>
                <a href="RUC.savedrecipes.php"><button id="saved-recipe">Saved Recipe</button></a>
            </div>
            <form id="profile-form">
                <div class="profile-details">
                    <div class="form-group">
                        <label for="full-name">Full Name</label>
                        <input type="text" id="full-name" name="full-name" value="<?php echo htmlspecialchars($name); ?>" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" disabled required>
                            <option value="undisclose" <?php echo ($gender === 'undisclose') ? 'selected' : ''; ?>>Rather Not To Say</option>
                            <option value="male" <?php echo ($gender === 'male') ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo ($gender === 'female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="language">Language</label>
                        <select id="language" name="language" disabled required>
                            <option value="english" <?php echo ($language === 'english') ? 'selected' : ''; ?>>English</option>
                            <option value="french" <?php echo ($language === 'french') ? 'selected' : ''; ?>>French</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <select id="country" name="country" disabled required>
                            <option value="<?php echo htmlspecialchars($country); ?>"><?php echo htmlspecialchars($country); ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" placeholder="Enter new password" readonly>
                            <span class="password-toggle">
                                <i class="fas fa-eye" id="togglePassword"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </form>
            <div>
                <button id="logout-button" class="logout-btn">Logout</button>
            </div>
        </div>
    </main>

    <script src="profile.js"></script>
</body>
</html>