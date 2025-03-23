<?php
session_start();
require 'db.php'; // Database connection

// Redirect if language is not set
if (!isset($_SESSION['language'])) {
    header("Location: index.php");
    exit();
}

// Load selected language file
$langFile = "lang/" . $_SESSION['language'] . ".php";
$texts = include($langFile);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texts['register_farmer']; ?> | Natural Farming Marketplace</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff;
        }
        .registration-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-register {
            width: 100%;
            font-size: 18px;
            background-color: #28a745;
            color: white;
        }
        .btn-register:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            font-size: 14px;
            display: block;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container">
    <div class="registration-container">
        <h2 class="text-center mb-4">🚜 <?php echo $texts['register_farmer']; ?></h2>

        <form action="farmer_register_process.php" method="post" enctype="multipart/form-data">
            
            <!-- Full Name -->
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="full_name" id="full_name" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" required>
                <span id="email_error" class="error"></span>
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="tel" class="form-control" name="phone" id="phone" pattern="[0-9]{10}" required>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                <span id="confirm_password_error" class="error"></span>
            </div>

            <!-- Full Address -->
            <div class="mb-3">
                <label class="form-label">Full Address</label>
                <textarea class="form-control" name="address" required></textarea>
            </div>

            <!-- State (Default: Gujarat) -->
            <div class="mb-3">
                <label class="form-label">State</label>
                <input type="text" class="form-control" name="state" value="Gujarat" readonly>
            </div>

            <!-- City -->
            <div class="mb-3">
                <label class="form-label">City</label>
                <input type="text" class="form-control" name="city" required>
            </div>

            <!-- Pincode -->
            <div class="mb-3">
                <label class="form-label">Pincode</label>
                <input type="text" class="form-control" name="pincode" pattern="[0-9]{6}" required>
            </div>

            <!-- Upload Certificate -->
            <div class="mb-3">
                <label class="form-label">Upload Certificate</label>
                <input type="file" class="form-control" name="certificate_image" accept="image/*" required>
            </div>

            <!-- Certificate Number -->
            <div class="mb-3">
                <label class="form-label">Certificate Number</label>
                <input type="text" class="form-control" name="certificate_number" id="certificate_number" required>
                <span id="certificate_error" class="error"></span>
            </div>

            <!-- Bank Details -->
            <h5 class="mt-4">💳 Bank Details</h5>
            <div class="mb-3">
                <label class="form-label">Bank Name</label>
                <input type="text" class="form-control" name="bank_name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Account Number</label>
                <input type="text" class="form-control" name="account_number" pattern="[0-9]{9,18}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">IFSC Code</label>
                <input type="text" class="form-control" name="ifsc_code" pattern="[A-Z]{4}[0-9]{7}" required>
            </div>

            <button type="submit" class="btn btn-register">Submit</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Confirm password check
        $("#confirm_password").on("input", function() {
            var password = $("#password").val();
            var confirmPassword = $(this).val();
            if (password !== confirmPassword) {
                $("#confirm_password_error").text("Passwords do not match!").css("color", "red");
            } else {
                $("#confirm_password_error").text("");
            }
        });

        // Certificate number and full name validation
        $("#certificate_number, #full_name").on("input", function() {
            var certificateNumber = $("#certificate_number").val().trim();
            var fullName = $("#full_name").val().trim();

            if (certificateNumber.length > 3 && fullName.length > 3) {
                $.ajax({
                    url: "validate_certificate.php",
                    type: "POST",
                    data: { certificate_number: certificateNumber, full_name: fullName },
                    success: function(response) {
                        if (response.trim() === "valid") {
                            $("#certificate_error").text("✔ Valid certificate number").css("color", "green");
                        } else {
                            $("#certificate_error").text("❌ Certificate number or name is incorrect").css("color", "red");
                        }
                    }
                });
            }
        });
    });
</script>

</body>
</html>
