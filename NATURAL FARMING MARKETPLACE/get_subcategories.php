<?php
require 'db.php';

if (isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];

    // Fetch subcategories based on category_id
    $sub_categories_query = "SELECT * FROM subcategories WHERE category_id = ?";
    $stmt = $conn->prepare($sub_categories_query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $subcategories = $result->fetch_all(MYSQLI_ASSOC);

    // Check if subcategories exist and return the options
    if ($subcategories) {
        echo '<option value="">Select a Subcategory</option>';
        foreach ($subcategories as $subcategory) {
            echo '<option value="' . $subcategory['sub_category_id'] . '">' . $subcategory['sub_category_name'] . '</option>';
        }
    } else {
        echo '<option value="">No Subcategories Available</option>';
    }
}
?>
