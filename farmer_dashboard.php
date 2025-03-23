<?php
session_start();

// Redirect if farmer is not logged in
if (!isset($_SESSION['farmer_data'])) {
    header("Location: farmer_login.php");
    exit();
}

// Set default language if not set
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'english'; // Default language is English
}

// Load language file based on session language
$langFile = "lang/" . $_SESSION['language'] . ".php";

// Check if the language file exists before including it
if (file_exists($langFile)) {
    $texts = include($langFile);
} else {
    // If the language file doesn't exist, fall back to English
    $_SESSION['language'] = 'english';
    $texts = include("lang/english.php");
}

// Load Farmer Data
$farmer = $_SESSION['farmer_data'];

// Include the database connection
require 'db.php';

// Fetch categories from the database
$query = "SELECT * FROM categories";
$result = $conn->query($query);
$categories = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="<?php echo ($_SESSION['language'] == 'gujarati') ? 'gu' : 'en'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texts['dashboard']; ?> | Natural Farming Marketplace</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
    /* Custom CSS styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f7f6;
        margin: 0;
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

    .dashboard-container {
        margin: 20px auto;
        padding: 20px;
        max-width: 1200px;
        background: white;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Full-Height Carousel */
    .carousel {
        width: 100%;
        height: 100vh;
    }

    .carousel img {
        width: 100%;
        height: 100vh;
        object-fit: cover;
    }

    /* Category Card Styling */
    .category-card {
        width: 100%;
        height: 300px;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        position: relative;
    }

    .category-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease-in-out;
    }

    .category-card .card-body {
        padding: 15px;
        text-align: center;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.5);
        color: white;
    }

    .category-card .card-title {
        font-weight: bold;
        font-size: 1.2rem;
    }

    .category-card:hover img {
        transform: scale(1.1);
    }

    .category-card:hover {
        box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
    }

    /* Footer Styling */
    .footer {
        background-color: #28a745;
        color: white;
        padding: 20px 0;
        text-align: center;
        position: relative;
        bottom: 0;
        width: 100%;
    }

    .footer a {
        color: white;
        text-decoration: none;
    }

    .footer a:hover {
        text-decoration: underline;
    }

    .footer .social-icons i {
        font-size: 24px;
        margin: 0 10px;
    }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="farmer_dashboard.php">🚜 <?php echo $texts['farmer_dashboard']; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home"></i>
                            <?php echo $texts['home']; ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="add_product.php"><i class="fas fa-plus-circle"></i>
                            <?php echo $texts['add_product']; ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="subcategory_add.php"><i class="fas fa-list"></i>
                            <?php echo $texts['add_sub_category']; ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="farmer_orders.php"><i class="fas fa-box"></i>
                            <?php echo $texts['view_orders']; ?></a></li>
                    <!-- <li class="nav-item"><a class="nav-link" href="cust_review.php"><i class="fas fa-star"></i>
                            <?php echo $texts['view_reviews']; ?></a></li> -->

                    <!-- <li class="nav-item"><a class="nav-link" href="my_products.php"><i class="fas fa-cogs"></i> <?php echo $texts['my_products']; ?></a></li> -->
                </ul>
                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center px-2 me-2" href="#"
                            id="profileDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <span><?php echo htmlspecialchars($farmer['full_name']); ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="update_profile.php"><i class="fas fa-user-edit"></i>
                                    <?php echo $texts['update_profile']; ?></a></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i
                                        class="fas fa-sign-out-alt"></i> <?php echo $texts['logout']; ?></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Full-Screen Carousel -->
    <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/farm1.jpg" class="d-block w-100" alt="Farm Image 1">
            </div>
            <div class="carousel-item">
                <img src="images/farm2.jpg" class="d-block w-100" alt="Farm Image 2">
            </div>
            <div class="carousel-item">
                <a href="add_sub_category.php">
                    <img src="images/farm3.jpg" class="d-block w-100" alt="Add Sub Category">
                </a>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Display Categories -->
    <div class="container mt-5">
        <h3 class="text-center mb-4"><?php echo $texts['categories']; ?></h3>
        <div class="row">
            <?php foreach ($categories as $category): ?>
            <div class="col-md-4 mb-4">
                <div class="category-card">
                    <!-- Category Image -->
                    <img src="uploads/<?php echo htmlspecialchars($category['category_image']); ?>"
                        alt="<?php echo htmlspecialchars($category['category_name']); ?>">
                    <div class="card-body">
                        <!-- Category Name -->
                        <h5 class="card-title"><?php echo htmlspecialchars($category['category_name']); ?></h5>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Natural Farming Marketplace. All Rights Reserved.</p>
            <p>
                <a href="privacy_policy.php"><?php echo $texts['privacy_policy']; ?></a> |
                <a href="terms_of_service.php"><?php echo $texts['terms_of_service']; ?></a>
            </p>
            <div class="social-icons">
                <a href="#" class="text-white"><i class="fab fa-facebook-square"></i></a>
                <a href="#" class="text-white"><i class="fab fa-twitter-square"></i></a>
                <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>