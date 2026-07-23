<?php
/**
 * SMTP Debug Script for WasteWatch Application
 * Run this from CLI: php email_otp_debug.php [test_recipient_email]
 */

require_once 'app/Models/Helpers/OtpMailer.php';

$recipient = $argv[1] ?? null;

echo "=== WasteWatch SMTP Debugger ===\n";

if (!$recipient) {
    echo "Usage: php email_otp_debug.php [recipient_email]\n";
    echo "No recipient email provided. Checking configuration parameters from .env...\n\n";
    
    $envFile = __DIR__ . '/.env';
    if (!file_exists($envFile)) {
        echo "ERROR: .env file does not exist.\n";
        exit(1);
    }
    
    $env = parse_ini_file($envFile, true);
    
    echo "SMTP Configured Settings:\n";
    echo "Host:     " . ($env['SMTP_HOST'] ?? 'Not set') . "\n";
    echo "Port:     " . ($env['SMTP_PORT'] ?? 'Not set') . "\n";
    echo "Username: " . ($env['SMTP_USERNAME'] ?? 'Not set') . "\n";
    echo "Secure:   " . ($env['SMTP_SECURE'] ?? 'Not set') . "\n";
    echo "From:     " . ($env['MAIL_FROM_ADDRESS'] ?? 'Not set') . "\n";
    echo "Name:     " . ($env['MAIL_FROM_NAME'] ?? 'Not set') . "\n\n";
    
    echo "To test mail delivery, run: php email_otp_debug.php your-email@example.com\n";
    exit(0);
}

$otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
echo "Attempting to send OTP test email...\n";
echo "Recipient: {$recipient}\n";
echo "Test OTP:  {$otp}\n\n";

try {
    // We will enable SMTP Debug inside PHPMailer to get detailed connection logs!
    // Since OtpMailer.php creates PHPMailer inside sendOtpEmail, let's capture any output or test connection
    OtpMailer::sendOtpEmail($recipient, $otp, 'Test User');
    echo "SUCCESS: Test email sent successfully to {$recipient}!\n";
} catch (Exception $e) {
    echo "ERROR: Failed to send email. Details below:\n";
    echo $e->getMessage() . "\n";
}
