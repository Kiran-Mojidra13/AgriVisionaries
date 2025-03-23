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

// Fetch the category ID from the URL
$category_id = $_GET['category_id'];

// Fetch subcategories for the selected category
$subcategories_query = "SELECT * FROM subcategories WHERE category_id = ?";
$stmt = $conn->prepare($subcategories_query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$subcategories_result = $stmt->get_result();

// Fetch category name for display
$category_query = "SELECT category_name FROM categories WHERE id = ?";
$stmt_category = $conn->prepare($category_query);
$stmt_category->bind_param("i", $category_id);
$stmt_category->execute();
$category_result = $stmt_category->get_result();
$category_name = $category_result->fetch_assoc()['category_name'];

$stmt_category->close();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subcategories | <?php echo $category_name; ?></title>
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

        .category-card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            background-color: white;
            transition: transform 0.3s ease;
            height: 100%;
        }

        .category-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .category-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .category-card img:hover {
            transform: scale(1.1);
        }

        .category-card-body {
            padding: 15px;
            text-align: center;
        }

        .category-card-title {
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .category-card-body a {
            text-decoration: none;
            background-color: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .category-card-body a:hover {
            background-color: #218838;
        }

        .container {
            margin-top: 40px;
        }

        .category-section h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .category-section p {
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

        @media (max-width: 768px) {
            .category-card img {
                height: 200px;
            }

            .category-section h3 {
                font-size: 1.5rem;
            }

            .category-section p {
                font-size: 1rem;
            }
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
        <div class="category-section">
            <h3>Explore Subcategories of <?php echo $category_name; ?></h3>
            <p>Browse through the subcategories below and discover amazing products that fit your needs.</p>
        </div>

        <div class="row">
            <?php while ($subcategory = $subcategories_result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="category-card">
                        <img src="uploads/<?php echo $subcategory['sub_category_image']; ?>" alt="<?php echo $subcategory['sub_category_name']; ?>">
                        <div class="category-card-body">
                            <h5 class="category-card-title"><?php echo $subcategory['sub_category_name']; ?></h5>
                            <a href="view_products.php?subcategory_id=<?php echo $subcategory['sub_category_id']; ?>" class="btn btn-success btn-sm">View Products</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Natural Farming Marketplace. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
