<?php

$host = 'localhost';
$dbname = 'med_appoint';
$username = 'root';
$password = 'root';


$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hospital_name']) && isset($_POST['address'])) {
    $hospital_name = trim($_POST['hospital_name']);
    $address = trim($_POST['address']);


    if (!empty($hospital_name) && !empty($address)) {
        $stmt = $conn->prepare("INSERT INTO Hospitals (Hospital_name, Address) VALUES (?, ?)");
        $stmt->bind_param("ss", $hospital_name, $address);

        if ($stmt->execute()) {
            echo "<p style='color:green; text-align:center;'>Hospital added successfully!</p>";
        } else {
            echo "<p style='color:red; text-align:center;'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:red; text-align:center;'>Please enter all fields.</p>";
    }
}

$sql = "SELECT Hospital_name, Address FROM Hospitals";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Hospital Name</th><th>Address</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row['Hospital_name']) . "</td><td>" . htmlspecialchars($row['Address']) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p style='text-align:center;'>No hospitals found.</p>";
}


$conn->close();
?>