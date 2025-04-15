<?php include 'config.php'; ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = 'user'; // default role

    // Use prepared statement for security
    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        $message = "Signup successful! <a href='login.php'>Login now</a>";
    } else {
        $message = "Signup failed: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="signup-box">
        <h2>SIGN UP</h2>

        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>

        <form method="POST">
            <label>Name</label>
            <input type="text" name="name" placeholder="Enter your full name" required>

            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>

            <button type="submit">SIGN UP!</button>

            <p>Have an Account? <a href="login.php">Login Here!</a></p>
        </form>
    </div>
</body>
</html>
