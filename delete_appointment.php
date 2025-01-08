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

    // Delete appointment
    $sql = "DELETE FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $appointmentId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Appointment deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete appointment."]);
    }

    $stmt->close();
    $conn->close();
}
?>