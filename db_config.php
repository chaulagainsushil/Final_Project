<?php
$host = "localhost";  // Change if using remote DB
$db_name = "khalti_demo";
$username = "root";  // Your MySQL username
$password = "";      // Your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>