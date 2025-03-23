<?php
session_start();
require_once 'db.php'; // Database connection

// Redirect if customer is not logged in
// Commenting out this check since you want to allow access without login
/*
if (!isset($_SESSION['customer_data'])) {
    header("Location: customer_login.php");
    exit();
}
*/

// Fetch customer data from session (optional for non-logged-in users)
$customer = isset($_SESSION['customer_data']) ? $_SESSION['customer_data'] : null;

// Fetch user's orders from `my_orders` table
$order_query = "SELECT o.order_id, o.product_id, o.product_name, o.product_price, o.quantity, o.total_amount, o.order_date
                FROM my_orders o";
if ($customer) {
    $order_query .= " WHERE o.customer_id = ? ORDER BY o.order_date DESC";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("i", $customer['customer_id']);
} else {
    $stmt = $conn->prepare($order_query . " ORDER BY o.order_date DESC");
}

$stmt->execute();
$order_result = $stmt->get_result();

$orders = [];
if ($order_result->num_rows > 0) {
    while ($order = $order_result->fetch_assoc()) {
        $orders[] = $order;
    }
} else {
    $orders = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f4f7f6;
        color: #333;
    }

    h2 {
        text-align: center;
        margin-top: 50px;
        font-size: 2rem;
        color: #28a745;
    }

    .table-container {
        margin-top: 40px;
        max-width: 1000px;
        margin: 0 auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 12px 15px;
        text-align: center;
    }

    th {
        background-color: #28a745;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .no-orders {
        text-align: center;
        font-size: 1.2rem;
        color: #999;
        margin-top: 40px;
    }

    /* Responsive design */
    @media (max-width: 768px) {

        table th,
        table td {
            padding: 8px 10px;
            font-size: 12px;
        }
    }
    </style>
</head>

<body>

    <h2>Your Order History</h2>

    <?php if (empty($orders)): ?>
    <p class="no-orders">You have no orders yet.</p>
    <?php else: ?>
    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                    <td>₹<?php echo number_format($order['product_price'], 2); ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td><?php echo date('d-m-Y', strtotime($order['order_date'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>