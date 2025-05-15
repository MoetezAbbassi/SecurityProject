<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    // 🛡️ INCLUDE FAKE FIREWALL (modular logger)
    require_once "firewall.php";
    run_firewall($username, $password); // detection/logging

    // 📖 READ users.txt LINE-BY-LINE
    $found = false;
    $file = fopen("users.txt", "r");

    if ($file) {
        while (($line = fgets($file)) !== false) {
            $line = trim($line);
            if (empty($line)) continue;

            [$stored_user, $stored_pass] = explode(",", $line);
            
            if ($username === $stored_user && $password === $stored_pass) {
                $found = true;
                break;
            }
        }
        fclose($file);
    } else {
        $error = "⚠️ Could not open users.txt";
    }

    if ($found) {
        $_SESSION["username"] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "❌ Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Insecure Web App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>🔐 Login to Continue</h2>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div>
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</body>
</html>
