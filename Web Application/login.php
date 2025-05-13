<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    // Hash the entered password
    $hashed_password = md5($password);

    // Open database file
    $file = fopen("database.txt", "r");
    $found = false;

    if ($file) {
        while (($line = fgets($file)) !== false) {
            $line = trim($line);
            if (empty($line)) continue;
            
            list($file_username, $file_password) = explode(",", $line);

            if ($file_username === $username && $file_password === $hashed_password) {
                $found = true;
                break;
            }
        }
        fclose($file);
    }

    if ($found) {
        // Successful login
        $_SESSION["username"] = $username;  // Store username in session
        header("Location: dashboard.php");   // Redirect to the protected page/dashboard
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Secure Web App</title>
    <!-- Include Bootstrap CSS (via CDN) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            height: 100vh;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            padding-top: 50px;
        }
        .card {
            padding: 20px;
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card">
            <div class="card-header">Secure Login</div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Include Bootstrap JS (via CDN) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
