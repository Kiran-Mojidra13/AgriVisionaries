<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['customer_data'])) {
    header("Location: customer_login.php");
    exit();
}

// Get customer data
$customer = $_SESSION['customer_data'];

// Fetch product details
$product_id = $_GET['product_id'];
$product_query = "SELECT p.*, f.full_name AS farmer_name, f.address AS farmer_address, f.phone AS farmer_contact
                  FROM products p
                  JOIN farmers f ON p.farmer_id = f.id
                  WHERE p.product_id = ?";

$stmt_product = $conn->prepare($product_query);
$stmt_product->bind_param("i", $product_id);
$stmt_product->execute();
$product_result = $stmt_product->get_result();
$product = $product_result->fetch_assoc();

// Check if product exists
if (!$product) {
    echo "Product not found.";
    exit();
}

$stmt_product->close();

// Handle add to cart action
if (isset($_POST['add_to_cart'])) {
    $quantity = $_POST['quantity'];

    // Check if the product already exists in the cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If the product is already in the cart, update the quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // Redirect to the same page to prevent form resubmission
    header("Location: product_details.php?product_id=" . $product_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .navbar {
            background-color: #28a745;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white;
        }
        .product-details-card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: white;
            margin-bottom: 40px;
        }
        .product-details-card img {
            width: 100%;
            height: auto;
            max-height: 250px;
            object-fit: cover;
            border-radius: 8px;
        }
        .product-details-card-body {
            padding: 20px;
        }
        .product-details-card-body h3 {
            font-weight: bold;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        .product-details-card-body p {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }
        .farmer-info {
            font-size: 1rem;
            margin-top: 20px;
            color: #555;
        }
        .btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #218838;
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="cust_dashboard.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Cart 
                            <span class="badge bg-danger">
                                <?php echo count($_SESSION['cart'] ?? []); ?>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="customer_profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Product Details Section -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="product-details-card">
                    <img src="uploads/<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    <div class="product-details-card-body">
                        <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <p><strong>Price: ₹<?php echo number_format($product['product_price'], 2); ?> per kg</strong></p>
                        <p><strong>Stock Available: <?php echo $product['stock']; ?> kg</strong></p>

                        <!-- Farmer Info -->
                        <div class="farmer-info">
                            <h4>Farmer Information</h4>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($product['farmer_name']); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($product['farmer_address']); ?></p>
                            <p><strong>Contact:</strong> <?php echo htmlspecialchars($product['farmer_contact']); ?></p>
                        </div>

                        <!-- Add to Cart Form -->
                        <form method="post">
                            <div class="d-flex align-items-center">
                                <input type="number" name="quantity" class="form-control me-2" value="1" min="1" max="<?php echo $product['stock']; ?>">
                                <button type="submit" name="add_to_cart" class="btn me-3">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </form>

                        <!-- Action Buttons -->
                        <div class="d-flex mt-3">
                            <a href="buy_now.php?product_id=<?php echo $product['product_id']; ?>" class="btn">Buy Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
