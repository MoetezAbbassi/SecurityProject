<?php
// CONFIG - UPDATE THESE VALUES FROM INFINITYFREE
$servername = "sql311.infinityfree.com"; // Replace with your actual MySQL host
$username_db = "if0_38974084 ";    // Your InfinityFree username
$password_db = "toS31ptbxTo ";   // Your MySQL password
$dbname = "if0_38974084_test"; // Your database name

// CONNECT TO DB
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// CHECK CONNECTION
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// PART 1: INSECURE LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = md5($_POST['password']); // weak MD5 hash

    // SQL injection-vulnerable query
    $query = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        echo "✅ Login SUCCESS (but this system is vulnerable)";
    } else {
        echo "❌ Login FAILED";
    }

    
    // PART 2: FAKE FIREWALL LOGGING
    $payload = $_POST['username'] . $_POST['password'];
    if (preg_match("/(\b(SELECT|UNION|INSERT|UPDATE|DELETE|DROP|--|#|OR|AND)\b|['\";=])/i", $payload)) {
        $log = "[" . date("Y-m-d H:i:s") . "] IP: " . $_SERVER['REMOTE_ADDR'] .
               " - Payload: $payload" .
               " - Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
        file_put_contents("log.txt", $log, FILE_APPEND);
        echo "<br><span style='color:red'>⚠️ ALERT: Suspicious input logged.</span>";
    }
}
?>

<!-- HTML LOGIN FORM -->
<h2>🔐 Demo Login</h2>
<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>