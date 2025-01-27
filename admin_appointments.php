<?php
session_start();




$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "med_appoint";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT a.id, a.patient_name, a.patient_age, a.contact_number, a.appointment_date, a.appointment_time, a.Appointment_Status, 
               h.Hospital_name, d.Name AS department_name, doc.Doc_name 
        FROM appointments a
        JOIN hospitals h ON a.hospital_id = h.Hospital_id
        JOIN department d ON a.department_id = d.Depart_id
        JOIN doctors doc ON a.doctor_id = doc.Doc_id
        ORDER BY a.appointment_date ASC, a.appointment_time ASC";

$result = $conn->query($sql);
$appointments = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - All Appointments</title>
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

    .btn-edit {
        background-color: #2a9d8f;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 12px;
        cursor: pointer;
    }

    .btn-edit:hover {
        background-color: #264653;
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
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-weight: bold;
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

    .close-btn {
        float: right;
        font-size: 20px;
        cursor: pointer;
        color: #264653;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Admin - All Appointments</h2>

        <?php if (count($appointments) === 0) : ?>
        <p>No appointments found.</p>
        <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient Name</th>
                    <th>Age</th>
                    <th>Contact Number</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Department</th>
                    <th>Doctor</th>
                    <th>Status</th>
                    <th>Action</th>
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
                    <td><?php echo htmlspecialchars($appointment['Appointment_Status']); ?></td>
                    <td>
                        <button class="btn-edit" data-id="<?php echo $appointment['id']; ?>"
                            data-name="<?php echo htmlspecialchars($appointment['patient_name']); ?>"
                            data-age="<?php echo htmlspecialchars($appointment['patient_age']); ?>"
                            data-contact="<?php echo htmlspecialchars($appointment['contact_number']); ?>"
                            data-date="<?php echo htmlspecialchars($appointment['appointment_date']); ?>"
                            data-time="<?php echo htmlspecialchars($appointment['appointment_time']); ?>"
                            data-status="<?php echo htmlspecialchars($appointment['Appointment_Status']); ?>">Edit</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>


    <div class="modal" id="edit-modal">
        <div class="modal-content">
            <span class="close-btn" id="close-modal">&times;</span>
            <h3>Edit Appointment</h3>
            <form id="edit-form">
                <input type="hidden" id="id" name="id">
                <div class="form-group">
                    <label for="patient_name">Patient Name</label>
                    <input type="text" id="patient_name" name="patient_name" required>
                </div>
                <div class="form-group">
                    <label for="patient_age">Patient Age</label>
                    <input type="number" id="patient_age" name="patient_age" required>
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number" required>
                </div>
                <div class="form-group">
                    <label for="appointment_date">Appointment Date</label>
                    <input type="date" id="appointment_date" name="appointment_date" required>
                </div>
                <div class="form-group">
                    <label for="appointment_time">Appointment Time</label>
                    <input type="time" id="appointment_time" name="appointment_time" required>
                </div>
                <div class="form-group">
                    <label for="appointment_status">Status</label>
                    <select id="appointment_status" name="appointment_status" required>
                        <option value="Pending">Pending</option>
                        <option value="Confirmed">Confirmed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn-save">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
    const editButtons = document.querySelectorAll('.btn-edit');
    const modal = document.getElementById('edit-modal');
    const closeModal = document.getElementById('close-modal');
    const form = document.getElementById('edit-form');

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('id').value = button.getAttribute('data-id');
            document.getElementById('patient_name').value = button.getAttribute('data-name');
            document.getElementById('patient_age').value = button.getAttribute('data-age');
            document.getElementById('contact_number').value = button.getAttribute('data-contact');
            document.getElementById('appointment_date').value = button.getAttribute('data-date');
            document.getElementById('appointment_time').value = button.getAttribute('data-time');
            document.getElementById('appointment_status').value = button.getAttribute('data-status');

            modal.style.display = 'flex';
        });
    });

    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        fetch('update_appointment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: params.toString(),
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    location.reload(); // Reload page on success
                }
            })
            .catch(error => console.error('Error:', error));
    });
    </script>
</body>

</html>