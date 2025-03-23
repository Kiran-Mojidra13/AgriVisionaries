<?php
require_once 'includes/db.php'; // Adjust as needed

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location = $_POST['location'];
    $order_id = $_POST['order_id'];
    $delivery_boy_id = $_POST['delivery_boy_id'];

    // Fetch the current delivery status
    $stmt = $pdo->prepare("SELECT * FROM `delivery_assignments` WHERE `order_id` = ? AND `delivery_boy_id` = ?");
    $stmt->execute([$order_id, $delivery_boy_id]);
    $assignment = $stmt->fetch();

    if ($assignment) {
        // Based on current status, update the status in delivery_status table
        $status = "In Transit"; // Default status
        if ($assignment['delivery_status'] == 'Assigned') {
            $status = 'Assigned';
        } elseif ($assignment['delivery_status'] == 'Picked Up') {
            $status = 'Picked Up';
        } elseif ($assignment['delivery_status'] == 'Out for Delivery') {
            $status = 'Out for Delivery';
        }

        // Insert the status update into delivery_status table
        $stmt = $pdo->prepare("INSERT INTO `delivery_status` (`order_id`, `delivery_boy_id`, `status`, `location`, `status_time`) 
                               VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$order_id, $delivery_boy_id, $status, $location]);

        echo "Status updated successfully.";
    } else {
        echo "No assignment found for this order.";
    }
} else {
    echo "Invalid request method.";
}
?>
