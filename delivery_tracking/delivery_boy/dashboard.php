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
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
        <a href="tracking.php" class="btn btn-primary">Start Tracking Deliveries</a>
        <!-- <a href="update_status.php" class="btn btn-info">Update Status</a> -->
        <a href="earnings.php" class="btn btn-success">View Earnings</a>
        <a href="profile.php" class="btn btn-warning">Profile</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>

</html>