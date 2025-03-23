<?php
session_start();

// Redirect if customer is not logged in
if (!isset($_SESSION['customer_data'])) {
    header("Location: customer_login.php");
    exit();
}

// Load customer data
$customer = $_SESSION['customer_data'];

// Include the database connection
require 'db.php';

// Fetch all categories
$categories_query = "SELECT * FROM categories";
$categories_result = $conn->query($categories_query);

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f4f7f6;
        font-family: Arial, sans-serif;
    }

    .navbar {
        background-color: #28a745;
    }

    .navbar-brand,
    .navbar-nav .nav-link {
        color: white;
    }

    .category-card {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        background-color: white;
        transition: transform 0.3s ease;
    }

    .category-card:hover {
        transform: scale(1.05);
    }

    .category-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .category-card-body {
        padding: 15px;
        text-align: center;
    }

    .footer {
        background-color: #28a745;
        color: white;
        padding: 20px 0;
        text-align: center;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="cust_dashboard.php">Natural Farming</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="view_category.php">View Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="view_products.php">View Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="orders.php">Order</a></li>
                    <li class="nav-item"><a class="nav-link" href="track_order.php">Track Order</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h3 class="text-center">Browse Categories</h3>
        <div class="row">
            <?php while ($category = $categories_result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="category-card">
                    <img src="uploads/<?php echo $category['category_image']; ?>"
                        alt="<?php echo $category['category_name']; ?>">
                    <div class="category-card-body">
                        <h5><?php echo $category['category_name']; ?></h5>
                        <a href="view_subcategories.php?category_id=<?php echo $category['id']; ?>"
                            class="btn btn-success btn-sm">View Subcategories</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Natural Farming Marketplace. All Rights Reserved.</p>
    </footer>
</body>

</html>