<?php
$db = new SQLite3('database.db');

// Create users table
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    password TEXT NOT NULL
)");

// Insert sample users (plain-text for demonstration/vulnerability)
$db->exec("INSERT INTO users (username, password) VALUES ('admin', 'admin')");
$db->exec("INSERT INTO users (username, password) VALUES ('user', 'user')");

echo "Database and users table created.";
?>
