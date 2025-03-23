<?php
session_start();

// Include the database connection
require 'db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL query to fetch the customer by email
    $query = "SELECT * FROM customers WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if customer exists
    if ($result->num_rows > 0) {
        // Fetch the customer data
        $customer = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $customer['password'])) {
            // Password is correct, start the session and store the customer data
            $_SESSION['customer_data'] = $customer;

            // Redirect to the customer dashboard
            header("Location: cust_dashboard.php");
            exit();
        } else {
            // Incorrect password
            $error_message = "Invalid password.";
        }
    } else {
        // Customer not found
        $error_message = "No account found with that email.";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!-- HTML Form for Customer Login -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login | Natural Farming Marketplace</title>

    <!-- Bootstrap 5 for styling and responsiveness -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f7f6;
    }

    .login-form {
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .login-form h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .login-form .form-group {
        margin-bottom: 15px;
    }
    </style>
</head>

<body>

    <div class="container">
        <div class="login-form">
            <h2>Customer Login</h2>

            <!-- Display error message if there is one -->
            <?php if (isset($error_message)): ?>
            <div class="alert alert-danger text-center">
                <?php echo $error_message; ?>
            </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="cust_login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                <div class="text-center">
                    <p>Don't have an account? <a href="cust_registration.php">Register</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>