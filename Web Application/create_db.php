<?php
$dbFile = 'database.db';

// Delete existing (bad) DB file
if (file_exists($dbFile)) {
    unlink($dbFile);
}

$db = new SQLite3($dbFile);

// Check if the DB is writable
if (!$db) {
    die("Failed to open database.");
}

// Create users table
$result = $db->exec("CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    password TEXT NOT NULL
)");

if (!$result) {
    die("Failed to create table: " . $db->lastErrorMsg());
}

// Insert test users
$db->exec("INSERT INTO users (username, password) VALUES ('admin', 'admin')");
$db->exec("INSERT INTO users (username, password) VALUES ('user', 'user')");

echo "✅ Fresh database created with users: admin/admin, user/user";
