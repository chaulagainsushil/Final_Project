<?php
// Start the session and get the user_id
 session_start();
$user_id = $_SESSION['user_id'];
$user_id=1;
echo $user_id;
// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "med_appoint";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
die("Connection failed: " . mysqli_connect_error());
}

// Fetch data from the database for the logged-in user
$sql = "SELECT `id`, `patient_name`, `doctor_name`, `appointment_date`,
`appointment_time`
FROM `appointments`
WHERE `UserId` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Table</title>
    <link rel="stylesheet" href="myappstyle.css">
</head>

<body>
    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Serial Number</th>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Date and Time</th>
                    <!-- <th>Reschedule Appointment</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                    // Check if there are any rows returned
                    if ($result->num_rows > 0) {
                    $serial = 1;
                    while ($row = $result->fetch_assoc()) {
                    $dateTime = $row['appointment_date'] . ' - ' .
                    $row['appointment_time'];
                    echo "<tr>
                        <td>{$serial}</td>
                        <td>{$row['patient_name']}</td>
                        <td>{$row['doctor_name']}</td>
                        <td>{$dateTime}</td>
                        <td><button class='reschedule-btn'>Reschedule</button></td>
                    </tr>";
                    $serial++;
                    }
                    } else {
                    echo "<tr><td colspan='5'>No appointments found</td></tr>";
                    }
                    ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>