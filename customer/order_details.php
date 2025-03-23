<?php
session_start();
require_once 'db.php'; // Ensure this is correct based on your directory structure

// Check if the customer is logged in
if (!isset($_SESSION['customer_data'])) {
    header("Location: customer_login.php");
    exit();
}

$customer = $_SESSION['customer_data'];

// Fetch orders from the `my_orders` table
$query = "SELECT * FROM my_orders WHERE customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer['customer_id']);
$stmt->execute();
$order_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
</head>

<body>
    <h2>Your Orders</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
        <?php while ($order = $order_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
            <td>₹<?php echo number_format($order['product_price'], 2); ?></td>
            <td><?php echo $order['quantity']; ?></td>
            <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
            <td><?php echo htmlspecialchars($order['status']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h3>Product Reviews & Ratings</h3>
    <form action="submit_review.php" method="POST">
        <label for="product_id">Product ID: </label>
        <input type="text" id="product_id" name="product_id" required><br>

        <label for="rating">Rating: </label>
        <input type="number" id="rating" name="rating" min="1" max="5" required><br>

        <label for="review_text">Review: </label><br>
        <textarea id="review_text" name="review_text" required></textarea><br>

        <input type="submit" value="Submit Review">
    </form>
</body>

</html>