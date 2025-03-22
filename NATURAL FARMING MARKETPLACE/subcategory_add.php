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

// Handle form submission for adding a new subcategory
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['category_id'];
    $sub_category_name = $_POST['sub_category_name'];

    // Handle file upload for subcategory image
    if ($_FILES['sub_category_image']['name']) {
        $image_name = $_FILES['sub_category_image']['name'];
        $image_tmp = $_FILES['sub_category_image']['tmp_name'];
        $image_path = 'uploads/' . $image_name;

        if (move_uploaded_file($image_tmp, $image_path)) {
            // Check if the subcategory already exists
            $check_subcategory_query = "SELECT * FROM subcategories WHERE category_id = ? AND sub_category_name = ?";
            $stmt = $conn->prepare($check_subcategory_query);
            $stmt->bind_param("is", $category_id, $sub_category_name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Subcategory already exists
                $error_message = "This subcategory already exists for the selected category.";
            } else {
                // Insert the new subcategory into the database with the image
                $insert_subcategory_query = "INSERT INTO subcategories (category_id, sub_category_name, sub_category_image) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insert_subcategory_query);
                $stmt->bind_param("iss", $category_id, $sub_category_name, $image_name);
                $stmt->execute();

                // Redirect to another page (e.g., the subcategories list page)
                header("Location: farmer_dashboard.php");
                exit();
            }
        } else {
            $error_message = "Error uploading the subcategory image.";
        }
    } else {
        $error_message = "Please upload an image for the subcategory.";
    }
}
?>

<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subcategory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Add Subcategory</h2>

        <!-- Display error message if subcategory already exists -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Form for adding subcategory -->
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

            <!-- Existing Subcategories Dropdown -->
            <div class="mb-3">
                <label for="existing_subcategory" class="form-label">Existing Subcategory (If applicable)</label>
                <select class="form-select" id="existing_subcategory" name="existing_subcategory">
                    <option value="">Select an Existing Subcategory (if applicable)</option>
                    <!-- Subcategories will be dynamically loaded here based on the selected category -->
                </select>
                <small class="form-text text-muted">You can select an existing subcategory or add a new one.</small>
            </div>

            <!-- Add New Subcategory Section -->
            <div class="mb-3">
                <label for="sub_category_name" class="form-label">Add New Subcategory (if not exists above)</label>
                <input type="text" class="form-control" id="sub_category_name" name="sub_category_name" placeholder="Enter new subcategory name" required>
            </div>

            <div class="mb-3">
                <label for="sub_category_image" class="form-label">Subcategory Image</label>
                <input type="file" class="form-control" id="sub_category_image" name="sub_category_image" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Subcategory</button>
        </form>
    </div>

    <script>
        // When category is selected, fetch subcategories
        $('#category_id').change(function() {
            var category_id = $(this).val();
            if (category_id) {
                // Send an AJAX request to fetch the subcategories for the selected category
                $.ajax({
                    type: 'POST',
                    url: 'get_subcategories.php', // PHP file to fetch subcategories
                    data: { category_id: category_id },
                    success: function(data) {
                        $('#existing_subcategory').html(data); // Update subcategory dropdown
                    }
                });
            } else {
                $('#existing_subcategory').html('<option value="">Select an Existing Subcategory (if applicable)</option>');
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
