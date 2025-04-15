<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "PatientDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $disease = $_POST['disease'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $admission_date = $_POST['admission_date'];

    $sql = "INSERT INTO Patients (name, age, gender, disease, contact_number, address, admission_date)
            VALUES ('$name', '$age', '$gender', '$disease', '$contact_number', '$address', '$admission_date')";

    if ($conn->query($sql) === TRUE) {
        $message = "New record created successfully";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Data Store</title>
    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Daffodil Health care Patient Record </h1>

        <?php
        if (isset($message)) {
            echo "<p class='message'>$message</p>";
        }
        ?>

        <form action="insert_patient.php" method="POST">
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

            <input type="SUBMIT" value="SUBMIT">
        </form>
    </div>
</body>
</html>
