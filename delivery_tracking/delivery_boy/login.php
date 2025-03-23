<?php
session_start();

// Assuming you are checking the login credentials against a database
if (isset($_POST['login'])) {
    // Dummy login validation (You should replace this with actual database check)
    $username = $_POST['username'];
    $password = $_POST['password'];

    // For example, fetch the delivery_boy_id from your database based on username and password
    // This is just a placeholder, and you need to query your database to verify the login
    if ($username == "ravi" && $password == "password") {  // Replace with actual DB query
        // Assuming delivery_boy_id is 1 for this example
        $_SESSION['username'] = $username;
        $_SESSION['delivery_boy_id'] = 2;  // Replace this with the actual delivery_boy_id from the DB
        header("Location: dashboard.php");
    } else {
        echo "Invalid login credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Delivery Boy Login</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="login">Login</button>
        </form>
    </div>
</body>

</html>