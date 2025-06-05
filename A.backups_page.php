<?php
require 'db_connect.php'; // Database connection

$backupDir = 'backups/';

// Function to list backups
function getBackupFiles() {
    global $backupDir;
    $files = glob("$backupDir/*.sql");
    return $files ? array_reverse($files) : [];
}

// Include create backups logic
if (isset($_POST['backup'])) {
    require 'A.create_backups.php';
}

// Function to restore a backup with confirmation
if (isset($_POST['restore'])) {
    $backupFile = $_POST['backup_file'];
    echo "<script>
        if (confirm('Are you sure you want to restore this backup? All current data will be replaced.')) {
            window.location.href = 'A.restore.php?file=$backupFile';
        }
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backups & Recovery</title>
    <link rel="stylesheet" href="./A.global.css">
    <link rel="stylesheet" href="./A.backups_style.css">
</head>
<body>
    <div class="main-container">
        <!-- Sidebar -->
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
                    <li><a href="./A.usersManagement_page.php">User Accounts</a></li>
                    <li class="divider">System</li>
                    <li><a href="#" class="active">Backups and Recovery</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Content -->
        <div class="content">
            <header class="header-bar">
                <div class="left">
                    <h1>Backups & Recovery</h1>
                </div>
                <div class="right">
                <form method="POST" action="logout.php" style="display:inline;">
                        <button type="submit" id="logout-button" class="logout-btn">Logout</button>
                    </form>
                    <span class="icon"><a href ="./A.dashboard_page.html">🏠</a></span>
                    <span class="icon"><a href ="./A.adminProfile_page.php">👤</a></span> <!-- Profile Placeholder -->
                </div>
            </header>
            
            <section class="backups-content">
                <div class="backups-actions">
                    <form method="POST">
                        <button type="submit" name="backup" class="update-btn">Create Backup</button>
                    </form>
                </div>
                
                <h2>Available Backups</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Backup File</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $backupFiles = getBackupFiles();
                        if (!empty($backupFiles)) {
                            foreach ($backupFiles as $file): ?>
                                <tr>
                                    <td><?php echo basename($file); ?></td>
                                    <td>
                                        <a href="<?php echo $file; ?>" class="download-btn" download>Download</a>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="backup_file" value="<?php echo $file; ?>">
                                            <button type="submit" name="restore" class="restore-btn" onclick="return confirmRestore('<?php echo $file; ?>')">Restore</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; 
                        } else { ?>
                            <tr><td colspan="2">No backups available.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
    
    <script>
        function confirmBackup() {
            return confirm('Are you sure you want to create a new backup?');
        }

        function confirmRestore(file) {
            if (confirm('Are you sure you want to restore this backup? All current data will be replaced.')) {
                window.location.href = 'A.restore.php?file=' + file;
                return false;
            }
            return false;
        }
    </script>
</body>
</html>
