<?php
// Database connection settings
$host = 'localhost'; // Replace with your database host
$dbname = 'med_appoint'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = 'root'; // Replace with your database password

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doc_id = $_POST['doc_id'];
    $doc_name = $_POST['doc_name'];

    // Validate inputs
    if (empty($doc_id) || empty($doc_name)) {
        echo "Please fill out all fields.";
        exit;
    }

    // Insert data into the database
    $sql = "INSERT INTO Doctors (Doc_id, Doc_name) VALUES ('$doc_id', '$doc_name')";
    if ($conn->query($sql) === TRUE) {
        echo "Doctor added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>