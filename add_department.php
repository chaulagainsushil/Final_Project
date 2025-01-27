<?php

$host = 'localhost'; 
$dbname = 'med_appoint';  
$username = 'root';  
$password = 'root';

$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dept_name'])) {
    $dept_name = trim($_POST['dept_name']);

   
    if (!empty($dept_name)) {
        $stmt = $conn->prepare("INSERT INTO Department (Name) VALUES (?)");
        $stmt->bind_param("s", $dept_name);

        if ($stmt->execute()) {
            echo "<p style='color:green; text-align:center;'>Department added successfully!</p>";
        } else {
            echo "<p style='color:red; text-align:center;'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:red; text-align:center;'>Please enter the department name.</p>";
    }
}

$sql = "SELECT Depart_id, Name FROM Department ORDER BY Depart_id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Department ID</th><th>Department Name</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row['Depart_id']) . "</td><td>" . htmlspecialchars($row['Name']) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p style='text-align:center;'>No departments found.</p>";
}


$conn->close();
?>