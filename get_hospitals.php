<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "med_appoint";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM hospitals";
$result = $conn->query($sql);

$hospitals = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hospitals[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($hospitals);

$conn->close();