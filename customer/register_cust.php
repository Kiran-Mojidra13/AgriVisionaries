<?php
session_start();

// Include the database connection
require 'db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = $_POST['address'];
    $state = $_POST['state']; // This will always be 'Gujarat' by default
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];

    // Validate password confirmation
    if ($password !== $confirm_password) {
        die('Passwords do not match.');
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query to insert customer data
    $query = "INSERT INTO customers (full_name, email, phone, password, address, state, city, pincode) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssss", $full_name, $email, $phone, $hashed_password, $address, $state, $city, $pincode);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to a success page or login page
        header("Location: cust_login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
