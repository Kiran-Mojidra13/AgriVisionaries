<?php
//session_start();
include 'config.php';
include 'includes/header.php';
include 'includes/sidebar.php';

// Fetch farmers from the database using PDO
$sql = "SELECT * FROM farmers";
$stmt = $conn->prepare($sql);
$stmt->execute();
$farmers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2>Manage Farmers</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Certification ID</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($farmers as $row) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['certification_id']); ?></td>
                <td><?php echo $row['is_verified'] ? 'Approved' : 'Pending'; ?></td>
                <td>
                    <?php if ($row['is_verified'] == 0) { ?>
                    <a href="approve_farmer.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Approve</a>
                    <a href="reject_farmer.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Reject</a>
                    <?php } ?>
                </td>

            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>