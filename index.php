<?php
session_start();

// Set default language as English if not selected
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'english';
}

// Handle language selection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['language'])) {
    $_SESSION['language'] = $_POST['language']; // Store selected language
    header("Location: role_selection.php");
    exit();
}

// Load the selected language file
$langFile = "lang/" . $_SESSION['language'] . ".php";
$texts = include($langFile);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texts['title_language']; ?> | Natural Farming Marketplace</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card text-center p-4 shadow-lg">
            <h2 class="mb-4"><?php echo $texts['title_language']; ?></h2>
            <form method="post">
                <button type="submit" name="language" value="english" class="btn btn-primary btn-lg m-2"><?php echo $texts['english']; ?></button>
                <button type="submit" name="language" value="gujarati" class="btn btn-success btn-lg m-2"><?php echo $texts['gujarati']; ?></button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
