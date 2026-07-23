<?php
require_once 'app/Config/Database.php';
try {
    $db = new Database();
    echo "--- sms_otps schema ---\n";
    $db->query('SHOW CREATE TABLE sms_otps');
    $row = $db->single();
    if ($row && isset($row['Create Table'])) {
        echo $row['Create Table'] . "\n";
    } else {
        echo "sms_otps table not found\n";
    }

    echo "\n--- sms_rate_limits schema ---\n";
    $db->query('SHOW CREATE TABLE sms_rate_limits');
    $row = $db->single();
    if ($row && isset($row['Create Table'])) {
        echo $row['Create Table'] . "\n";
    } else {
        echo "sms_rate_limits table not found\n";
    }

    echo "\n--- latest sms_otps rows ---\n";
    $db->query('SELECT id, user_id, phone, token, expires_at, last_sent_at, attempts, is_used, created_at FROM sms_otps ORDER BY created_at DESC LIMIT 5');
    $rows = $db->resultSet();
    echo json_encode($rows, JSON_PRETTY_PRINT) . "\n";
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}
