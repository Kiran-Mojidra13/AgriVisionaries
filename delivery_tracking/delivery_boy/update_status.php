<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Include the database connection file
require_once '../includes/db.php'; // Ensure this path is correct

// Get the logged-in delivery boy's ID from session
$delivery_boy_id = $_SESSION['delivery_boy_id'];

// Fetch the orders assigned to the logged-in delivery boy
$stmt = $pdo->prepare("SELECT o.*, da.delivery_status 
                       FROM orders o
                       JOIN delivery_assignments da ON o.order_id = da.order_id
                       WHERE da.delivery_boy_id = ? AND da.delivery_status != 'Delivered'");
$stmt->execute([$delivery_boy_id]);

$orders = $stmt->fetchAll();

// Handle the status update when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $status = $_POST['status'];
    $order_id = $_POST['order_id'];

    // Update the delivery status in the database
    $updateStmt = $pdo->prepare("UPDATE delivery_assignments SET delivery_status = ? WHERE order_id = ? AND delivery_boy_id = ?");
    $updateStmt->execute([$status, $order_id, $delivery_boy_id]);

    // Also update the order status
    $orderStatus = ($status == 'Delivered') ? 'Delivered' : 'Shipped'; // You can add more conditions if needed
    $updateOrderStmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $updateOrderStmt->execute([$orderStatus, $order_id]);

    echo "<script>alert('Status updated successfully!'); window.location.href = 'order_details.php';</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Delivery Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Update Delivery Status</h2>
        
        <!-- Displaying the list of orders -->
        <?php if (count($orders) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer ID</th>
                        <th>Order Status</th>
                        <th>Current Delivery Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo $order['customer_id']; ?></td>
                            <td><?php echo $order['order_status']; ?></td>
                            <td><?php echo $order['delivery_status']; ?></td>
                            <td>
                                <!-- Only show the button if the order is not delivered -->
                                <?php if ($order['delivery_status'] != 'Delivered'): ?>
                                    <form method="POST">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <div class="mb-3">
                                            <label for="status_<?php echo $order['order_id']; ?>" class="form-label">Update Status</label>
                                            <select class="form-control" id="status_<?php echo $order['order_id']; ?>" name="status" required>
                                                <option value="In Transit">In Transit</option>
                                                <option value="Delivered">Delivered</option>
                                                <option value="Returned">Returned</option>
                                                <option value="Failed Delivery">Failed Delivery</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update Status</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-success">Delivered</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No orders available for delivery.</p>
        <?php endif; ?>
    </div>
</body>
</html>
