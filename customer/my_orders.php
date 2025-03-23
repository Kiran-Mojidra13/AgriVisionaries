<?php
session_start();

// Include the database connection
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['customer_data'])) {
    echo "You must be logged in to view your orders.";
    exit(); // Stop the script if the user is not logged in
}

// Get the logged-in customer ID from the session
$customer_id = $_SESSION['customer_data']['customer_id'];

// Fetch orders for the logged-in customer
$query = "SELECT o.order_id, o.order_date, o.order_status, p.product_name, p.product_image, o.product_id
          FROM orders o
          JOIN products p ON o.product_id = p.product_id
          WHERE o.customer_id = ? AND o.order_status = 'Delivered'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if any orders are found
if (mysqli_num_rows($result) > 0) {
    // Display orders
    while ($row = mysqli_fetch_assoc($result)) {
        $order_id = $row['order_id'];
        $order_date = $row['order_date'];
        $order_status = $row['order_status'];
        $product_name = $row['product_name'];
        $product_image = $row['product_image'];
        $product_id = $row['product_id'];

        echo "<div class='order'>
                <img src='images/products/$product_image' alt='$product_name' class='order-img'>
                <h5>$product_name</h5>
                <p>Order Date: $order_date</p>
                <p>Status: $order_status</p>";

        // Fetch reviews for this product if any
        $review_query = "SELECT * FROM reviews WHERE product_id = ? AND customer_id = ?";
        $review_stmt = $conn->prepare($review_query);
        $review_stmt->bind_param("ii", $product_id, $customer_id);
        $review_stmt->execute();
        $review_result = $review_stmt->get_result();

        if (mysqli_num_rows($review_result) > 0) {
            // Display reviews
            while ($review = mysqli_fetch_assoc($review_result)) {
                echo "<div class='review'>
                        <p><strong>Review:</strong> {$review['review_text']}</p>
                        <em>Rating: {$review['rating']} stars</em>
                      </div>";
            }
        } else {
            // If no review, display an option to add a review
            echo "<form method='POST' action='add_review.php'>
                    <textarea name='review_text' placeholder='Write your review here'></textarea>
                    <select name='rating'>
                        <option value='1'>1 Star</option>
                        <option value='2'>2 Stars</option>
                        <option value='3'>3 Stars</option>
                        <option value='4'>4 Stars</option>
                        <option value='5'>5 Stars</option>
                    </select>
                    <input type='hidden' name='order_id' value='$order_id'>
                    <input type='hidden' name='product_id' value='$product_id'>
                    <button type='submit'>Submit Review</button>
                  </form>";
        }

        echo "</div>"; // End of order
    }
} else {
    echo "No delivered orders found.";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!-- Optional: Add the footer section -->
<footer>
    <p>&copy; 2025 Natural Farming Marketplace</p>
</footer>

<!-- CSS Styles -->
<style>
.order {
    margin-bottom: 20px;
    border: 1px solid #ddd;
    padding: 10px;
}

.order img {
    width: 100px;
    height: 100px;
    object-fit: cover;
}

.order h5 {
    font-size: 18px;
}

.order p {
    font-size: 14px;
}

.review {
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 10px;
}

.review p {
    margin: 0;
    font-size: 14px;
}

.review em {
    font-size: 12px;
    color: gray;
}

textarea {
    width: 100%;
    height: 100px;
    margin-bottom: 10px;
}

select {
    margin-bottom: 10px;
}

button {
    padding: 5px 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}
</style>