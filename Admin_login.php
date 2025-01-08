<?php
// Admin_Login.php
session_start();
// Database connection
$host = 'localhost'; // Replace with your database host
$dbName = 'med_appoint'; // Database name
$username = 'root'; // Replace with your database username
$password = 'root'; // Replace with your database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

// Check if POST data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Please fill out all fields."]);
        exit;
    }

    // Query the database for the user
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email and role='admin'");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && ($password== $user['Password'])) {
        $_SESSION["ïsAdmin"]=true;
        // Login successful
        echo json_encode(["status" => "success", "redirect" => "Admin_dashboard.html"]);
    } else {
        // Login failed
        echo json_encode(["status" => "error", "message" => "Invalid email or password."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>