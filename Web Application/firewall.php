<?php

function run_firewall($username, $password) {
    $input = $username . $password;
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
    $timestamp = date("Y-m-d H:i:s");

    $severity = calculate_severity($input);
    $is_suspicious = detect_sqli($input);

    if ($is_suspicious) {
        // 🔥 TEXT LOG
        $txtLog = "[$timestamp] IP: $ip | Payload: $input | Agent: $agent | Severity: $severity\n";
        file_put_contents("log.txt", $txtLog, FILE_APPEND);

        // 🔥 CSV LOG
        $csvFile = fopen("log.csv", "a");
        fputcsv($csvFile, [$timestamp, $ip, $input, $agent, $severity]);
        fclose($csvFile);

        echo "<p style='color:red'>⚠️ ALERT: Suspicious input logged. Severity: $severity</p>";
    }

    update_ip_counter($ip);
}

// 🔍 Detect SQLi patterns
function detect_sqli($input) {
    return preg_match("/(\b(SELECT|UNION|INSERT|UPDATE|DELETE|DROP|--|#|OR|AND)\b|['\";=])/i", $input);
}

// 🧮 Estimate attack severity (1–10)
function calculate_severity($input) {
    $score = 0;
    $patterns = [
        "' OR '1'='1" => 3,
        "--"          => 2,
        "UNION"       => 3,
        "SELECT"      => 2,
        "#"           => 1,
        "="           => 1,
        ";"           => 1,
        "DROP"        => 4,
        "INSERT"      => 2,
        "UPDATE"      => 2,
        "DELETE"      => 2
    ];

    foreach ($patterns as $pattern => $value) {
        if (stripos($input, $pattern) !== false) {
            $score += $value;
        }
    }

    return min($score, 10);
}

// 📊 Track IP login attempts
function update_ip_counter($ip) {
    $file = "ip_hits.txt";
    $lines = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
    $ip_counts = [];

    foreach ($lines as $line) {
        [$logged_ip, $count] = explode("|", $line);
        $ip_counts[$logged_ip] = (int)$count;
    }

    $ip_counts[$ip] = ($ip_counts[$ip] ?? 0) + 1;

    $out = "";
    foreach ($ip_counts as $logged_ip => $count) {
        $out .= "$logged_ip|$count\n";
    }

    file_put_contents($file, $out);

    if ($ip_counts[$ip] >= 5) {
        echo "<p style='color:orange'>⚠️ IP $ip has attempted $ip_counts[$ip] times. Consider monitoring.</p>";
    }
}
