<?php
$servername = "localhost";
$username = "root"; // Change if different
$password = ""; // Change if you have a password
$database = "nfm"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
