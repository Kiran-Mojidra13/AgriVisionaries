<?php
session_start();

// Redirect back if language is not set
if (!isset($_SESSION['language'])) {
    header("Location: index.php");
    exit();
}

// Load the selected language file
$langFile = "lang/" . $_SESSION['language'] . ".php";
$texts = include($langFile);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['role'])) {
    $_SESSION['role'] = $_POST['role']; // Store selected role
    if ($_POST['role'] == 'farmer') {
        header("Location: farmer_register.php");
    } else {
        header("Location: customer_register.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texts['title_role']; ?> | Natural Farming Marketplace</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card text-center p-4 shadow-lg">
            <h2 class="mb-4"><?php echo $texts['title_role']; ?></h2>
            <form method="post">
                <button type="submit" name="role" value="farmer" class="btn btn-warning btn-lg m-2"><?php echo $texts['farmer']; ?></button>
                <button type="submit" name="role" value="customer" class="btn btn-info btn-lg m-2"><?php echo $texts['customer']; ?></button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
