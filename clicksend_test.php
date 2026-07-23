<?php
require_once 'app/Config/Database.php';
try {
    $db = new Database();
    $db->query('SELECT id, phone_number, phone_normalized, email FROM users WHERE phone_normalized IS NOT NULL OR phone_number IS NOT NULL LIMIT 5');
    $rows = $db->resultSet();
    echo json_encode($rows, JSON_PRETTY_PRINT) . PHP_EOL;
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
