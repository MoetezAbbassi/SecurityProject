<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    file_put_contents("database.txt", "$username,$password\n", FILE_APPEND);
    echo "<p style='color: green;'>User registered successfully!</p>";
}
?>

<!-- Fancy Registration Form -->
<link rel="stylesheet" href="style.css">
<form method="POST">
    <h2>Register</h2>
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Register</button>
</form>
