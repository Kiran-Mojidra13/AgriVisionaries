<?php
include 'config.php';

if (isset($_GET['id'])) {
    $farmer_id = $_GET['id'];

    // Update farmer status to approved (1 means verified)
    $query = "UPDATE farmers SET is_verified = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$farmer_id]);

    if ($stmt->rowCount() > 0) {
        header("Location: farmers.php?success=Farmer Approved Successfully");
    } else {
        header("Location: farmers.php?error=Failed to Approve Farmer");
    }
} else {
    header("Location: farmers.php");
}

exit();
?>