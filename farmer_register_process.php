<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and get form data
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password
    $address = trim($_POST['address']);
    $state = trim($_POST['state']);
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pincode']);
    $certificate_number = trim($_POST['certificate_number']);
    $account_number = trim($_POST['account_number']);
    $ifsc_code = trim($_POST['ifsc_code']);
    $bank_name = trim($_POST['bank_name']); // Get bank name

    // Check if bank name is empty
    if (empty($bank_name)) {
        $_SESSION['error'] = "Bank name is required.";
        header("Location: register_farmer.php");
        exit();
    }

    // Handle the uploaded certificate image (optional, if certificate image is uploaded)
    if (isset($_FILES['certificate_image']) && $_FILES['certificate_image']['error'] == 0) {
        $certificate_image = $_FILES['certificate_image'];
        $upload_dir = 'uploads/';
        
        // Create the uploads directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $certificate_url = $upload_dir . uniqid() . '_' . basename($certificate_image['name']);
        
        // Move uploaded file to the uploads directory
        if (!move_uploaded_file($certificate_image['tmp_name'], $certificate_url)) {
            $_SESSION['error'] = "Failed to upload certificate image. Please try again.";
            header("Location: register_farmer.php");
            exit();
        }
    } else {
        // Set a default value if no certificate image is uploaded (you can change this logic)
        $certificate_url = '';
    }

    // Validate certificate number and full name
    $stmt = $conn->prepare("SELECT * FROM farmer_certificate WHERE certificate_number = ? AND farmer_name = ?");
    $stmt->bind_param("ss", $certificate_number, $full_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Invalid certificate number or name. Please enter correct details.";
        header("Location: register_farmer.php");
        exit();
    }
    $stmt->close();

    // Insert registration data into the database
    $stmt = $conn->prepare("INSERT INTO farmers (full_name, email, password, phone, address, state, city, pincode, certificate_url, bank_name, account_number, ifsc_code) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssssssssss", $full_name, $email, $password, $phone, $address, $state, $city, $pincode, $certificate_url, $bank_name, $account_number, $ifsc_code);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! Welcome, $full_name.";
        header("Location: farmer_login.php"); // Redirect to a success page (you can change this)
        exit();
    } else {
        $_SESSION['error'] = "An error occurred during registration. Please try again.";
        header("Location: register_farmer.php");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
