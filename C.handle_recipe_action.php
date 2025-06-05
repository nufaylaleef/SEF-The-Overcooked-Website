<?php
$conn = new mysqli("localhost", "root", "", "the_overcooked_db");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pendingId = $_POST["recipeId"];
    $action = $_POST["action"];

    // Fetch the pending recipe
    $query = $conn->prepare("SELECT * FROM pending_recipe WHERE pendingId = ?");
    $query->bind_param("i", $pendingId);
    $query->execute();
    $result = $query->get_result();
    $pendingRecipe = $result->fetch_assoc();
    $query->close();

    if (!$pendingRecipe) {
        die(json_encode(["status" => "error", "message" => "Recipe not found in pending list."]));
    }

    if ($action === "approve") {
        // **Update the original recipe in `posted_recipe`**
        $updateStmt = $conn->prepare("UPDATE posted_recipe SET 
            recipeName=?, category=?, tag=?, note=?, details=?, ingredients=?, instruction=?
            WHERE recipeId=?");

        $updateStmt->bind_param("sssssssi",
            $pendingRecipe['recipeName'], $pendingRecipe['category'], 
            $pendingRecipe['tag'], $pendingRecipe['note'], $pendingRecipe['details'], 
            $pendingRecipe['ingredients'], $pendingRecipe['instruction'], $pendingRecipe['originalId']);
        
        if ($updateStmt->execute()) {
            // **Delete the pending edit after approval**
            $deleteStmt = $conn->prepare("DELETE FROM pending_recipe WHERE pendingId=?");
            $deleteStmt->bind_param("i", $pendingId);
            $deleteStmt->execute();
            echo json_encode(["status" => "success", "message" => "Recipe edit approved and updated."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating recipe: " . $updateStmt->error]);
        }
    } elseif ($action === "reject") {
        // **Mark as rejected and store rejection reason**
        $rejectionReason = $_POST["rejectionReason"];

        $stmt = $conn->prepare("UPDATE pending_recipe SET status = 'rejected', rejection_reason = ? WHERE pendingId = ?");
        $stmt->bind_param("si", $rejectionReason, $pendingId);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Recipe rejected successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error rejecting recipe: " . $stmt->error]);
        }
    }
}

$conn->close();
?>
