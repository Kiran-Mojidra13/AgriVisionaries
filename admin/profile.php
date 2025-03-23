<?php
// session_start(); // This should be in config.php already
include 'config.php'; // Database connection

// Check if admin is logged in
/*if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];*/

// Assuming admin_id is fixed as 1 or passed dynamically via session or other means
$admin_id = 1;

try {
    // Fetch admin details
    $query = "SELECT * FROM admin WHERE id = :admin_id"; // Use :admin_id in query
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT); // Bind admin_id correctly
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Handle profile update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];

        $update_query = "UPDATE admins SET name = :name, email = :email WHERE id = :admin_id";
        $stmt = $conn->prepare($update_query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT); // Bind admin_id here

        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile updated successfully!";
            header("Location: profile.php");
            exit();
        } else {
            $_SESSION['error'] = "Failed to update profile.";
        }
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<?php include './includes/header.php'; ?>
<?php include './includes/sidebar.php'; ?>

<div class="container mt-4">
    <h2>Admin Profile</h2>
    <?php if (isset($_SESSION['success'])) { echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>'; unset($_SESSION['success']); } ?>
    <?php if (isset($_SESSION['error'])) { echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>'; unset($_SESSION['error']); } ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control"
                value="<?php echo htmlspecialchars($admin['name'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<?php include '../admin/includes/footer.php'; ?>