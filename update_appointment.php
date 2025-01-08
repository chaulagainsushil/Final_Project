<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "med_appoint";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $appointmentId = $_POST['id'];
    $patientName = $conn->real_escape_string($_POST['patient_name']);
    $patientAge = (int)$_POST['patient_age'];
    $contactNumber = $conn->real_escape_string($_POST['contact_number']);
    $appointmentDate = $conn->real_escape_string($_POST['appointment_date']);
    $appointmentTime = $conn->real_escape_string($_POST['appointment_time']);
    $status = $conn->real_escape_string($_POST['appointment_status']);

    $sql = "UPDATE appointments SET patient_name = ?, patient_age = ?, contact_number = ?, appointment_date = ?, appointment_time = ?, Appointment_Status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissssi", $patientName, $patientAge, $contactNumber, $appointmentDate, $appointmentTime, $status, $appointmentId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Appointment updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update appointment."]);
    }

    $stmt->close();
    $conn->close();
}
?>