<?php

include 'config.php';

/*if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}*/

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $subcategory_id = $_POST['subcategory_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $farmer_id = $_POST['farmer_id'];
    $description = $_POST['description'];

    $image = $_FILES['product_image'];
    $image_name = time() . '_' . basename($image['name']);
    $image_path = '../uploads/' . $image_name;

    if (move_uploaded_file($image['tmp_name'], $image_path)) {
        $stmt = $conn->prepare("INSERT INTO products (subcategory_id, product_name, product_image, price, stock, farmer_id, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$subcategory_id, $product_name, $image_name, $price, $stock, $farmer_id, $description]);

        $_SESSION['success'] = 'Product added successfully!';
    } else {
        $_SESSION['error'] = 'Error uploading product image!';
    }

    header('Location: products.php');
    exit();
}

// Fetch products, subcategories, and farmers
$stmt = $conn->query("SELECT p.*, s.subcategory_name FROM products p JOIN subcategories s ON p.subcategory_id = s.id ORDER BY p.id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sub_stmt = $conn->query("SELECT * FROM subcategories");
$subcategories = $sub_stmt->fetchAll(PDO::FETCH_ASSOC);

$farmer_stmt = $conn->query("SELECT * FROM farmers");
$farmers = $farmer_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include './includes/header.php'; ?>
<div class="container mt-4">
    <h2>Manage Products</h2>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>

    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?> </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?> </div>
    <?php endif; ?>

    <!-- Table Container for Scrollable Table -->
    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Subcategory</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Farmer</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['subcategory_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['price']); ?></td>
                    <td><?php echo htmlspecialchars($product['stock']); ?></td>
                    <td><?php echo htmlspecialchars($product['farmer_id']); ?></td>
                    <td><img src="../uploads/<?php echo htmlspecialchars($product['product_image']); ?>" width="50">
                    </td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $product['id']; ?>"
                            class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_product.php?id=<?php echo $product['id']; ?>"
                            class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="products.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Subcategory</label>
                        <select name="subcategory_id" class="form-control" required>
                            <?php foreach ($subcategories as $sub): ?>
                            <option value="<?php echo $sub['id']; ?>">
                                <?php echo htmlspecialchars($sub['subcategory_name']); ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Product Name</label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Price</label>
                        <input type="number" name="price" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label>Stock</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Farmer</label>
                        <select name="farmer_id" class="form-control" required>
                            <?php foreach ($farmers as $farmer): ?>
                            <option value="<?php echo $farmer['id']; ?>">
                                <?php echo htmlspecialchars($farmer['name']); ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Product Image</label>
                        <input type="file" name="product_image" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include './includes/footer.php'; ?>