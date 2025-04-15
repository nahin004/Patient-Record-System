<?php
$servername = "localhost";
$username = "root";        // default XAMPP username
$password = "";            // default XAMPP password is empty
$database = "PatientDB";  // replace with your actual database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
