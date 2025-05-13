<?php
$db = new SQLite3('database.db');
$db->exec("CREATE TABLE IF NOT EXISTS users (username TEXT, password TEXT)");
$db->exec("DELETE FROM users");
$db->exec("INSERT INTO users (username, password) VALUES ('admin', 'admin')");
$db->exec("INSERT INTO users (username, password) VALUES ('user', 'test')");
echo "Database initialized with users.";
?>
