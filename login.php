<?php
session_start();
include 'database.php'; // This should contain your DB connection ($conn)

// Handle login
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Simple security check
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query the user
    $sql = "SELECT * FROM admin_login WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        // Check password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login">
    <h2>Enter username and password to Login</h2>
    <?php if ($error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    </div>
</body>
</html>
