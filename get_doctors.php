<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "med_appoint";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$hospitalId = isset($_GET['hospitalId']) ? (int)$_GET['hospitalId'] : 0;
$departmentId = isset($_GET['departmentId']) ? (int)$_GET['departmentId'] : 0;

if ($hospitalId > 0 && $departmentId > 0) {
    $sql = "SELECT distinct d.Doc_id, d.Doc_name FROM doctors d
            JOIN hospital_department_doctor hdd ON d.Doc_id = hdd.doctor_id
            WHERE hdd.hospital_id = ? AND hdd.department_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $hospitalId, $departmentId);
    $stmt->execute();
    $result = $stmt->get_result();

    $doctors = [];
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($doctors);
} else {
    echo json_encode([]);
}

$conn->close();