<?php
session_start();

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Redirect to login if not logged in or not admin
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "PatientDB");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if patient_id is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $patient_id = intval($_GET['id']); // Ensure the ID is an integer

    // Prepare the SQL statement to delete the record
    $stmt = $conn->prepare("DELETE FROM Patients WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);

    if ($stmt->execute()) {
        echo "Patient record deleted successfully.";
        header("Location: view_patients.php"); // Redirect back to the view patients page
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo "Invalid request. No patient ID provided.";
}

// Close the database connection
$conn->close();
?>
