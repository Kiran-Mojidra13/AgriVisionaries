<?php
session_start();

// Redirect if farmer is not logged in
if (!isset($_SESSION['farmer_data']) || empty($_SESSION['farmer_data'])) {
    echo "Session data is not set. Please log in.";
    exit();
}

// Load Farmer Data from session
$farmer = $_SESSION['farmer_data'];

// Ensure that 'id' key exists in the session data
if (!isset($farmer['id'])) {
    echo "Farmer ID not found in session data.";
    exit();
}

$farmer_id = $farmer['id']; // Assign farmer ID from session

// Include the database connection
require 'db.php';

// Fetch current farmer profile details from the database
$query = "SELECT * FROM farmers WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$result = $stmt->get_result();
$farmer_data = $result->fetch_assoc();

if (!$farmer_data) {
    echo "No farmer found with this ID.";
    exit();
}

// Handle form submission for updating the profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated values from the form
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Update the farmer's details in the database
    $update_query = "UPDATE farmers SET full_name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $full_name, $email, $phone, $address, $farmer_id);
    
    if ($stmt->execute()) {
        // Update session data with new profile info
        $_SESSION['farmer_data']['full_name'] = $full_name;
        $_SESSION['farmer_data']['email'] = $email;
        $_SESSION['farmer_data']['phone'] = $phone;
        $_SESSION['farmer_data']['address'] = $address;

        // Redirect to the dashboard or a success page
        header("Location: farmer_dashboard.php?status=updated");
        exit();
    } else {
        echo "Error updating profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile | Natural Farming Marketplace</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff;
        }
        .form-container {
            max-width: 600px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-update {
            width: 100%;
            font-size: 18px;
            background-color: #28a745;
            color: white;
        }
        .btn-update:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2 class="text-center mb-4">Update Profile</h2>
        
        <!-- Display Success or Error Message -->
        <?php if (isset($_GET['status']) && $_GET['status'] === 'updated'): ?>
            <div class="alert alert-success">Profile updated successfully!</div>
        <?php endif; ?>

        <form action="update_profile.php" method="POST">
            <!-- Full Name -->
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" name="full_name" id="full_name" value="<?php echo htmlspecialchars($farmer_data['full_name']); ?>" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($farmer_data['email']); ?>" required>
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" name="phone" id="phone" value="<?php echo htmlspecialchars($farmer_data['phone']); ?>" required>
            </div>

            <!-- Address -->
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" name="address" id="address" required><?php echo htmlspecialchars($farmer_data['address']); ?></textarea>
            </div>

            <!-- Update Button -->
            <button type="submit" class="btn btn-update">Update Profile</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
