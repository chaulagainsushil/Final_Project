<?php
// Database configuration
$servername = "localhost"; // Change this to your database server (e.g., "127.0.0.1")
$username = "root";        // Change this to your database username
$password = "root";            // Change this to your database password
$dbname = "med_appoint"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $hospital_id = $_POST['hospital_id'];
    $hospital_name = $_POST['hospital_name'];
    $address = $_POST['address'];

    // Prepare the SQL query
    if (!empty($hospital_id)) {
        // Insert with hospital_id (if provided)
        $sql = "INSERT INTO hospitals (Hospital_id, Hospital_name, Address) VALUES ('$hospital_id', '$hospital_name', '$address')";
    } else {
        // Auto-increment hospital_id
        $sql = "INSERT INTO hospitals (Hospital_name, Address) VALUES ('$hospital_name', '$address')";
    }

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "New hospital record added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>