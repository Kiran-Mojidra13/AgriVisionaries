<?php
session_start();

// Include database connection
require 'db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture the login details from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the input data (basic validation)
    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = 'Both fields are required.';
        header("Location: farmer_login.php");
        exit();
    }

    // Query to find farmer by either email or phone number
    $query = "SELECT * FROM farmers WHERE email = ? OR phone = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $farmer = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $farmer['password'])) {
            // Successful login, store farmer data in session
            $_SESSION['farmer_data'] = [
                'id' => $farmer['id'],
                'full_name' => $farmer['full_name'],
                'email' => $farmer['email'],
                'phone' => $farmer['phone'],
                'address' => $farmer['address'],
                // You can add any other data you need
            ];

            // Redirect to the dashboard
            header("Location: farmer_dashboard.php");
            exit();
        } else {
            // Incorrect password
            $_SESSION['login_error'] = 'Invalid credentials. Please try again.';
            header("Location: farmer_login.php");
            exit();
        }
    } else {
        // Farmer not found
        $_SESSION['login_error'] = 'Farmer not found. Please check your credentials.';
        header("Location: farmer_login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Natural Farming Marketplace</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff;
        }
        .login-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-login {
            width: 100%;
            font-size: 18px;
            background-color: #28a745;
            color: white;
        }
        .btn-login:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            font-size: 14px;
            display: block;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-container">
        <h2 class="text-center mb-4">Farmer Login</h2>
        
        <!-- Display Error Message -->
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?></div>
        <?php endif; ?>

        <form action="farmer_login.php" method="POST">
            
            <!-- Username (Email or Phone) -->
            <div class="mb-3">
                <label for="username" class="form-label">Username (Email or Phone)</label>
                <input type="text" class="form-control" name="username" id="username" required placeholder="Enter email or phone">
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" required placeholder="Enter your password">
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn btn-login">Login</button>

            <!-- Redirect to Registration Page -->
            <p class="mt-3 text-center">
                Not a member? <a href="farmer_register.php">Register here</a>
            </p>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
