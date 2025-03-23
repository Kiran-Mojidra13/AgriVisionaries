<?php
session_start();

// Assuming delivery_boy_id is stored in the session when the delivery boy logs in
if (!isset($_SESSION['delivery_boy_id'])) {
    echo "You are not logged in!";
    exit;
}

// Adjusted the path to include the db.php file correctly
require_once '../includes/db.php';

// Get the delivery_boy_id from session
$delivery_boy_id = $_SESSION['delivery_boy_id'];

// Fetch tracking information from delivery_status based on the delivery_boy_id
$stmt = $pdo->prepare("SELECT * FROM delivery_status WHERE delivery_boy_id = ? ORDER BY status_time DESC");
$stmt->execute([$delivery_boy_id]);

// Fetch the latest tracking record for the delivery boy
$tracking_data = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated location from the form
    $new_location = $_POST['location'];

    // Update the delivery status and location in the database
    $stmt = $pdo->prepare("UPDATE delivery_status SET location = ?, status_time = NOW() WHERE status_id = ?");
    $stmt->execute([$new_location, $tracking_data['status_id']]);

    // Notify the user
    echo "<script>alert('Location updated successfully!'); window.location.href = 'tracking.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Tracking Information</h2>
        
        <?php if ($tracking_data): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Order #<?php echo htmlspecialchars($tracking_data['order_id']); ?></h5>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($tracking_data['status']); ?></p>
                    <p><strong>Location:</strong></p>
                    <!-- Location input field -->
                    <form method="POST">
                        <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($tracking_data['location']); ?>" required>
                        <button type="submit" class="btn btn-primary mt-3">Update Location</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p>No tracking data available for this delivery boy.</p>
        <?php endif; ?>
    </div>
</body>
</html>
