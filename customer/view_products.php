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

// Fetch the subcategory ID from the URL
$subcategory_id = $_GET['subcategory_id'];

// Fetch the subcategory details (to display its name or other info if needed)
$subcategory_query = "SELECT sub_category_name FROM subcategories WHERE sub_category_id = ?";
$stmt_subcategory = $conn->prepare($subcategory_query);
$stmt_subcategory->bind_param("i", $subcategory_id);
$stmt_subcategory->execute();
$subcategory_result = $stmt_subcategory->get_result();
$subcategory = $subcategory_result->fetch_assoc();

// Check if subcategory data was fetched correctly
if (!$subcategory) {
    echo "Subcategory not found.";
    exit();
}

// Fetch products for the selected subcategory, along with the farmer's full name
$products_query = "SELECT p.*, f.full_name AS farmer_name 
                   FROM products p 
                   JOIN farmers f ON p.farmer_id = f.id
                   WHERE p.sub_category_id = ? AND p.stock > 0";
$stmt_products = $conn->prepare($products_query);
$stmt_products->bind_param("i", $subcategory_id);
$stmt_products->execute();
$products_result = $stmt_products->get_result();

// Check if any products are found
if ($products_result->num_rows == 0) {
    $no_products_message = "No products available in this subcategory.";
} else {
    $no_products_message = "";
}

$stmt_subcategory->close();
$stmt_products->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products in <?php echo htmlspecialchars($subcategory['sub_category_name']); ?> | Natural Farming Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        .navbar {
            background-color: #28a745;
        }

        .navbar-brand, .navbar-nav .nav-link {
            color: white;
        }

        .navbar-brand:hover, .navbar-nav .nav-link:hover {
            color: #d4edda;
        }

        .product-card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            background-color: white;
            transition: transform 0.3s ease;
            height: 100%;
        }

        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .product-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card img:hover {
            transform: scale(1.1);
        }

        .product-card-body {
            padding: 15px;
            text-align: center;
        }

        .product-card-title {
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .product-card-body a {
            text-decoration: none;
            background-color: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .product-card-body a:hover {
            background-color: #218838;
        }

        .container {
            margin-top: 40px;
        }

        .product-section h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .product-section p {
            font-size: 1.2rem;
            color: #555;
            text-align: center;
            margin-bottom: 40px;
        }

        /* Footer Styles */
        .footer {
            background-color: #28a745;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .footer a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="cust_dashboard.php">
                Natural Farming 🥕
            </a>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container mt-4">
        <div class="product-section">
            <h3>Explore Products in <?php echo htmlspecialchars($subcategory['sub_category_name']); ?></h3>
            <p>Browse through the products uploaded by farmers in this subcategory.</p>
        </div>

        <!-- Display no products message if applicable -->
        <?php if ($no_products_message): ?>
            <div class="alert alert-warning"><?php echo $no_products_message; ?></div>
        <?php else: ?>
            <div class="row">
                <?php while ($product = $products_result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="product-card">
                            <img src="uploads/<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                            <div class="product-card-body">
                                <h5 class="product-card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                                <p>Price: ₹<?php echo number_format($product['product_price'], 2); ?></p>
                                <p>Stock: <?php echo $product['stock']; ?> items</p>
                                <p>Uploaded by: <?php echo htmlspecialchars($product['farmer_name']); ?></p>
                                <a href="product_details.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-success btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Natural Farming Marketplace. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
