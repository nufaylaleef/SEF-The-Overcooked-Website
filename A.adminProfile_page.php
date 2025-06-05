<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="./A.global.css">
    <link rel="stylesheet" href="./A.adminProfile_style.css">
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
                    <li><a href="#" class="active">Admin Profile</a></li>
                    <li class="divider">Manage</li>
                    <li><a href="./A.recipes_page.php">Recipes</a></li>
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
                    <h1>Admin Profile</h1>
                </div>
                <div class="right">
                <form method="POST" action="logout.php" style="display:inline;">
                        <button type="submit" id="logout-button" class="logout-btn">Logout</button>
                    </form>
                    <span class="icon"><a href ="./A.dashboard_page.html">🏠</a></span>
                    <span class="icon"><a href ="./A.adminProfile_page.php">👤</a></span> <!-- Profile Placeholder -->
                </div>
            </header>

            <!-- Admin Profile Content -->
            <?php
            require 'db_connect.php';

            // Fetch admin profile information
            session_start();
            $userId = $_SESSION['userId'];
            $sql = "SELECT name, email, password FROM users WHERE userId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Output data of each row
                $row = $result->fetch_assoc();
                $fullName = $row['name'];
                $email = $row['email'];
            } else {
                echo "0 results";
            }
            $stmt->close();
            $conn->close();
            ?>

            <section class="profile-content">
                <h2>Profile Information</h2>
                <div class="profile-card">
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($fullName); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                </div>

                <h2 id="updateToggle" class="dropdown-toggle">Update Information ▼</h2>
                <form class="profile-form hidden" id="updateForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="fullName">Full Name:</label>
                    <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($fullName); ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password">

                    <button type="submit" class="update-btn">Update Profile</button>
                </form>
            </section>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require 'db_connect.php';

        $updatedFullName = $_POST['fullName'];
        $updatedEmail = $_POST['email'];
        $updatedPassword = $_POST['password'];

        // Update query
        if (!empty($updatedPassword)) {
            $sql = "UPDATE users SET name=?, email=?, password=? WHERE userId=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $updatedFullName, $updatedEmail, $updatedPassword, $userId);
        } else {
            $sql = "UPDATE users SET name=?, email=? WHERE userId=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $updatedFullName, $updatedEmail, $userId);
        }

        if ($stmt->execute() === TRUE) {
            echo "Profile updated successfully";
            echo "<script>window.location.href = window.location.href;</script>";
        } else {
            echo "Error updating profile: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
    ?>

    <script>
        // JavaScript to toggle the dropdown
        const updateToggle = document.getElementById('updateToggle');
        const updateForm = document.getElementById('updateForm');

        updateToggle.addEventListener('click', () => {
            updateForm.classList.toggle('hidden');
            updateToggle.textContent = updateForm.classList.contains('hidden')
                ? 'Update Information ▼'
                : 'Update Information ▲';
        });

        // JavaScript to add confirmation dialog on form submission
        const updateBtn = document.querySelector('.update-btn');
        updateBtn.addEventListener('click', (event) => {
            const confirmation = confirm('Are you sure you want to update your profile?');
            if (!confirmation) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
