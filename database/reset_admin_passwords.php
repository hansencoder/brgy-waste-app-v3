<?php
/**
 * ================================================
 * ADMIN PASSWORD RESET SCRIPT
 * ================================================
 * Run this script ONCE via your browser at:
 *   http://localhost/brgy-waste-app-v3/database/reset_admin_passwords.php
 *
 * This will reset both admin accounts to:
 *   Password: Password@123
 *
 * DELETE THIS FILE after running it for security!
 * ================================================
 */

// --- DB CONFIG (adjust if needed) ---
$host   = 'localhost';
$dbname = 'brgy_waste_db';
$user   = 'root';
$pass   = '';
// ------------------------------------

$password    = 'Password@123';
$hash        = password_hash($password, PASSWORD_BCRYPT);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Upsert captain account
    $stmt = $pdo->prepare("
        INSERT INTO users (id, name, address, phone_number, email, password, role, status)
        VALUES (1, 'Barangay Captain', 'Barangay Hall', '09123456789', 'captain@dulongbayan.ph', :hash, 'captain', 'active')
        ON DUPLICATE KEY UPDATE password = :hash2, status = 'active'
    ");
    $stmt->execute([':hash' => $hash, ':hash2' => $hash]);

    // Upsert secretary account
    $stmt = $pdo->prepare("
        INSERT INTO users (id, name, address, phone_number, email, password, role, status)
        VALUES (2, 'Barangay Secretary', 'Barangay Hall', '09123456788', 'secretary@dulongbayan.ph', :hash, 'secretary', 'active')
        ON DUPLICATE KEY UPDATE password = :hash2, status = 'active'
    ");
    $stmt->execute([':hash' => $hash, ':hash2' => $hash]);

    echo "<h2 style='color:green;font-family:monospace'>✅ Done!</h2>";
    echo "<p style='font-family:monospace'>Both admin accounts have been reset.</p>";
    echo "<table border='1' cellpadding='8' style='font-family:monospace;border-collapse:collapse'>";
    echo "<tr><th>Role</th><th>Email</th><th>Password</th></tr>";
    echo "<tr><td>Captain</td><td>captain@dulongbayan.ph</td><td>Password@123</td></tr>";
    echo "<tr><td>Secretary</td><td>secretary@dulongbayan.ph</td><td>Password@123</td></tr>";
    echo "</table>";
    echo "<p style='color:red;font-weight:bold;font-family:monospace'>⚠️ DELETE this file now for security!</p>";

} catch (PDOException $e) {
    echo "<h2 style='color:red;font-family:monospace'>❌ Error</h2>";
    echo "<pre style='font-family:monospace'>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<p style='font-family:monospace'>Check your DB credentials at the top of this file.</p>";
}
?>
