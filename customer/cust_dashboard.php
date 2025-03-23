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

// Fetch all subcategories for each category (optional: you can query dynamically by category ID if needed)
$subcategories_query = "SELECT * FROM subcategories";
$subcategories_result = $conn->query($subcategories_query);

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Natural Farming | Marketplace</title>

    <!-- Bootstrap 5 for styling and responsiveness -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for profile icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f7f6;
    }

    .navbar {
        background-color: #28a745;
    }

    .navbar-brand,
    .navbar-nav .nav-link {
        color: white;
    }

    .navbar-brand:hover,
    .navbar-nav .nav-link:hover {
        color: #d4edda;
    }

    .category-card {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
        background-color: white;
        transition: transform 0.3s ease;
    }

    .category-card:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
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

    .category-card-title {
        font-weight: bold;
        font-size: 1.2rem;
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

    /* Custom Styles for Profile Dropdown */
    .dropdown-toggle::after {
        display: none !important;
    }

    /* Logo Styling */
    .navbar-brand img {
        width: 40px;
        height: 40px;
        margin-right: 10px;
    }

    /* Spacing between Navbar Items */
    .navbar-nav {
        margin-left: auto;
    }

    /* Styling the Profile Icon */
    .profile-icon {
        font-size: 30px;
    }

    /* Custom Styling for Category Section */
    .category-section {
        text-align: center;
        margin-bottom: 30px;
    }

    .category-section h3 {
        font-size: 2rem;
        color: #333;
        font-weight: 700;
    }

    .category-section p {
        font-size: 1.2rem;
        color: #555;
        margin-bottom: 40px;
    }

    /* Container for Responsive Spacing */
    .container {
        margin-top: 40px;
    }

    /* Responsiveness Adjustments */
    @media (max-width: 767px) {
        .navbar-brand img {
            width: 30px;
            height: 30px;
        }
    }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <!-- Logo and Text -->
            <a class="navbar-brand" href="cust_dashboard.php">
                <!-- Optionally, replace with your custom logo -->
                Natural Farming 🥕
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_cat.php">View Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="all_products.php">View Products</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="orders.php">Order</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="phpChatBot/index.php">FAQs</a>
                    </li>
                    <li class="nav-item dropdown">
                        <!-- Profile Icon Dropdown -->
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- Font Awesome Profile Icon -->
                            <i class="fas fa-user-circle profile-icon"></i> <?php echo $customer['full_name']; ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
                            <li><a class="dropdown-item" href="order_history.php">My Orders</a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container mt-4">
        <!-- Categories Section -->
        <div class="category-section">
            <h3>Browse Categories</h3>
            <p>Explore a wide range of natural farming products by category. Find what suits your needs!</p>
        </div>

        <div class="row">
            <?php while ($category = $categories_result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="category-card">
                    <!-- Corrected image path to refer to 'uploads/' -->
                    <img src="uploads/<?php echo $category['category_image']; ?>"
                        alt="<?php echo $category['category_name']; ?>">
                    <div class="category-card-body">
                        <h5 class="category-card-title"><?php echo $category['category_name']; ?></h5>
                        <a href="view_subcategories.php?category_id=<?php echo $category['id']; ?>"
                            class="btn btn-success btn-sm">View Subcategories</a>
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