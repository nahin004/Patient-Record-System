<?php
session_start();

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Redirect to login if not logged in or not admin
    exit;
}

// Display success or error message from the session
if (isset($_SESSION['message'])) {
    // Style based on success/error message type
    $messageClass = ($_SESSION['message_type'] == 'error') ? 'error' : 'success';
    echo "<div class='message $messageClass'>" . htmlspecialchars($_SESSION['message']) . "</div>";

    // Clear the message after displaying
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Database connection
$conn = new mysqli("localhost", "root", "", "PatientDB");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all patient records
$sql = "SELECT patient_id, name, age, gender, disease, contact_number, address, admission_date FROM Patients";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patients</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f4f4f4;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        .message {
            padding: 15px;
            margin: 20px;
            border-radius: 5px;
            font-size: 16px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .actions a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Patient Records</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Disease</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Admission Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['patient_id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['age']) ?></td>
                        <td><?= htmlspecialchars($row['gender']) ?></td>
                        <td><?= htmlspecialchars($row['disease']) ?></td>
                        <td><?= htmlspecialchars($row['contact_number']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td><?= htmlspecialchars($row['admission_date']) ?></td>
                        <td class="actions">
                            <a href="edit_patient.php?id=<?= $row['patient_id'] ?>">Edit</a>
                            <a href="delete_patient.php?id=<?= $row['patient_id'] ?>" onclick="return confirm('Are you sure you want to delete this patient?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No patient records found.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>
