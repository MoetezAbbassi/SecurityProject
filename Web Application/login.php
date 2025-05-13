<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fake firewall detection — log suspicious patterns
    if (preg_match("/('|--|;|=|OR|AND)/i", $username . $password)) {
        $log = "[" . date("Y-m-d H:i:s") . "] Suspicious input from IP: " . $_SERVER['REMOTE_ADDR'] . " | Payload: $username / $password" . PHP_EOL;
        file_put_contents("log.txt", $log, FILE_APPEND);
        echo "<p style='color:red;'>[ALERT] Suspicious activity detected!</p>";
    }

    // VULNERABLE QUERY (no sanitization)
    $query = "SELECT * FROM users WHERE username='$username' AND password='" . md5($password) . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        header("Location: success.php");
        exit();
    } else {
        echo "<p style='color:red;'>Invalid username or password.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Vulnerable Login</h2>
    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
