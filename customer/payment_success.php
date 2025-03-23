<?php
// Include the Composer autoload file
require_once '../vendor/autoload.php'; // Adjust the path to the correct location

use Razorpay\Api\Api;

// Your Razorpay credentials
$key_id = 'rzp_test_weWkTdxTnwUghx';  // Replace with your Razorpay Key ID
$key_secret = '7ujDworqXqupNSitmjSpsa8M';  // Replace with your Razorpay Key Secret

// Initialize Razorpay API
$api = new Api($key_id, $key_secret);

// Assuming payment details come via query string (ensure proper validation and sanitization in real applications)
$payment_id = $_GET['payment_id'];
$order_id = $_GET['order_id'];

try {
    // Fetch payment details from Razorpay
    $payment = $api->payment->fetch($payment_id);
    
    // Check if the payment is successful
    if ($payment->status == 'captured') {
        // Handle success logic, update order status, etc.
        $payment_status = "Payment successful!";
    } else {
        $payment_status = "Payment failed.";
    }
} catch (Exception $e) {
    $payment_status = 'Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation - Natural Farming Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fc;
        margin-top: 50px;
    }

    .confirmation-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .confirmation-title {
        text-align: center;
        font-size: 28px;
        color: #333;
        margin-bottom: 30px;
    }

    .confirmation-message {
        text-align: center;
        font-size: 20px;
        color: #333;
    }

    .status {
        font-size: 24px;
        font-weight: bold;
        color: #28a745;
        margin-top: 20px;
    }

    .status.failed {
        color: #dc3545;
    }

    .btn-go-home {
        display: block;
        margin: 20px auto;
        width: 200px;
        background-color: #28a745;
        color: white;
        padding: 12px;
        border: none;
        font-size: 18px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .btn-go-home:hover {
        background-color: #218838;
    }

    @media (max-width: 768px) {
        .confirmation-container {
            padding: 20px;
        }

        .confirmation-title {
            font-size: 24px;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="confirmation-container">
            <h2 class="confirmation-title">Payment Confirmation</h2>

            <div class="confirmation-message">
                <?php if (strpos($payment_status, 'Error') !== false): ?>
                <p class="status failed"><?php echo $payment_status; ?></p>
                <?php else: ?>
                <p class="status"><?php echo $payment_status; ?></p>
                <p>Your payment was successfully processed, and your order is being processed.</p>
                <?php endif; ?>
            </div>

            <button class="btn-go-home" onclick="window.location.href='cust_dashboard.php'">Go to Home</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>