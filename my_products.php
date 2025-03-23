<?php
session_start();

// Redirect if farmer is not logged in
if (!isset($_SESSION['farmer_data'])) {
    header("Location: farmer_login.php");
    exit();
}

// Load Farmer Data
$farmer = $_SESSION['farmer_data'];

// Include the database connection
require 'db.php';

// Fetch products added by the farmer (using the farmer_id column)
$query = "SELECT * FROM products WHERE farmer_id = ?";  // Use farmer_id to filter products
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $farmer['farmer_id']);  // Binding the farmer's ID
$stmt->execute();
$result = $stmt->get_result();

// Check if the query returned any results
if ($result === false) {
    die("Error executing query: " . $conn->error); // Debugging step: output the error message
}

$products = $result->fetch_all(MYSQLI_ASSOC);

// Close statement
$stmt->close();

// Load the language file based on session language
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'english'; // Default to English
$lang_file = "lang/{$language}.php"; // Path to language file

if (file_exists($lang_file)) {
    $texts = include($lang_file); // Include the selected language file
} else {
    // Default to English if the language file doesn't exist
    $texts = include('lang/english.php');
}
?>

<!DOCTYPE html>
<html lang="<?php echo ($language == 'gujarati') ? 'gu' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texts['my_products']; ?> | Natural Farming Marketplace</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom Styles for Theme Consistency -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
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
        .container {
            margin-top: 30px;
        }
        .product-card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            background-color: white;
            transition: transform 0.3s ease;
        }
        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .product-card-body {
            padding: 15px;
            text-align: center;
        }
        .product-card-title {
            font-weight: bold;
            font-size: 1.2rem;
        }
        .product-card-price {
            color: #28a745;
            font-size: 1.1rem;
            margin-top: 10px;
        }
        .product-card-actions {
            display: flex;
            justify-content: space-around;
            margin-top: 15px;
        }
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
            <a class="navbar-brand" href="#"><?php echo $texts['farmer_dashboard']; ?></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="my_orders.php"><?php echo $texts['my_orders']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php"><?php echo $texts['profile']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><?php echo $texts['logout']; ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container">
        <h2><?php echo $texts['my_products']; ?></h2>

        <?php if (count($products) > 0): ?>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="product-card">
                            <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            <div class="product-card-body">
                                <h5 class="product-card-title"><?php echo $product['name']; ?></h5>
                                <p class="product-card-price">₹<?php echo $product['price']; ?></p>
                                <div class="product-card-actions">
                                    <a href="view_product.php?id=<?php echo $product['id']; ?>" class="btn btn-success btn-sm"><?php echo $texts['view_reviews']; ?></a>
                                    <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm"><?php echo $texts['update_product_button']; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p><?php echo $texts['no_products_message']; ?></p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Natural Farming Marketplace. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS & dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
