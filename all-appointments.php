<?php
session_start();

// Check if hospital is logged in
if (!isset($_SESSION['hospitalId'])) {
    header("Location: login.php");
    exit();
}

$hospitalId = $_SESSION['hospitalId'];
$hospitalName = $_SESSION['name'];

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "med_appoint";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all appointments for the hospital
$sql = "SELECT a.id, a.patient_name, a.patient_age, a.contact_number, a.appointment_date, a.appointment_time, a.Appointment_Status, 
               d.Name AS department_name, doc.Doc_name 
        FROM appointments a
        JOIN department d ON a.department_id = d.Depart_id
        JOIN doctors doc ON a.doctor_id = doc.Doc_id
        WHERE a.hospital_id = ?
        ORDER BY a.appointment_date ASC, a.appointment_time ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hospitalId);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Appointments - <?php echo htmlspecialchars($hospitalName); ?></title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f7f9fc;
        color: #264653;
        padding: 20px;
    }

    .container {
        max-width: 1000px;
        margin: 0 auto;
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #2a9d8f;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #264653;
        color: white;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .status-pending {
        color: #e76f51;
        font-weight: bold;
    }

    .status-confirmed {
        color: #2a9d8f;
        font-weight: bold;
    }

    .status-cancelled {
        color: #b33939;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>All Appointments for <?php echo htmlspecialchars($hospitalName); ?></h2>

        <?php if (count($appointments) === 0) : ?>
        <p>No appointments found for this hospital.</p>
        <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient Name</th>
                    <th>Age</th>
                    <th>Contact Number</th>
                    <th>Appointment Date</th>
                    <th>Time</th>
                    <th>Department</th>
                    <th>Doctor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $index => $appointment) : ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['patient_age']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['contact_number']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['department_name']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['Doc_name']); ?></td>
                    <td class="<?php echo 'status-' . strtolower($appointment['Appointment_Status']); ?>">
                        <?php echo htmlspecialchars($appointment['Appointment_Status']); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</body>

</html>