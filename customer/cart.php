<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['customer_data'])) {
    header("Location: customer_login.php");
    exit();
}

$customer = $_SESSION['customer_data'];
$cart_items = [];

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

if (isset($_GET['remove_id'])) {
    $remove_id = $_GET['remove_id'];
    unset($_SESSION['cart'][$remove_id]);
    header("Location: cart.php");
    exit();
}

if (isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    if ($quantity > 0) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        unset($_SESSION['cart'][$product_id]);
    }
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #28a745;">
        <div class="container">
            <a class="navbar-brand" href="cust_dashboard.php">Natural Farming 🥕</a>
        </div>
    </nav>

    <!-- Cart Table -->
    <div class="container mt-5">
        <h2>Your Cart</h2>
        <?php if (count($cart_items) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td>₹<?php echo number_format($item['product_price'], 2); ?></td>
                    <td>
                        <form method="post">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <button type="submit" name="update_cart" class="btn btn-warning btn-sm">Update</button>
                        </form>
                    </td>
                    <td>₹<?php echo number_format($item['product_price'] * $item['quantity'], 2); ?></td>
                    <td><a href="cart.php?remove_id=<?php echo $item['product_id']; ?>" class="btn btn-danger btn-sm">Remove</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
        <?php else: ?>
            <p>Your cart is empty. Start adding products to your cart!</p>
        <?php endif; ?>
    </div>

</body>
</html>
