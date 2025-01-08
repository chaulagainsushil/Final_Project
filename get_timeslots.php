<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "med_appoint";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$doctorId = $_GET['doctorId'];
$appointmentDate = $_GET['date'];

$allSlots = ["09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "13:00", "13:30", "14:00", "14:30", "15:00", "15:30"];

// Fetch taken slots
$sql = "SELECT TIME_FORMAT(appointment_time, '%H:%i') as taken_time FROM appointments 
        WHERE doctor_id = ? AND appointment_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $doctorId, $appointmentDate);
$stmt->execute();
$result = $stmt->get_result();

$takenSlots = [];
while ($row = $result->fetch_assoc()) {
    $takenSlots[] = $row['taken_time'];
}

// Filter available slots
$availableSlots = array_diff($allSlots, $takenSlots);

header('Content-Type: application/json');
echo json_encode(array_values($availableSlots));

$conn->close();