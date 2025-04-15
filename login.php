<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "PatientDB");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

// Form submission check
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize email input
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password (hashed using password_hash)
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No user found with that email!";
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <style>
        body { font-family: Arial; padding: 30px; background-color: #f4f4f4; }
        form {
            width: 300px; margin: auto;
            background: white; padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 10px; margin: 10px 0;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            padding: 10px 15px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .error {
            color: red; margin: 10px 0; text-align: center;
        }
        h2 { text-align: center; }
    </style>
</head>
<body>

    <h2>User Login</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Email:</label>
        <input type="text" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Login">
    </form>

    <p style="text-align: center;">Don't have an account? <a href="signup.php">Sign up here</a></p>

</body>
</html>
