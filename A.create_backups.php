<?php
require 'db_connect.php'; // Database connection

// Backup Directory
$backupDir = 'backups/';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// Database name
$dbName = 'the_overcooked_db';

// Generate backup filename with timestamp
$timestamp = date('Y-m-d_H-i-s');
$backupFile = "$backupDir/backup_$timestamp.sql";

// Check if database has tables and data before creating backup
$result = $conn->query("SHOW TABLES FROM $dbName");
if ($result->num_rows > 0) {
    // Execute MySQL Dump command for the specific database
    $command = "mysqldump -u root -pYOUR_PASSWORD --routines --triggers --events $dbName > $backupFile";
    system($command, $output);
    
    // Append INSERT statements to backup file
    $tables = $conn->query("SHOW TABLES FROM $dbName");
    $backupContent = "";
    while ($row = $tables->fetch_array()) {
        $table = $row[0];
        $rows = $conn->query("SELECT * FROM `$table`", MYSQLI_USE_RESULT);
        while ($data = $rows->fetch_assoc()) {
            $values = array_map(function ($value) use ($conn) {
                return $value === null ? "NULL" : "'" . $conn->real_escape_string($value) . "'";
            }, array_values($data));
            
            $backupContent .= "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
        }
        $rows->close();
    }
    
    // Append data to the SQL file
    file_put_contents($backupFile, "\n" . $backupContent, FILE_APPEND);
    
    $status = $output === 0 ? 'Backup successful' : 'Backup failed';
} else {
    $status = 'No data to backup';
}

// Redirect back with status
header("Location: A.backups_page.php?status=$status");
exit();
?>