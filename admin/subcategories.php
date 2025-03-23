<?php
include 'config.php';
include 'includes/header.php';
include 'includes/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['category_id'];
    $subcategory_name = $_POST['subcategory_name'];
    $stmt = $conn->prepare("INSERT INTO subcategories (category_id, subcategory_name) VALUES (?, ?)");
    $stmt->execute([$category_id, $subcategory_name]);
}

$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
$subcategories = $conn->query("SELECT subcategories.*, categories.category_name FROM subcategories INNER JOIN categories ON subcategories.category_id = categories.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h2>Manage Subcategories</h2>
    <form method="POST">
        <label>Category</label>
        <select name="category_id" required>
            <?php foreach ($categories as $category) { ?>
            <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
            <?php } ?>
        </select>
        <label>Subcategory Name</label>
        <input type="text" name="subcategory_name" required>
        <button type="submit">Add Subcategory</button>
    </form>
    <table>
        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Subcategory</th>
        </tr>
        <?php foreach ($subcategories as $subcategory) { ?>
        <tr>
            <td><?= $subcategory['id'] ?></td>
            <td><?= $subcategory['category_name'] ?></td>
            <td><?= $subcategory['subcategory_name'] ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php include 'includes/footer.php'; ?>