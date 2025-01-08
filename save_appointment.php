<?php
 session_start();
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "med_appoint";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientName = $conn->real_escape_string($_POST['patient_name']);
    $patientAge = (int)$_POST['patient_age'];
    $contactNumber = $conn->real_escape_string($_POST['contact_number']);
    $appointmentDate = $conn->real_escape_string($_POST['appointment_date']);
    $appointmentTime = $conn->real_escape_string($_POST['appointment_time']);
    $doctorId = (int)$_POST['doctor_id'];
    $hospitalId = (int)$_POST['hospital_id'];
    $departmentId = (int)$_POST['department_id'];
    $userId =(int)$_SESSION['user_id'];

    // Check if the selected time slot is already booked
    $checkSlotQuery = "SELECT * FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND appointment_time = ?";
    $stmt = $conn->prepare($checkSlotQuery);
    $stmt->bind_param("iss", $doctorId, $appointmentDate, $appointmentTime);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Time slot is already taken. Please select another slot."]);
        exit();
    }

    // Insert new appointment
    $insertQuery = "INSERT INTO appointments (patient_name, patient_age, contact_number, appointment_date, appointment_time, Appointment_Status, UserId, hospital_id, department_id, doctor_id)
                    VALUES (?, ?, ?, ?, ?, 'Pending', ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sisssiiii", $patientName, $patientAge, $contactNumber, $appointmentDate, $appointmentTime, $userId, $hospitalId, $departmentId, $doctorId);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Appointment booked successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to book the appointment. Please try again."]);
    }

    $stmt->close();
}
$conn->close();