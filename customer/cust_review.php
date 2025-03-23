<?php
require_once 'db.php'; // Database connection

// Fetch all reviews and ratings from the ratings_reviews table along with the associated product name
$review_query = "SELECT rr.product_id, p.product_name, rr.rating, rr.review_text
                 FROM ratings_reviews rr
                 JOIN products p ON rr.product_id = p.product_id
                 ORDER BY p.product_name ASC";

// Execute the query
$review_result = $conn->query($review_query);

// Check for query error
if ($review_result === false) {
    die("Error in query: " . $conn->error);  // If there's a query error, show the error message
}

// Initialize reviews array
$reviews = [];
if ($review_result->num_rows > 0) {
    while ($review = $review_result->fetch_assoc()) {
        $reviews[] = $review;
    }
} else {
    $reviews = []; // If no reviews, set an empty array
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Reviews</title>
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

    <h2>Product Reviews</h2>

    <?php if (empty($reviews)): ?>
    <p class="no-reviews" style="text-align: center;">No reviews found.</p>
    <?php else: ?>
    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Rating</th>
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?php echo htmlspecialchars($review['product_name']); ?></td>
                    <td><?php echo $review['rating']; ?> Stars</td>
                    <td><?php echo htmlspecialchars($review['review_text']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>