<?php
session_start();

// Check if the delivery_boy_id is set in the session
if (!isset($_SESSION['delivery_boy_id'])) {
    echo "You are not logged in as a delivery boy!";
    exit;  // Stop execution if not logged in
}

// Connect to the database
require_once '../includes/db.php';  // Adjust the path if necessary

// Get the delivery_boy_id from the session
$delivery_boy_id = $_SESSION['delivery_boy_id'];

// Fetch the orders assigned to the logged-in delivery boy
$stmt = $pdo->prepare("SELECT o.* 
                       FROM orders o 
                       INNER JOIN delivery_assignments da 
                       ON o.order_id = da.order_id 
                       WHERE da.delivery_boy_id = ?");
$stmt->execute([$delivery_boy_id]);

$orders = $stmt->fetchAll();

if (!$orders) {
    echo "No orders found for this delivery boy!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Orders Assigned to You</h2>
        
        <?php foreach ($orders as $order): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Order #<?php echo $order['order_id']; ?></h5>
                <p><strong>Customer ID:</strong> <?php echo $order['customer_id']; ?></p>
                <p><strong>Current Status:</strong> <?php echo $order['order_status']; ?></p>

                <?php
                // Check if the order is already delivered or not
                if ($order['order_status'] !== 'Delivered'): 
                ?>
                    <!-- Button to Update Status -->
                    <a href="update_status.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-primary">Update Status</a>
                <?php else: ?>
                    <p class="text-success">This order has already been delivered.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
