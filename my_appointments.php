<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "med_appoint";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = (int)$_SESSION['user_id'];

// Fetch user appointments
$sql = "SELECT a.id, a.patient_name, a.appointment_date, a.appointment_time, a.Appointment_Status, h.Hospital_name, d.Name AS department_name, doc.Doc_name 
        FROM appointments a
        JOIN hospitals h ON a.hospital_id = h.Hospital_id
        JOIN department d ON a.department_id = d.Depart_id
        JOIN doctors doc ON a.doctor_id = doc.Doc_id
        WHERE a.UserId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
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
    <title>My Appointments</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f7f9fc;
        color: #264653;
        padding: 20px;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .appointment {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #e9c46a;
        margin-bottom: 15px;
        padding: 15px;
        border-radius: 8px;
        color: #264653;
    }

    .appointment-info {
        max-width: 70%;
    }

    .btn {
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 15px;
        cursor: pointer;
        margin-left: 10px;
    }

    .btn-reschedule {
        background-color: #2a9d8f;
    }

    .btn-delete {
        background-color: #e76f51;
    }

    .btn-reschedule:hover {
        background-color: #264653;
    }

    .btn-delete:hover {
        background-color: #b33939;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        width: 100%;
        max-width: 500px;
        text-align: center;
    }

    .close-btn {
        float: right;
        font-size: 20px;
        cursor: pointer;
        color: #264653;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #d1d5db;
        border-radius: 5px;
    }

    .btn-save {
        background-color: #2a9d8f;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-save:hover {
        background-color: #264653;
    }

    .btn-confirm-delete {
        background-color: #e76f51;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-confirm-delete:hover {
        background-color: #b33939;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>My Appointments</h2>

        <?php if (count($appointments) === 0) : ?>
        <p>No appointments found.</p>
        <?php else : ?>
        <?php foreach ($appointments as $appointment) : ?>
        <div class="appointment">
            <div class="appointment-info">
                <strong><?php echo htmlspecialchars($appointment['patient_name']); ?></strong><br>
                <span><?php echo htmlspecialchars($appointment['appointment_date']); ?> at
                    <?php echo htmlspecialchars($appointment['appointment_time']); ?></span><br>
                <span>Hospital: <?php echo htmlspecialchars($appointment['Hospital_name']); ?></span><br>
                <span>Department: <?php echo htmlspecialchars($appointment['department_name']); ?></span><br>
                <span>Doctor: <?php echo htmlspecialchars($appointment['Doc_name']); ?></span><br>
                <span>Status: <?php echo htmlspecialchars($appointment['Appointment_Status']); ?></span>
            </div>
            <div>
                <!-- <button class="btn btn-reschedule" data-id="<?php echo $appointment['id']; ?>"
                    data-date="<?php echo $appointment['appointment_date']; ?>"
                    data-time="<?php echo $appointment['appointment_time']; ?>">Reschedule</button> -->
                <button class="btn btn-delete" data-id="<?php echo $appointment['id']; ?>">Delete</button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Modal for Rescheduling -->
    <!-- <div class="modal" id="reschedule-modal">
        <div class="modal-content">
            <span class="close-btn" id="close-modal">&times;</span>
            <h3>Reschedule Appointment</h3>
            <form id="reschedule-form">
                <input type="hidden" id="appointment-id">
                <div class="form-group">
                    <label for="new-date">New Date</label>
                    <input type="date" id="new-date" required>
                </div>
                <div class="form-group">
                    <label for="new-time">New Time</label>
                    <select id="new-time" required>
                        <option value="">Select Time</option>
                        <option value="09:00">09:00</option>
                        <option value="09:30">09:30</option>
                        <option value="10:00">10:00</option>
                        <option value="10:30">10:30</option>
                        <option value="11:00">11:00</option>
                        <option value="11:30">11:30</option>
                        <option value="12:00">12:00</option>
                        <option value="12:30">12:30</option>
                        <option value="13:00">13:00</option>
                        <option value="13:30">13:30</option>
                        <option value="14:00">14:00</option>
                        <option value="14:30">14:30</option>
                        <option value="15:00">15:00</option>
                        <option value="15:30">15:30</option>
                    </select>
                </div>
                <!-- <button type="submit" class="btn-save">Save Changes</button> -->
    </form>
    </div>
    </div> -->

    <script>
    let appointmentToDelete = null;

    // Show reschedule modal
    const rescheduleButtons = document.querySelectorAll('.btn-reschedule');
    const modal = document.getElementById('reschedule-modal');
    const closeModal = document.getElementById('close-modal');
    const form = document.getElementById('reschedule-form');

    // rescheduleButtons.forEach(button => {
    //     button.addEventListener('click', () => {
    //         document.getElementById('appointment-id').value = button.getAttribute('data-id');
    //         document.getElementById('new-date').value = button.getAttribute('data-date');
    //         modal.style.display = 'flex';
    //     });
    // });

    // closeModal.addEventListener('click', () => {
    //     modal.style.display = 'none';
    // });

    // form.addEventListener('submit', function(e) {
    //     e.preventDefault();
    //     const appointmentId = document.getElementById('appointment-id').value;
    //     const newDate = document.getElementById('new-date').value;
    //     const newTime = document.getElementById('new-time').value;

    //     fetch('reschedule_appointment.php', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/x-www-form-urlencoded',
    //             },
    //             body: `id=${appointmentId}&new_date=${newDate}&new_time=${newTime}`,
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             alert(data.message);
    //             if (data.status === 'success') {
    //                 location.reload(); // Reload on success
    //             }
    //         });
    // });

    // Delete appointment
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const appointmentId = button.getAttribute('data-id');
            if (confirm("Are you sure you want to delete this appointment?")) {
                fetch('delete_appointment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${appointmentId}`,
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.status === 'success') {
                            location.reload(); // Reload on success
                        }
                    });
            }
        });
    });
    </script>
</body>

</html>