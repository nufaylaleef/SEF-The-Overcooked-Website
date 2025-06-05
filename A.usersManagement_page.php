<?php
require 'db_connect.php';

// Fetch Users from Database
$query = "SELECT userId, name, username, password, email, roleId FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="./A.global.css">
    <link rel="stylesheet" href="./A.usersManagement_style.css">
</head>
<body>
    <div class="main-container">
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
                    <li><a href="./A.recipes_page.php">Recipes</a></li>
                    <li><a href="./A.comments_page.php">Comments</a></li>
                    <li><a href="#" class="active">User Accounts</a></li>
                    <li class="divider">System</li>
                    <li><a href="./A.backups_page.php">Backups and Recovery</a></li>
                </ul>
            </nav>
        </aside>

        <div class="content">
            <header class="header-bar">
                <div class="left">
                    <h1>User Management</h1>
                </div>
                <div class="right">
                <form method="POST" action="logout.php" style="display:inline;">
                        <button type="submit" id="logout-button" class="logout-btn">Logout</button>
                    </form>
                    <span class="icon"><a href ="./A.dashboard_page.html">🏠</a></span>
                    <span class="icon"><a href ="./A.adminProfile_page.php">👤</a></span> <!-- Profile Placeholder -->
                </div>
            </header>

            <section class="users-content">
                <h2>Manage Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Email</th>
                            <th>Role ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr id="user-<?php echo $row['userId']; ?>">
                            <td><?php echo $row['userId']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td>
                                <span id="passwordText-<?php echo $row['userId']; ?>">********</span>
                                <button type="button" class="toggle-pass-table" onclick="togglePasswordTable(<?php echo $row['userId']; ?>, '<?php echo $row['password']; ?>')">👁</button>
                            </td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['roleId']; ?></td>
                            <td>
                                <button class="edit-btn" onclick="toggleEditForm(<?php echo $row['userId']; ?>)">Edit</button>
                                <button class="delete-btn" onclick="confirmDelete(<?php echo $row['userId']; ?>)">Delete</button>
                            </td>
                        </tr>
                        <tr id="editRow-<?php echo $row['userId']; ?>" class="edit-row hidden">
                            <td colspan="7">
                                <div class="edit-box">
                                    <form class="edit-form">
                                        <input type="hidden" id="editUserId-<?php echo $row['userId']; ?>" value="<?php echo $row['userId']; ?>">

                                        <label>Full Name:</label>
                                        <input type="text" id="editFullName-<?php echo $row['userId']; ?>" value="<?php echo $row['name']; ?>" required>

                                        <label>Username:</label>
                                        <input type="text" id="editUsername-<?php echo $row['userId']; ?>" value="<?php echo $row['username']; ?>" required>

                                        <label>Password:</label>
                                        <div class="password-field">
                                            <input type="password" id="editPassword-<?php echo $row['userId']; ?>" value="<?php echo $row['password']; ?>" required>
                                            <button type="button" class="toggle-pass" onclick="togglePassword(<?php echo $row['userId']; ?>)">👁</button>
                                        </div>

                                        <label>Email:</label>
                                        <input type="email" id="editEmail-<?php echo $row['userId']; ?>" value="<?php echo $row['email']; ?>" required>

                                        <label>Role ID (1-3):</label>
                                        <input type="number" id="editRole-<?php echo $row['userId']; ?>" value="<?php echo $row['roleId']; ?>" min="1" max="3" required>

                                        <button type="button" class="btn btn-save" onclick="updateUser(<?php echo $row['userId']; ?>)">Save</button>
                                        <button type="button" class="btn btn-cancel" onclick="toggleEditForm(<?php echo $row['userId']; ?>)">Cancel</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>

    <script>
        function toggleEditForm(userId) {
            const editRow = document.getElementById(`editRow-${userId}`);
            document.querySelectorAll('.edit-row').forEach(row => {
                if (row !== editRow) row.classList.add('hidden');
            });
            editRow.classList.toggle('hidden');
        }

        function togglePassword(userId) {
            const passField = document.getElementById(`editPassword-${userId}`);
            passField.type = passField.type === "password" ? "text" : "password";
        }

        function togglePasswordTable(userId, password) {
            const passwordText = document.getElementById(`passwordText-${userId}`);
            if (passwordText.textContent === "********") {
                passwordText.textContent = password;
            } else {
                passwordText.textContent = "********";
            }
        }

        function updateUser(userId) {
            const name = document.getElementById(`editFullName-${userId}`).value;
            const username = document.getElementById(`editUsername-${userId}`).value;
            const password = document.getElementById(`editPassword-${userId}`).value;
            const email = document.getElementById(`editEmail-${userId}`).value;
            const role = document.getElementById(`editRole-${userId}`).value;

            const postData = new URLSearchParams();
            postData.append("userId", userId);
            postData.append("name", name);
            postData.append("username", username);
            postData.append("password", password);
            postData.append("email", email);
            postData.append("roleId", role);

            fetch('A.updateUser.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: postData.toString()
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes("User updated successfully")) {
                    alert("User updated successfully!");
                    location.reload();
                } else {
                    alert("Error updating user: " + data);
                }
            })
            .catch(error => console.error("Error updating user:", error));
        }

        function confirmDelete(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                fetch('A.deleteUser.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `userId=${userId}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data.includes("User deleted successfully")) {
                        alert("User deleted successfully!");
                        document.getElementById(`user-${userId}`).remove();
                        document.getElementById(`editRow-${userId}`).remove();
                    } else {
                        alert("Error deleting user: " + data);
                    }
                })
                .catch(error => console.error("Error deleting user:", error));
            }
        }
    </script>
</body>
</html>
