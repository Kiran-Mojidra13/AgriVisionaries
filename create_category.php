<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Category | Natural Farming Marketplace</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff;
        }
        .category-container {
            max-width: 500px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="category-container">
        <h2 class="text-center mb-4">Create New Category</h2>

        <!-- Display success or error messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="cat.php" method="POST" enctype="multipart/form-data">

            <!-- Category Name -->
            <div class="mb-3">
                <label class="form-label">Category Name</label>
                <input type="text" class="form-control" name="category_name" required>
            </div>

            <!-- Category Image -->
            <div class="mb-3">
                <label class="form-label">Category Image</label>
                <input type="file" class="form-control" name="category_image" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Create Category</button>
        </form>
    </div>
</div>

</body>
</html>
