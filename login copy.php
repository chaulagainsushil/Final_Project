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
   
    if (isset($_POST["email"]) && isset($_POST["password"])) {
     
        $email = $_POST["email"];
        $password = $_POST["password"];

        $email = mysqli_real_escape_string($conn, $email);
        $password = mysqli_real_escape_string($conn, $password);

      
        $sql = "SELECT * FROM user WHERE Email='$email' AND Password='$password'";
        echo $sql;
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            session_start();
             $user = mysqli_fetch_row($result); 
             echo $user;
             $_SESSION['user_id'] = $user['id']; 
            $_SESSION['email']= $email ;
            setcookie("email",$email,time()+(86400*30),"/");
            
            //header("Location: dashboard.html");
           exit; 
        } else {
           
            echo "Invalid email or password. Please try again.";
        }
    } 
}

mysqli_close($conn);
?>