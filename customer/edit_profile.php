<?php
session_start();

// Check if customer is logged in
if (!isset($_SESSION['customer_data'])) {
    header("Location: customer_login.php");
    exit();
}

// Load customer data from session
$customer = $_SESSION['customer_data'];
$customer_id = $customer['customer_id']; // Extracting customer ID

// Include database connection
require '../db.php';

// Fetch current customer details from the database
$query = "SELECT * FROM customers WHERE customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$customer_data = $result->fetch_assoc();
$stmt->close();

// Handle form submission for updating the profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $state = trim($_POST['state']);
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pincode']);

    // Validate inputs
    if (empty($full_name) || empty($email) || empty($phone) || empty($address) || empty($city) || empty($pincode)) {
        $error = "All fields except state are required.";
    } else {
        // Update customer details in the database
        $update_query = "UPDATE customers SET full_name=?, email=?, phone=?, address=?, state=?, city=?, pincode=? WHERE customer_id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssssssi", $full_name, $email, $phone, $address, $state, $city, $pincode, $customer_id);

        if ($stmt->execute()) {
            // Update session data
            $_SESSION['customer_data']['full_name'] = $full_name;
            $_SESSION['customer_data']['email'] = $email;
            $_SESSION['customer_data']['phone'] = $phone;
            $_SESSION['customer_data']['address'] = $address;
            $_SESSION['customer_data']['state'] = $state;
            $_SESSION['customer_data']['city'] = $city;
            $_SESSION['customer_data']['pincode'] = $pincode;

            $success = "Profile updated successfully!";
        } else {
            $error = "Failed to update profile.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Natural Farming Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        .container {
            margin-top: 50px;
            max-width: 600px;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .btn-success {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Edit Profile</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="edit_profile.php" method="post">
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" name="full_name" id="full_name" class="form-control" value="<?php echo htmlspecialchars($customer_data['full_name']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($customer_data['email']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($customer_data['phone']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea name="address" id="address" class="form-control" required><?php echo htmlspecialchars($customer_data['address']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="state" class="form-label">State</label>
            <select name="state" id="state" class="form-control">
                <option value="Gujarat" <?php if ($customer_data['state'] == "Gujarat") echo "selected"; ?>>Gujarat</option>
                <option value="Maharashtra" <?php if ($customer_data['state'] == "Maharashtra") echo "selected"; ?>>Maharashtra</option>
                <option value="Rajasthan" <?php if ($customer_data['state'] == "Rajasthan") echo "selected"; ?>>Rajasthan</option>
                <option value="Madhya Pradesh" <?php if ($customer_data['state'] == "Madhya Pradesh") echo "selected"; ?>>Madhya Pradesh</option>
                <option value="Other" <?php if ($customer_data['state'] == "Other") echo "selected"; ?>>Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="city" class="form-label">City</label>
            <input type="text" name="city" id="city" class="form-control" value="<?php echo htmlspecialchars($customer_data['city']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="pincode" class="form-label">Pincode</label>
            <input type="text" name="pincode" id="pincode" class="form-control" value="<?php echo htmlspecialchars($customer_data['pincode']); ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Update Profile</button>
    </form>
</div>

</body>
</html>
