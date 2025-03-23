<?php
session_start();
require 'db.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $category_name = trim($_POST['category_name']);
    
    // Check if an image is uploaded
    if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] == 0) {
        $image = $_FILES['category_image'];
        $image_name = time() . "_" . basename($image['name']);
        $image_tmp = $image['tmp_name'];
        $image_path = 'uploads/' . $image_name;

        // Validate the image (optional)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($image['type'], $allowed_types)) {
            // Move the uploaded image to the 'uploads' folder
            if (move_uploaded_file($image_tmp, $image_path)) {
                // Prepare SQL query to insert category data into the table
                $stmt = $conn->prepare("INSERT INTO categories (category_name, category_image) VALUES (?, ?)");
                $stmt->bind_param("ss", $category_name, $image_name);
                
                if ($stmt->execute()) {
                    // Store the success message in the session
                    $_SESSION['success'] = "Category created successfully!";
                    header("Location: create_category.php"); // Redirect to display the success message
                    exit();
                } else {
                    // Store the error message in the session
                    $_SESSION['error'] = "Failed to create category due to a database issue.";
                    header("Location: create_category.php");
                    exit();
                }
            } else {
                // Error if the image couldn't be uploaded
                $_SESSION['error'] = "Failed to upload the image. Please try again.";
                header("Location: create_category.php");
                exit();
            }
        } else {
            // Error if the image type is invalid
            $_SESSION['error'] = "Invalid image type. Only JPEG, PNG, or GIF allowed.";
            header("Location: create_category.php");
            exit();
        }
    } else {
        // Error if no image is uploaded
        $_SESSION['error'] = "Please upload a category image.";
        header("Location: create_category.php");
        exit();
    }
}
