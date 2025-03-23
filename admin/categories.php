<?php
include 'config.php';
include 'includes/header.php';
include 'includes/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];
    $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
    $stmt->execute([$category_name]);
}

$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h2>Manage Categories</h2>
    <form method="POST">
        <label>Category Name</label>
        <input type="text" name="category_name" required>
        <button type="submit">Add Category</button>
    </form>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>
        <?php foreach ($categories as $category) { ?>
        <tr>
            <td><?= $category['id'] ?></td>
            <td><?= $category['category_name'] ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php include 'includes/footer.php'; ?>