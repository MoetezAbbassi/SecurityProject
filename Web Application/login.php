<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fake firewall detection
    if (preg_match("/('|--|;|=|OR|AND|UNION|SELECT|FROM|WHERE)/i", $username . $password)) {
        $log = "[" . date("Y-m-d H:i:s") . "] Suspicious input from IP: " . $_SERVER['REMOTE_ADDR'] . " | Payload: $username / $password" . PHP_EOL;
        file_put_contents("log.txt", $log, FILE_APPEND);
        echo "<p style='color:red;'>[ALERT] Suspicious activity detected!</p>";
        
        // If attacker uses specific payload, serve the database file
        if (strpos($username, 'UNION SELECT') !== false || strpos($username, '1=1') !== false) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="stolen_database.txt"');
            readfile($db_file);
            exit();
        }
    }

    // Simulate vulnerable authentication
    $lines = file($db_file, FILE_IGNORE_NEW_LINES);
    $authenticated = false;
    
    // Skip header line
    for ($i = 1; $i < count($lines); $i++) {
        $fields = explode(',', $lines[$i]);
        if (count($fields) >= 3) {
            $db_username = $fields[1];
            $db_password = $fields[2];
            
            // Vulnerable comparison - simulating SQL injection
            if ($db_username == $username && $db_password == md5($password)) {
                $authenticated = true;
                break;
            }
            
            // Simulate SQL injection vulnerability
            if (strpos($username, "' OR '1'='1") !== false) {
                $authenticated = true;
                break;
            }
        }
    }

    if ($authenticated) {
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
    <title>Vulnerable Login</title>
</head>
<body>
    <h2>File-Based Vulnerable Login</h2>
    <p>This system uses a text file as a "database" and is vulnerable to simulated SQL injection.</p>
    
    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    
    <div style="margin-top: 20px; border-top: 1px solid #ccc; padding-top: 10px;">
        <h3>Try these simulated SQLi payloads:</h3>
        <ul>
            <li><code>admin' --</code> (leave password empty)</li>
            <li><code>' OR '1'='1</code> (with any password)</li>
            <li><code>' UNION SELECT 1,2,3 --</code> (will download database.txt)</li>
        </ul>
    </div>
</body>
</html>