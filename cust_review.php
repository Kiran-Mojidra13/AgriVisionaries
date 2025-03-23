<?php
session_start();
require_once 'db.php'; // Database connection

// Fetch customer data from session (optional for non-logged-in users)
$customer = isset($_SESSION['customer_data']) ? $_SESSION['customer_data'] : null;

// Fetch products for which the customer has placed orders
$order_query = "SELECT o.product_id, o.product_name
                FROM my_orders o
                WHERE o.customer_id = ? ORDER BY o.order_date DESC";

$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $customer['customer_id']);
$stmt->execute();
$order_result = $stmt->get_result();

$products = [];
if ($order_result->num_rows > 0) {
    while ($order = $order_result->fetch_assoc()) {
        $products[] = $order;
    }
} else {
    $products = [];
}

// Handle new review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    // Insert or update review in the database
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
    <title>Customer Reviews</title>
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

    <h2>Customer Reviews</h2>

    <?php if (empty($products)): ?>
    <p class="no-orders" style="text-align: center;">You have not purchased any products yet.</p>
    <?php else: ?>
    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td>
                        <?php 
                            // Check if a review exists for the product
                            $review_query = "SELECT rating, review_text FROM ratings_reviews WHERE product_id = ? AND customer_id = ?";
                            $stmt_review = $conn->prepare($review_query);
                            $stmt_review->bind_param("ii", $product['product_id'], $customer['customer_id']);
                            $stmt_review->execute();
                            $review_result = $stmt_review->get_result();

                            if ($review_result->num_rows > 0) {
                                $review = $review_result->fetch_assoc();
                                echo $review['rating'] . " Stars";
                            } else {
                                echo "Not Rated Yet";
                            }
                        ?>
                    </td>
                    <td>
                        <?php 
                            if ($review_result->num_rows > 0) {
                                echo htmlspecialchars($review['review_text']);
                            } else {
                                echo "No Review Yet";
                            }
                        ?>
                    </td>
                    <td>
                        <!-- Add review form if no review exists for this product -->
                        <?php if ($review_result->num_rows == 0): ?>
                        <form method="POST" action="" class="review-form">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <label for="rating">Rating (1-5): </label>
                            <input type="number" name="rating" min="1" max="5" required>
                            <br>
                            <label for="review_text">Review: </label>
                            <textarea name="review_text" required></textarea>
                            <br>
                            <button type="submit" name="submit_review">Submit</button>
                        </form>
                        <?php endif; ?>
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