<?php
// Database connection settings
$host = 'localhost';  // Replace with your database host
$dbname = 'med_appoint';  // Replace with your database name
$username = 'root';  // Replace with your database username
$password = 'root';  // Replace with your database password

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX request to insert hospital-department-doctor record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hospital_id'], $_POST['department_id'], $_POST['doctor_id'])) {
    $hospital_id = trim($_POST['hospital_id']);
    $department_id = trim($_POST['department_id']);
    $doctor_id = trim($_POST['doctor_id']);

    // Validate input
    if (!empty($hospital_id) && !empty($department_id) && !empty($doctor_id)) {
        $stmt = $conn->prepare("INSERT INTO hospital_department_doctor (hospital_id, department_id, doctor_id) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $hospital_id, $department_id, $doctor_id);

        if ($stmt->execute()) {
            echo "<p style='color:green; text-align:center;'>Record added successfully!</p>";
        } else {
            echo "<p style='color:red; text-align:center;'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:red; text-align:center;'>All fields are required.</p>";
    }
}

// Retrieve and display hospital-department-doctor list
$sql = "SELECT id, hospital_id, department_id, doctor_id FROM hospital_department_doctor ORDER BY id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Hospital ID</th><th>Department ID</th><th>Doctor ID</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row['id']) . "</td><td>" . htmlspecialchars($row['hospital_id']) . "</td><td>" . htmlspecialchars($row['department_id']) . "</td><td>" . htmlspecialchars($row['doctor_id']) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p style='text-align:center;'>No records found.</p>";
}

// Close the database connection
$conn->close();
?>