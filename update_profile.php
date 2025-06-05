<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
ob_start();

header('Content-Type: application/json');

session_start();

// Debug information
error_log("POST data received: " . print_r($_POST, true));
error_log("FILES data received: " . print_r($_FILES, true));

if (!isset($_SESSION["userId"])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$userId = $_SESSION["userId"];

try {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "the_overcooked_db");
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Initialize arrays for SQL construction
    $updateFields = [];
    $params = [];
    $types = '';

    // Handle each field
    if (isset($_POST['full-name']) && !empty($_POST['full-name'])) {
        $updateFields[] = "name = ?";
        $params[] = $_POST['full-name'];
        $types .= 's';
    }

    if (isset($_POST['gender']) && !empty($_POST['gender'])) {
        $updateFields[] = "gender = ?";
        $params[] = $_POST['gender'];
        $types .= 's';
    }

    if (isset($_POST['language']) && !empty($_POST['language'])) {
        $updateFields[] = "language = ?";
        $params[] = $_POST['language'];
        $types .= 's';
    }

    if (isset($_POST['country']) && !empty($_POST['country'])) {
        $updateFields[] = "country = ?";
        $params[] = $_POST['country'];
        $types .= 's';
    }

    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $updateFields[] = "email = ?";
        $params[] = $_POST['email'];
        $types .= 's';
    }

    // Handle password without hashing
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $updateFields[] = "password = ?";
        $params[] = $_POST['password']; // Store password as-is
        $types .= 's';
    }

    // Handle profile picture
    if (isset($_FILES['profile-picture']) && $_FILES['profile-picture']['error'] == 0) {
        $targetDir = "uploads/";
        
        // Create uploads directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Generate unique filename
        $profilePicture = $targetDir . uniqid() . '_' . basename($_FILES['profile-picture']['name']);
        
        // Move uploaded file
        if (move_uploaded_file($_FILES['profile-picture']['tmp_name'], $profilePicture)) {
            $updateFields[] = "profile_pic = ?";
            $params[] = $profilePicture;
            $types .= 's';
        } else {
            error_log("Failed to move uploaded file");
        }
    }

    // Only proceed if there are fields to update
    if (!empty($updateFields)) {
        // Construct and execute update query
        $sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE userId = ?";
        $params[] = $userId;
        $types .= 'i';

        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param($types, ...$params);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        // Fetch updated user data for verification
        $verifySQL = "SELECT name, email, gender, language, country, profile_pic FROM users WHERE userId = ?";
        $verifyStmt = $conn->prepare($verifySQL);
        $verifyStmt->bind_param("i", $userId);
        $verifyStmt->execute();
        $result = $verifyStmt->get_result();
        $updatedUser = $result->fetch_assoc();

        // Success response
        echo json_encode([
            "success" => true,
            "message" => "Profile updated successfully",
            "debug" => [
                "received_data" => array_merge($_POST, ['password' => 'HIDDEN']),
                "updated_values" => $updatedUser
            ]
        ]);

        $verifyStmt->close();
    } else {
        echo json_encode([
            "success" => true,
            "message" => "No fields to update",
            "debug" => [
                "received_data" => array_merge($_POST, ['password' => 'HIDDEN'])
            ]
        ]);
    }

} catch (Exception $e) {
    error_log("Error in update_profile.php: " . $e->getMessage());
    echo json_encode([
        "error" => $e->getMessage(),
        "debug" => [
            "received_data" => array_merge($_POST, ['password' => 'HIDDEN'])
        ]
    ]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>