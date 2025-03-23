<?php
session_start();
require_once 'vendor/autoload.php'; // Composer autoload
require_once 'db.php'; // Ensure this is correct based on your directory structure

use Razorpay\Api\Api;

// Manually set Razorpay API credentials
$key_id = 'rzp_test_weWkTdxTnwUghx';       // Replace with your Razorpay Key ID
$key_secret = '7ujDworqXqupNSitmjSpsa8M'; // Replace with your Razorpay Key Secret

// Redirect if not logged in
if (!isset($_SESSION['customer_data'])) {
    header("Location: customer_login.php");
    exit();
}

// Fetch customer data
$customer = $_SESSION['customer_data'];
$cart_items = [];

// Fetch cart items if any
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product_query = "SELECT p.*, f.full_name AS farmer_name, f.address AS farmer_address, f.phone AS farmer_contact
                          FROM products p
                          JOIN farmers f ON p.farmer_id = f.id
                          WHERE p.product_id = ?";
        $stmt_product = $conn->prepare($product_query);
        $stmt_product->bind_param("i", $product_id);
        $stmt_product->execute();
        $product_result = $stmt_product->get_result();
        $product = $product_result->fetch_assoc();

        if ($product) {
            $product['quantity'] = $quantity;
            $cart_items[] = $product;
        }
    }
}

// Calculate total price
$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['product_price'] * $item['quantity'];
}

// Insert order data into `my_orders` table
if ($total_amount > 0) {
    $insert_order_query = "INSERT INTO my_orders (customer_id, product_id, product_name, product_price, quantity, total_amount)
                           VALUES (?, ?, ?, ?, ?, ?)";

    // Insert each product in the cart
    foreach ($cart_items as $item) {
        $stmt_insert = $conn->prepare($insert_order_query);
        $stmt_insert->bind_param("iisdid", 
            $customer['customer_id'], // Assuming you have the customer_id in the session
            $item['product_id'],
            $item['product_name'],
            $item['product_price'],
            $item['quantity'],
            $total_amount
        );
        $stmt_insert->execute();
    }
}

// Check if API keys are set, else terminate with an error message
if (empty($key_id) || empty($key_secret)) {
    die("Razorpay API keys are not set. Please check your configuration.");
}

// Initialize Razorpay API
$api = new Api($key_id, $key_secret);

// Ensure the total amount is converted to a string and then multiplied by 100 to convert to paise
$amount_in_paise = (string)($total_amount * 100); // Explicitly cast to string

// Create an order on Razorpay
$orderData = [
    'receipt'         => (string)rand(1000, 9999), // Cast receipt to string
    'amount'          => $amount_in_paise,  // Amount in paise (as string)
    'currency'        => 'INR',
    'payment_capture' => 1  // 1 means the payment will be captured automatically after authorization
];

try {
    $razorpayOrder = $api->order->create($orderData);  // Correct method to create an order
    $order_id = $razorpayOrder->id;
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();  // Show error if the order creation fails
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Natural Farming Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fc;
        margin-top: 50px;
    }

    .checkout-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .checkout-title {
        text-align: center;
        margin-bottom: 30px;
        font-size: 24px;
        color: #333;
    }

    .table th,
    .table td {
        text-align: center;
    }

    .table th {
        background-color: #28a745;
        color: white;
    }

    .total-amount {
        font-size: 20px;
        font-weight: bold;
        color: #28a745;
        text-align: right;
        margin-bottom: 20px;
    }

    .pay-btn {
        width: 100%;
        background-color: #28a745;
        color: white;
        padding: 10px;
        border: none;
        font-size: 18px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .pay-btn:hover {
        background-color: #218838;
    }

    @media (max-width: 768px) {
        .checkout-container {
            padding: 10px;
        }

        .checkout-title {
            font-size: 20px;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="checkout-container">
            <h2 class="checkout-title">Your Cart</h2>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td>₹<?php echo number_format($item['product_price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>₹<?php echo number_format($item['product_price'] * $item['quantity'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p class="total-amount">Total Amount: ₹<?php echo number_format($total_amount, 2); ?></p>

            <button id="pay_btn" class="pay-btn">Pay Now</button>
        </div>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
    var options = {
        "key": "<?php echo $key_id; ?>", // Razorpay Key ID
        "amount": "<?php echo $amount_in_paise; ?>", // Amount in paise (as string)
        "currency": "INR",
        "order_id": "<?php echo (string)$order_id; ?>", // Cast order_id to string
        "name": "Natural Farming Marketplace",
        "description": "Payment for your cart items",
        "image": "https://example.com/logo.png", // Replace with your logo URL
        "handler": function(response) {
            // Handle successful payment
            window.location.href = "payment_success.php?payment_id=" + response.razorpay_payment_id +
                "&order_id=" + response.razorpay_order_id;
        },
        "prefill": {
            "name": "<?php echo htmlspecialchars($customer['full_name']); ?>",
            "email": "<?php echo htmlspecialchars($customer['email']); ?>",
            "contact": "<?php echo htmlspecialchars($customer['phone']); ?>"
        },
        "notes": {
            "address": "<?php echo htmlspecialchars($customer['address']); ?>"
        },
        "theme": {
            "color": "#28a745"
        }
    };

    var rzp1 = new Razorpay(options);
    document.getElementById('pay_btn').onclick = function(e) {
        rzp1.open();
        e.preventDefault();
    }
    </script>
</body>

</html>