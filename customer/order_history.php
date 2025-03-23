<?php
session_start();
require_once 'db.php'; // Database connection
require_once 'vendor/autoload.php'; // Composer autoload

// Redirect if not logged in
if (!isset($_SESSION['customer_data'])) {
    header("Location: customer_login.php");
    exit();
}

// Fetch customer data from session
$customer = $_SESSION['customer_data'];

// Fetch user's orders from `my_orders` table
$order_query = "SELECT o.order_id, o.product_id, o.product_name, o.product_price, o.quantity, o.total_amount, o.order_date
                FROM my_orders o
                WHERE o.customer_id = ? ORDER BY o.order_date DESC";

$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $customer['customer_id']);
$stmt->execute();
$order_result = $stmt->get_result();

// Fetch ratings and reviews for each order
$ratings_reviews = [];
if ($order_result->num_rows > 0) {
    while ($order = $order_result->fetch_assoc()) {
        $product_id = $order['product_id'];
        
        // Get the rating and review for the product
        $review_query = "SELECT r.rating, r.review_text
                         FROM ratings_reviews r
                         WHERE r.product_id = ? AND r.customer_id = ?";

        $stmt_review = $conn->prepare($review_query);
        $stmt_review->bind_param("ii", $product_id, $customer['customer_id']);
        $stmt_review->execute();
        $review_result = $stmt_review->get_result();
        
        $order['rating'] = null;
        $order['review'] = null;

        if ($review_result->num_rows > 0) {
            $review = $review_result->fetch_assoc();
            $order['rating'] = $review['rating'];
            $order['review'] = $review['review_text'];
        }
        
        $ratings_reviews[] = $order;
    }
} else {
    $ratings_reviews = [];
}

// Handle new rating and review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rate_review'])) {
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    // Insert or update rating and review in the database
    $insert_query = "INSERT INTO ratings_reviews (product_id, customer_id, rating, review_text)
                     VALUES (?, ?, ?, ?)
                     ON DUPLICATE KEY UPDATE rating = ?, review_text = ?";
    $stmt_insert = $conn->prepare($insert_query);
    $stmt_insert->bind_param("iiisss", $product_id, $customer['customer_id'], $rating, $review_text, $rating, $review_text);
    $stmt_insert->execute();
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

    .review-form {
        margin-top: 10px;
    }

    .review-form textarea {
        width: 100%;
        height: 80px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    .review-form input[type="number"] {
        width: 80px;
        padding: 5px;
        margin-top: 5px;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    .review-form button {
        padding: 8px 15px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .review-form button:hover {
        background-color: #218838;
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

        .review-form button {
            font-size: 12px;
        }
    }
    </style>
</head>

<body>

    <h2>Your Order History</h2>

    <?php if (empty($ratings_reviews)): ?>
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
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ratings_reviews as $order): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                    <td>₹<?php echo number_format($order['product_price'], 2); ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td><?php echo date('d-m-Y', strtotime($order['order_date'])); ?></td>
                    <td>
                        <?php 
                            if ($order['rating'] !== null) {
                                echo $order['rating'] . " Stars";
                            } else {
                                echo "Not Rated Yet";
                            }
                        ?>
                    </td>
                    <td>
                        <?php 
                            if ($order['review'] !== null) {
                                echo htmlspecialchars($order['review']);
                            } else {
                                echo "No Review Yet";
                            }
                        ?>
                    </td>
                    <td>
                        <!-- Add rating and review form -->
                        <form method="POST" action="" class="review-form">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <input type="hidden" name="product_id" value="<?php echo $order['product_id']; ?>">
                            <label for="rating">Rating (1-5): </label>
                            <input type="number" name="rating" min="1" max="5"
                                value="<?php echo $order['rating'] ?? ''; ?>" required>
                            <br>
                            <label for="review_text">Review: </label>
                            <textarea name="review_text" required><?php echo $order['review'] ?? ''; ?></textarea>
                            <br>
                            <button type="submit" name="rate_review">Submit</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>