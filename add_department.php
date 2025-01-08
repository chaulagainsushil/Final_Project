<?php
// Database connection
$host = 'localhost'; // Replace with your database host
$dbName = 'med_appoint'; // Database name
$username = 'root'; // Replace with your database username
$password = 'root'; // Replace with your database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if POST data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $depart_id = $_POST['depart_id'] ?? null;
    $name = $_POST['name'] ?? null;

    // Validate input
    if (empty($depart_id) || empty($name)) {
        echo "<p class='message'>Please fill out all fields.</p>";
        exit;
    }

    // Insert data into the Departments table
    try {
        $stmt = $conn->prepare("INSERT INTO Department (Depart_id, Name) VALUES (:depart_id, :name)");
        $stmt->bindParam(':depart_id', $depart_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();

        echo "<p class='message' style='color: green;'>Department added successfully!</p>";
    } catch (PDOException $e) {
        echo "<p class='message' style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='message'>Invalid request method.</p>";
}
?>