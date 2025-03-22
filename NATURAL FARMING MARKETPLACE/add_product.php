<?php
session_start();

// Check if farmer is logged in
if (!isset($_SESSION['farmer_data'])) {
    header("Location: farmer_login.php");
    exit();
}

// Include the database connection
require 'db.php';

// Fetch categories from the database for the category dropdown
$categories_query = "SELECT * FROM categories";
$categories_result = $conn->query($categories_query);
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['category_id'];
    $sub_category_id = $_POST['sub_category_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $stock = $_POST['stock'];

    // Handle file upload
    if ($_FILES['product_image']['name']) {
        $image_name = $_FILES['product_image']['name'];
        $image_tmp = $_FILES['product_image']['tmp_name'];
        $image_path = 'uploads/' . $image_name;

        if (move_uploaded_file($image_tmp, $image_path)) {
            // Insert the product data into the database
            $insert_query = "INSERT INTO products (category_id, sub_category_id, product_name, product_image, product_price, stock) 
                             VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("iissdi", $category_id, $sub_category_id, $product_name, $image_name, $product_price, $stock);
            $stmt->execute();

            // Redirect to avoid form resubmission
            header("Location: add_product.php");
            exit();
        } else {
            echo "Error uploading image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Add Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">Select a Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="sub_category_id" class="form-label">Subcategory</label>
                <select class="form-select" id="sub_category_id" name="sub_category_id" required>
                    <option value="">Select a Subcategory</option>
                    <!-- Subcategories will be loaded here dynamically based on the selected category -->
                </select>
            </div>

            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>

            <div class="mb-3">
                <label for="product_price" class="form-label">Product Price (per kg)</label>
                <input type="number" step="0.01" class="form-control" id="product_price" name="product_price" required>
                <small class="form-text text-muted">Price is per kilogram</small>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock (in kg)</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
                <small class="form-text text-muted">Stock is measured in kilograms</small>
            </div>

            <div class="mb-3">
                <label for="product_image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="product_image" name="product_image" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>

    <script>
        // When category is selected, fetch subcategories
        $('#category_id').change(function() {
            var category_id = $(this).val();
            if (category_id) {
                // Send an AJAX request to fetch the subcategories
                $.ajax({
                    type: 'POST',
                    url: 'get_subcategories.php', // PHP file to fetch subcategories
                    data: { category_id: category_id },
                    success: function(data) {
                        $('#sub_category_id').html(data); // Update subcategory dropdown
                    }
                });
            } else {
                $('#sub_category_id').html('<option value="">Select a Subcategory</option>');
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
