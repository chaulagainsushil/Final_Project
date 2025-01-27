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

    $sql = "INSERT INTO user (Name, Email, Ph_Number, Citizenship_no, Address, Password, Date_of_birth, Role) 
            VALUES ('$name', '$email', '$phone_number', '$citizenship_number', '$address', '$password', '$date_of_birth', 'user')";

    if (mysqli_query($conn, $sql)) {
        // Redirect to index.html after successful registration
        header("Location: index.html");
        exit(); // To ensure no further code is executed after redirection
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Closing the connection
    mysqli_close($conn);
}
?>