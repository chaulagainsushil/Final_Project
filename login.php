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

    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT id, Email, Password, Name FROM user WHERE Email = ? and role ='user'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['Password'] === $password) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['Email'];

            // Set email in cookie
            setcookie('email', $user['Email'], time() + (86400 * 30), "/"); // 30 days
            setcookie('name', $user['Name'], time() + (86400 * 30), "/");
            echo json_encode(["status" => "success", "redirect" => "dashboard.html"]);
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "Incorrect password."]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found."]);
        exit();
    }

    $conn->close();
}
?>