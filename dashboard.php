<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// DB Connection
$conn = new mysqli("localhost", "root", "", "PatientDB");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch patients
$sql = "SELECT patient_id, name, age, gender, disease, contact_number, address, admission_date FROM Patients";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { font-family: Arial; padding: 30px; }
        h1 { color: #333; }
        a.logout { float: right; margin-top: -30px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #aaa;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h1>Welcome to Information zone</h1>
    <p>Hello, <?= htmlspecialchars($_SESSION['name']) ?>!</p>
    <a href="logout.php" class="logout">Logout</a>

    <h2>Patient Records</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Patient ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Disease</th>
                <th>Contact Number</th>
                <th>Address</th>
                <th>Admission Date</th>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
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
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <td>
                            <a href="edit_patient.php?id=<?= $row['patient_id'] ?>">Edit</a> |
                            <a href="delete_patient.php?id=<?= $row['patient_id'] ?>" onclick="return confirm('Are you sure you want to delete this patient?');">Delete</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No patient records found.</p>
    <?php endif; ?>

</body>
</html>
