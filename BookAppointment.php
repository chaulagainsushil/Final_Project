<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
// Database connection
$servername = "localhost";
$username = "root";
$password = "root"; 
$dbname = "med_appoint";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_name = $_POST['patientName'];
    $patient_age = $_POST['patientAge'];
    $contact_number = $_POST['contactNumber'];
    $hospital = $_POST['hospital'];
    $department = $_POST['department'];
    $doctor_name = $_POST['doctorName']; 
    $appointment_date = $_POST['appointmentDate'];
    $appointment_time = $_POST['appointmentTime']; 
    $appointment_status = "Pending"; 
    $user_id = (int)$_SESSION['user_id'];
    $sql = "INSERT INTO appointments (patient_name, patient_age, contact_number, hospital, department, doctor_name, appointment_date, appointment_time, Appointment_Status,userid) 
            VALUES ('$patient_name', '$patient_age', '$contact_number', '$hospital', '$department', '$doctor_name', '$appointment_date', '$appointment_time', '$appointment_status','$userId')";

    if ($conn->query($sql) === TRUE) {
        echo "Appointment booked successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>