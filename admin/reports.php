<?php
// session_start();
include 'config.php';
include './includes/header.php';
include './includes/sidebar.php';

try {
    // Fetch sales data using order_items for quantity
    $query = "SELECT products.product_name, 
                     SUM(order_items.quantity) AS total_quantity, 
                     SUM(orders.total_price) AS total_sales 
              FROM orders
              JOIN order_items ON orders.id = order_items.order_id 
              JOIN products ON order_items.product_id = products.id 
              GROUP BY order_items.product_id 
              ORDER BY total_sales DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Sales Reports</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity Sold</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales as $row) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['total_quantity'] ?? '0'); ?></td>
                <td><?php echo htmlspecialchars($row['total_sales'] ?? '0.00'); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>