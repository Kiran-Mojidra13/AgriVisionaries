<?php
session_start();

// Check if farmer is logged in
if (!isset($_SESSION['farmer_data'])) {
    header("Location: farmer_login.php");
    exit();
}

// Include the database connection
require 'db.php';

// Handle form submission for stock update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $stock = $_POST['stock'];

    // Update the product stock in the database
    $update_query = "UPDATE products SET stock = stock + ? WHERE product_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $stock, $product_id);
    $stmt->execute();

    // Redirect to the farmer dashboard
    header("Location: farmer_dashboard.php");
    exit();
}

// Fetch products from the database to display
$query = "SELECT * FROM products";
$result = $conn->query($query);
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product Stock</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Update Product Stock</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="product_id" class="form-label">Select Product</label>
                <select class="form-select" id="product_id" name="product_id" required>
                    <option value="">Select a Product</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?> - Current Stock: <?php echo $product['stock']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Increase Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Stock</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
