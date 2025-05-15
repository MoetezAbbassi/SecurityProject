<?php
// 📦 CONNECT TO SQLite DB (create if doesn't exist)
$db = new SQLite3('database.db');

// 🔨 CREATE users TABLE (if not exists)
$db->exec("CREATE TABLE IF NOT EXISTS users (
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
)");

// 📖 READ users.txt LINE-BY-LINE
$lines = file('users.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$addedCount = 0;

foreach ($lines as $line) {
    [$username, $password] = explode(',', trim($line));

    // 🔒 HASH PASSWORD WITH MD5 (per your vulnerable demo spec)
    $hashedPassword = md5($password);

    // ⚙️ INSERT INTO DATABASE
    $stmt = $db->prepare("INSERT OR IGNORE INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);
    $result = $stmt->execute();

    if ($result) {
        $addedCount++;
    }
}

// ✅ Display message in browser
echo "<h2>✅ Seeding Complete</h2>";
echo "<p>Added $addedCount users to <code>database.db</code></p>";
echo "<a href='login.php'>🔐 Go to Login Page</a>";
?>
