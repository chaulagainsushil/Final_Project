<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "med_appoint";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $appointmentId = $_POST['id'];
    $newDate = $_POST['new_date'];
    $newTime = $_POST['new_time'];

    // Update appointment with new date and time
    $sql = "UPDATE appointments SET appointment_date = ?, appointment_time = ?, Appointment_Status = 'Rescheduled' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $newDate, $newTime, $appointmentId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Appointment rescheduled successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to reschedule appointment."]);
    }

    $stmt->close();
    $conn->close();
}
?>