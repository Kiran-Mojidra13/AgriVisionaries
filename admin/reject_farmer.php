<?php
include 'config.php';

if (isset($_GET['id'])) {
    $farmer_id = $_GET['id'];

    // Update farmer status to rejected (0 means not verified)
    $query = "UPDATE farmers SET is_verified = 0 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$farmer_id]);

    if ($stmt->rowCount() > 0) {
        header("Location: farmers.php?success=Farmer Rejected Successfully");
    } else {
        header("Location: farmers.php?error=Failed to Reject Farmer");
    }
} else {
    header("Location: farmers.php");
}

exit();
?>