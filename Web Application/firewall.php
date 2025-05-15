<?php

// 🚨 Load & Analyze Input
function run_firewall($username, $password) {
    $input = $username . $password;
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
    $timestamp = date("Y-m-d H:i:s");

    $severity = calculate_severity($input);
    $is_suspicious = detect_sqli($input);

    if ($is_suspicious) {
        $message = "[$timestamp] IP: $ip | Payload: $input | Agent: $agent | Severity: $severity\n";
        file_put_contents("log.txt", $message, FILE_APPEND);

        // 🗂️ Also log to CSV for later analytics
        $csv = fopen("log.csv", "a");
        fputcsv($csv, [$timestamp, $ip, $input, $agent, $severity]);
        fclose($csv);

        echo "<p style='color:red'>⚠️ ALERT: Suspicious input detected and logged. Severity: $severity</p>";

        // 📬 Email alert system (optional, disabled)
        // send_alert_email($ip, $input, $agent, $severity);
    }

    update_ip_counter($ip);
}

// 🔍 SQL Injection Detection
function detect_sqli($input) {
    return preg_match("/(\b(SELECT|UNION|INSERT|UPDATE|DELETE|DROP|--|#|OR|AND)\b|['\";=])/i", $input);
}

// 🧮 Severity Estimation (score 1–10)
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

// 📊 IP Frequency Tracker (for manual review)
function update_ip_counter($ip) {
    $ip_file = "ip_hits.txt";
    $lines = file_exists($ip_file) ? file($ip_file, FILE_IGNORE_NEW_LINES) : [];
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

    file_put_contents($ip_file, $out);

    if ($ip_counts[$ip] >= 5) {
        echo "<p style='color:orange'>⚠️ Warning: IP $ip has attempted $ip_counts[$ip] logins. Consider blocking.</p>";
    }
}

// 📬 Optional Email Alert (disabled by default)
function send_alert_email($ip, $input, $agent, $severity) {
    $to = "admin@example.com";
    $subject = "🚨 SQLi Alert Detected";
    $body = "Suspicious input detected!\nIP: $ip\nPayload: $input\nUser Agent: $agent\nSeverity: $severity";
    $headers = "From: firewall@yourdomain.com";

    // ⚠️ Only enable if mail() works in your environment
    // mail($to, $subject, $body, $headers);
}

?>
