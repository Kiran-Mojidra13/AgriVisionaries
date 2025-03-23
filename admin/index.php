<?php
include 'config.php';
include './includes/header.php';
include './includes/sidebar.php';

// Fetch total farmers
$farmersQuery = "SELECT COUNT(*) AS total_farmers FROM farmers";
$farmersStmt = $conn->prepare($farmersQuery);
$farmersStmt->execute();
$totalFarmers = $farmersStmt->fetch(PDO::FETCH_ASSOC)['total_farmers'];

// Fetch total products
$productsQuery = "SELECT COUNT(*) AS total_products FROM products";
$productsStmt = $conn->prepare($productsQuery);
$productsStmt->execute();
$totalProducts = $productsStmt->fetch(PDO::FETCH_ASSOC)['total_products'];

// Fetch total orders
$ordersQuery = "SELECT COUNT(*) AS total_orders FROM orders";
$ordersStmt = $conn->prepare($ordersQuery);
$ordersStmt->execute();
$totalOrders = $ordersStmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

// Fetch pending approvals (Farmers who are not verified)
$pendingFarmersQuery = "SELECT COUNT(*) AS pending_approvals FROM farmers WHERE is_verified = 0";
$pendingFarmersStmt = $conn->prepare($pendingFarmersQuery);
$pendingFarmersStmt->execute();
$pendingApprovals = $pendingFarmersStmt->fetch(PDO::FETCH_ASSOC)['pending_approvals'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

</head>

<body>

    <!-- Main Content -->
    <main class="col-md-10 ms-sm-auto px-md-4">
        <div class="container mt-4">
            <h2 class="mb-4">Dashboard</h2>

            <div class="row">
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3 shadow">
                        <div class="card-body">
                            <h5 class="card-title">Total Farmers</h5>
                            <h3 class="text-center"><?php echo $totalFarmers; ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3 shadow">
                        <div class="card-body">
                            <h5 class="card-title">Total Products</h5>
                            <h3 class="text-center"><?php echo $totalProducts; ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-warning mb-3 shadow">
                        <div class="card-body">
                            <h5 class="card-title">Total Orders</h5>
                            <h3 class="text-center"><?php echo $totalOrders; ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-danger mb-3 shadow">
                        <div class="card-body">
                            <h5 class="card-title">Pending Approvals</h5>
                            <h3 class="text-center"><?php echo $pendingApprovals; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </div>
    </div>

    <!-- Footer
    <footer class="footer bg-dark text-white text-center py-3">
        &copy; 2025 AgriVisionaries. All Rights Reserved.
    </footer>-->

    <?php include './includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>