<?php
// Database connection
$host = 'localhost'; // Replace with your database host
$dbName = 'med_appoint'; // Database name
$username = 'root'; // Replace with your database username
$password = 'root'; // Replace with your database password

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $depart_id = $_POST['depart_id'];
    $name = $_POST['name'];

    // Validate input
    if (empty($depart_id) || empty($name)) {
        echo "Please fill out all fields.";
        exit;
    }

    // Insert data into the Department table
    $sql = "INSERT INTO Department (Depart_id, Name) VALUES ('$depart_id', '$name')";
    if ($conn->query($sql) === TRUE) {
        echo "Department added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

// Close the connection
$conn->close();
?>