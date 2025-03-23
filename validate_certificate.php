<?php
require 'db.php';

if (isset($_POST['certificate_number'], $_POST['full_name'])) {
    $certificate_number = trim($_POST['certificate_number']);
    $farmer_name = trim($_POST['full_name']);

    $stmt = $conn->prepare("SELECT * FROM farmer_certificate WHERE certificate_number = ? AND farmer_name = ?");
    $stmt->bind_param("ss", $certificate_number, $farmer_name);
    $stmt->execute();
    $result = $stmt->get_result();

    echo ($result->num_rows > 0) ? "valid" : "invalid";

    $stmt->close();
    $conn->close();
}
?>
