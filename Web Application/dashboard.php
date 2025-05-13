<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");  // Redirect to login if not logged in
    exit();
}

// Get the username from the session
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Secure Web App</title>
    <!-- Include Bootstrap CSS (via CDN) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
        }
        .dashboard-container {
            margin-top: 50px;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2>Welcome to Your Dashboard</h2>
                    </div>
                    <div class="card-body">
                        <h4>Hello, <?= htmlspecialchars($username) ?>!</h4>
                        <p>You're now logged in. This is a secure area of the site that only authorized users can access.</p>
                        <p>Feel free to explore your dashboard, or use the options below:</p>
                        <hr>
                        <h5>Dashboard Features:</h5>
                        <ul>
                            <li>View your profile</li>
                            <li>Change your settings</li>
                            <li>Access secure information</li>
                        </ul>
                        <hr>
                        <div class="text-center">
                            <a href="logout.php" class="btn btn-danger">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS (via CDN) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
