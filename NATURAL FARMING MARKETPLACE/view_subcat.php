<?php
// Include the database connection
require 'db.php';

// Fetch subcategories with their respective category names
$query = "SELECT s.sub_category_id, s.sub_category_name, s.sub_category_image, c.category_name 
          FROM subcategories s 
          JOIN categories c ON s.category_id = c.id";
$result = $conn->query($query);
$subcategories = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Subcategories</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Subcategories</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Subcategory ID</th>
                    <th>Category</th>
                    <th>Subcategory Name</th>
                    <th>Subcategory Image</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subcategories as $subcategory): ?>
                    <tr>
                        <td><?php echo $subcategory['sub_category_id']; ?></td>
                        <td><?php echo $subcategory['category_name']; ?></td>
                        <td><?php echo $subcategory['sub_category_name']; ?></td>
                        <td><img src="uploads/<?php echo $subcategory['sub_category_image']; ?>" width="100" height="100" alt="Subcategory Image"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
