<?php
include 'config.php';
include 'includes/header.php';
include 'includes/sidebar.php';

// Fetch orders with user details and products using the `order_items` table
$query = "SELECT orders.id, users.name AS user_name, products.product_name, 
                 order_items.quantity, orders.total_price, orders.status 
          FROM orders
          JOIN users ON orders.user_id = users.id
          JOIN order_items ON orders.id = order_items.order_id
          JOIN products ON order_items.product_id = products.id";

$stmt = $conn->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2 class="mb-4">Manage Orders</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order) { ?>
            <tr>
                <td><?php echo htmlspecialchars($order['id']); ?></td>
                <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                <td><?php echo htmlspecialchars($order['total_price']); ?></td>
                <td><?php echo htmlspecialchars($order['status']); ?></td>
                <td>
                    <a href="order_update.php?id=<?php echo $order['id']; ?>&status=Approved"
                        class="btn btn-success btn-sm">Approve</a>
                    <a href="order_update.php?id=<?php echo $order['id']; ?>&status=Rejected"
                        class="btn btn-danger btn-sm">Reject</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>