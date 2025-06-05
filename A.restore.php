<?php
require 'db_connect.php'; // Database connection

if (!isset($_GET['file'])) {
    die("No backup file specified.");
}

$backupFile = $_GET['file'];
$backupDir = 'backups/';
$fullPath = $backupDir . basename($backupFile);

if (!file_exists($fullPath)) {
    die("Backup file not found.");
}

// Get database name
$dbName = 'the_overcooked_db';

// Disable foreign key checks
$conn->query("SET foreign_key_checks = 0");

// Fetch all tables and delete all data without dropping tables, except for the session table
$tables = $conn->query("SHOW TABLES FROM $dbName");
while ($row = $tables->fetch_array()) {
    $table = $row[0];
    if ($table !== 'session') {
        $conn->query("DELETE FROM `$table`");
    }
}

// Re-enable foreign key checks
$conn->query("SET foreign_key_checks = 1");

// Read SQL backup file and execute only INSERT statements, except for the session table
$sqlContent = file_get_contents($fullPath);
$queries = explode(";\n", $sqlContent);

foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query) && stripos($query, 'INSERT INTO') === 0 && stripos($query, 'INSERT INTO `session`') !== 0) {
        $conn->query($query);
    }
}

$status = ($conn->error) ? 'failed' : 'restored';
header("Location: A.backups_page.php?status=$status");
exit();
?>
