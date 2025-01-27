<?php

$host = 'localhost'; 
$dbname = 'med_appoint';  
$username = 'root';  
$password = 'root'; 


$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doc_name'])) {
    $doc_name = trim($_POST['doc_name']);

 
    if (!empty($doc_name)) {
        $stmt = $conn->prepare("INSERT INTO Doctors (Doc_name) VALUES (?)");
        $stmt->bind_param("s", $doc_name);

        if ($stmt->execute()) {
            echo "<p style='color:green; text-align:center;'>Doctor added successfully!</p>";
        } else {
            echo "<p style='color:red; text-align:center;'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:red; text-align:center;'>Please enter the doctor's name.</p>";
    }
}


$sql = "SELECT Doc_id, Doc_name FROM Doctors ORDER BY Doc_id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Doctor ID</th><th>Doctor Name</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row['Doc_id']) . "</td><td>" . htmlspecialchars($row['Doc_name']) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p style='text-align:center;'>No doctors found.</p>";
}


$conn->close();
?>