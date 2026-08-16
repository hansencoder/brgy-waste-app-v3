<?php
require_once __DIR__ . '/../app/init.php';

$db = new Database();
$db->query("DESCRIBE barangays");
echo "BARANGAYS:\n";
foreach ($db->resultSet() as $row) {
    echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

$db->query("DESCRIBE puroks");
echo "\nPUROKS:\n";
foreach ($db->resultSet() as $row) {
    echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

$db->query("SELECT id, latitude, longitude, status_id, category_id, purok_id FROM reports WHERE latitude IS NOT NULL LIMIT 5");
echo "\nREPORTS SAMPLE:\n";
print_r($db->resultSet());

$db->query("SELECT * FROM barangays LIMIT 1");
echo "\nBARANGAY ROW:\n";
print_r($db->single());

$db->query("SELECT purok_id, purok_name, boundary_coordinates, color FROM puroks");
echo "\nPUROKS LIST:\n";
print_r($db->resultSet());
