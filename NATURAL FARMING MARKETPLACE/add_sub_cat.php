<?php
session_start();

// Check if farmer is logged in
if (!isset($_SESSION['farmer_data'])) {
    header("Location: farmer_login.php");
    exit();
}

// Include the database connection
require 'db.php';

// Fetch categories from the database for the dropdown
$query = "SELECT * FROM categories";
$result = $conn->query($query);
$categories = $result->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['category_id'];
    $sub_category_name = $_POST['sub_category_name'];

    // Handle file upload
    if ($_FILES['sub_category_image']['name']) {
        $image_name = $_FILES['sub_category_image']['name'];
        $image_tmp = $_FILES['sub_category_image']['tmp_name'];
        $image_path = 'uploads/' . $image_name;

        if (move_uploaded_file($image_tmp, $image_path)) {
            // Insert the subcategory data into the database
            $insert_query = "INSERT INTO subcategories (category_id, sub_category_name, sub_category_image) 
                             VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("iss", $category_id, $sub_category_name, $image_name);
            $stmt->execute();

            // Redirect to avoid form resubmission
            header("Location: add_sub_cat.php");
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
    <title>Add Subcategory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Add Subcategory</h2>
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
                <label for="sub_category_name" class="form-label">Subcategory Name</label>
                <input type="text" class="form-control" id="sub_category_name" name="sub_category_name" required>
            </div>
            <div class="mb-3">
                <label for="sub_category_image" class="form-label">Subcategory Image</label>
                <input type="file" class="form-control" id="sub_category_image" name="sub_category_image" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Subcategory</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
