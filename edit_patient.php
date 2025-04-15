<?php
session_start();

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Redirect to login if the user is not an admin
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "PatientDB");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get patient_id from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No patient ID provided.");
}

$patient_id = intval($_GET['id']); // Ensure it's an integer

// Fetch existing data
$sql = "SELECT * FROM Patients WHERE patient_id = $patient_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("No patient found with ID = $patient_id");
}

$row = $result->fetch_assoc();

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updates = [];

    if (!empty($_POST['name'])) {
        $name = $_POST['name'];
        $updates[] = "name = '$name'";
    }

    if (!empty($_POST['age'])) {
        $age = (int) $_POST['age'];
        if ($age > 0) {
            $updates[] = "age = $age";
        } else {
            echo "<script>alert('Age must be greater than 0.');</script>";
        }
    }

    if (!empty($_POST['gender'])) {
        $gender = $_POST['gender'];
        $updates[] = "gender = '$gender'";
    }

    if (!empty($_POST['disease'])) {
        $disease = $_POST['disease'];
        $updates[] = "disease = '$disease'";
    }

    if (!empty($_POST['contact_number'])) {
        $contact_number = $_POST['contact_number'];
        $updates[] = "contact_number = '$contact_number'";
    }

    if (!empty($_POST['address'])) {
        $address = $_POST['address'];
        $updates[] = "address = '$address'";
    }

    if (count($updates) > 0) {
        $update_sql = "UPDATE Patients SET " . implode(', ', $updates) . " WHERE patient_id = $patient_id";

        if ($conn->query($update_sql) === TRUE) {
            echo "Patient information updated successfully!";
            header("Location: admin_dashboard.php"); // Redirect to the admin dashboard after updating
            exit;
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "No fields provided to update.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Patient</title>
    <link rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-size: cover;          
            background-position: center;     
            background-repeat: no-repeat;    
            font-family: Arial, sans-serif;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #444;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #43a047;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Update Patient Information</h2>
    <form method="POST">
    <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required><br>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Others">Others</option>
            </select><br>

            <label for="disease">Disease:</label>
            <input type="text" id="disease" name="disease" required><br>

            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number"><br>

            <label for="address">Address:</label>
            <textarea id="address" name="address"></textarea><br>

            <label for="admission_date">Admission Date:</label>
            <input type="date" id="admission_date" name="admission_date" required><br>

        <input type="submit" value="Update">
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
