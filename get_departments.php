<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "med_appoint";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$hospitalId = $_GET['hospitalId'];

$sql = "SELECT distinct d. * FROM department d 
        JOIN hospital_department_doctor hdd ON d.Depart_id = hdd.department_id 
        WHERE hdd.hospital_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hospitalId);
$stmt->execute();
$result = $stmt->get_result();

$departments = [];
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

header('Content-Type: application/json');
echo json_encode($departments);

$conn->close();