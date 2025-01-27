<?php
$conn = new mysqli('localhost', 'root', '', 'your_database');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, Name, Ph_Number, Email, Citizenship_no, Address, Password, Date_of_birth, Role FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $role = ($row['Role'] == 'admin') ? 'Hospital Admin' : 'User';
        echo "<tr>
                <td>{$row['Name']}</td>
                <td>{$row['Ph_Number']}</td>
                <td>{$row['Email']}</td>
                <td>{$row['Citizenship_no']}</td>
                <td>{$row['Address']}</td>
                <td>{$row['Password']}</td>
                <td>{$row['Date_of_birth']}</td>
                <td>{$role}</td>
                <td>
                    <button onclick=\"openEditModal({$row['id']}, '{$row['Name']}', '{$row['Ph_Number']}', '{$row['Email']}')\">Edit</button>
                    <button class='delete' onclick=\"deleteUser({$row['id']})\">Delete</button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='9' style='text-align:center;'>No records found</td></tr>";
}

$conn->close();
?>