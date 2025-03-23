<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Help & FAQs</h2>
        <div>
            <h5>Q: How do I update the status of a delivery?</h5>
            <p>A: You can update the status on the "Update Status" page after logging into your account.</p>
        </div>
        <div>
            <h5>Q: How do I contact customer support?</h5>
            <p>A: You can reach customer support through the "Contact Support" page.</p>
        </div>
    </div>
</body>
</html>
