<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "med_appoint";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
    $citizenship_number = $_POST["citizenship_number"];
    $address = $_POST["address"];
    $password = $_POST["password"];
    $date_of_birth = $_POST["date_of_birth"];
    

    
    $sql = "INSERT INTO user (Name, Email, Ph_Number, Citizenship_no, Address, Password, Date_of_birth) 
            VALUES ('$name', '$email', '$phone_number', '$citizenship_number', '$address', '$password','$date_of_birth')";

    if (mysqli_query($conn, $sql)) {
        echo "Data inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Closing the connection
    mysqli_close($conn);
}
?>