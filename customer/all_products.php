<?php
session_start();

// Check if customer is logged in
if (!isset($_SESSION['customer_data'])) {
    header("Location: customer_login.php");
    exit();
}

// Load customer data
$customer = $_SESSION['customer_data'];

// Include the database connection
require 'db.php';

// Fetch all products with farmer details
$products_query = "SELECT p.*, f.full_name AS farmer_name 
                   FROM products p 
                   JOIN farmers f ON p.farmer_id = f.id
                   WHERE p.stock > 0";
$products_result = $conn->query($products_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products | Natural Farming Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f9f9f9; color: #333; }
        .navbar { background-color: #28a745; }
        .navbar-brand { color: white; }
        .product-card { box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); border-radius: 10px; background-color: white; transition: 0.3s; height: 100%; }
        .product-card:hover { transform: scale(1.05); }
        .product-card img { width: 100%; height: 250px; object-fit: cover; }
        .footer { background-color: #28a745; color: white; padding: 20px 0; text-align: center; }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="cust_dashboard.php">Natural Farming 🥕</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h3 class="text-center">All Available Products</h3>
        <div class="row">
            <?php while ($product = $products_result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="product-card p-3">
                        <img src="uploads/<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <div class="text-center mt-3">
                            <h5><?php echo htmlspecialchars($product['product_name']); ?></h5>
                            <p>Price: ₹<?php echo number_format($product['product_price'], 2); ?></p>
                            <p>Stock: <?php echo $product['stock']; ?> items</p>
                            <p>Farmer: <?php echo htmlspecialchars($product['farmer_name']); ?></p>
                            <a href="product_details.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-success btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Natural Farming Marketplace. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
