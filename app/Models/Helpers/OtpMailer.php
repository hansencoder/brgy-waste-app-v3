<?php

class OtpMailer
{
    /**
     * Send general verification / OTP email.
     */
    public static function sendOtpEmail($toEmail, $otp, $userName = '')
    {
        $subject = 'Your verification code';
        $htmlBody = "
            <div style='font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; rounded: 8px;'>
                <h2 style='color: #15281f;'>Barangay Waste Management</h2>
                <p>Hello " . htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') . ",</p>
                <p>Your verification code is: <strong style='font-size: 24px; color: #166534; letter-spacing: 4px;'>" . htmlspecialchars($otp, ENT_QUOTES, 'UTF-8') . "</strong></p>
                <p style='color: #64748b;'>This code expires in 10 minutes. Please do not share this code with anyone.</p>
            </div>
        ";
        $altBody = "Hello {$userName}, your verification code is {$otp}. It expires in 10 minutes.";

        return self::sendMail($toEmail, $subject, $htmlBody, $altBody, $userName);
    }

    /**
     * Send temporary password email for new staff accounts.
     */
    public static function sendTempPasswordEmail($toEmail, $tempPassword, $userName = '')
    {
        $baseUrl = self::getBaseUrl();
        $subject = 'Your Staff Account Credentials';
        $htmlBody = "
            <div style='font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; rounded: 8px;'>
                <h2 style='color: #15281f;'>Barangay Waste Management</h2>
                <p>Hello " . htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') . ",</p>
                <p>Your staff account has been created for the Barangay Waste Reporting System.</p>
                <p><strong>Username:</strong> " . htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') . "</p>
                <p><strong>Temporary Password:</strong> <code>" . htmlspecialchars($tempPassword, ENT_QUOTES, 'UTF-8') . "</code></p>
                <p>Please log in and change your password immediately.</p>
                <p><a href='{$baseUrl}/auth' style='display: inline-block; padding: 10px 20px; background-color: #15281f; color: #fff; text-decoration: none; border-radius: 6px;'>Log in here</a></p>
                <p style='color: #64748b;'>This password will expire in 24 hours.</p>
            </div>
        ";
        $altBody = "Hello {$userName}, your temporary password is {$tempPassword}. Log in at {$baseUrl}/auth";

        return self::sendMail($toEmail, $subject, $htmlBody, $altBody, $userName);
    }

    /**
     * Send password reset OTP email.
     */
    public static function sendPasswordResetEmail($toEmail, $otp, $userName = '')
    {
        $subject = 'Reset Your Password';
        $htmlBody = "
            <div style='font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; rounded: 8px;'>
                <h2 style='color: #15281f;'>Barangay Waste Management</h2>
                <p>Hello " . htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') . ",</p>
                <p>You requested to reset your password. Your verification code is:</p>
                <p><strong style='font-size: 24px; color: #dc2626; letter-spacing: 4px;'>" . htmlspecialchars($otp, ENT_QUOTES, 'UTF-8') . "</strong></p>
                <p style='color: #64748b;'>This code expires in 10 minutes. If you did not request this, please ignore this email.</p>
            </div>
        ";
        $altBody = "Hello {$userName}, your password reset code is {$otp}. Expires in 10 minutes.";

        return self::sendMail($toEmail, $subject, $htmlBody, $altBody, $userName);
    }

    /**
     * Core Mail Dispatcher: Tries Brevo REST API first, then PHPMailer / SMTP fallback.
     */
    private static function sendMail($toEmail, $subject, $htmlBody, $altBody, $userName = '')
    {
        $envFile = dirname(__DIR__, 3) . '/.env';
        $env = file_exists($envFile) ? parse_ini_file($envFile, true) : [];

        $apiKey = $env['BREVO_API_KEY'] ?? $env['SMS_API_KEY'] ?? getenv('BREVO_API_KEY') ?? getenv('SMS_API_KEY') ?? '';
        $fromAddress = $env['MAIL_FROM_ADDRESS'] ?? getenv('MAIL_FROM_ADDRESS') ?? 'floreshans.neust@gmail.com';
        $fromName = $env['MAIL_FROM_NAME'] ?? getenv('MAIL_FROM_NAME') ?? 'Linaraya';

        // 1. Try Brevo REST API
        if (!empty($apiKey) && strpos($apiKey, 'xkeysib-') === 0) {
            try {
                if (self::sendViaBrevoApi($apiKey, $fromAddress, $fromName, $toEmail, $userName, $subject, $htmlBody)) {
                    return true;
                }
            } catch (Exception $e) {
                error_log('[OtpMailer] Brevo API failed: ' . $e->getMessage() . '. Falling back to SMTP...');
            }
        }

        // 2. Fallback to PHPMailer SMTP
        return self::sendViaPhpMailer($env, $toEmail, $subject, $htmlBody, $altBody, $fromAddress, $fromName);
    }

    /**
     * Send email via Brevo REST API.
     */
    private static function sendViaBrevoApi($apiKey, $fromAddress, $fromName, $toEmail, $userName, $subject, $htmlBody): bool
    {
        $url = 'https://api.brevo.com/v3/smtp/email';
        $payload = [
            'sender' => [
                'name' => $fromName,
                'email' => $fromAddress
            ],
            'to' => [
                [
                    'email' => $toEmail,
                    'name' => !empty($userName) ? $userName : 'User'
                ]
            ],
            'subject' => $subject,
            'htmlContent' => $htmlBody
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'api-key: ' . trim($apiKey)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        throw new Exception('Brevo API returned HTTP ' . $httpCode . ': ' . ($response ?: $curlError));
    }

    /**
     * Fallback: Send email via PHPMailer / SMTP.
     */
    private static function sendViaPhpMailer($env, $toEmail, $subject, $htmlBody, $altBody, $fromAddress, $fromName): bool
    {
        $autoloadPath = dirname(__DIR__, 3) . '/vendor/autoload.php';
        if (!file_exists($autoloadPath)) {
            throw new Exception('PHPMailer is not installed.');
        }
        require_once $autoloadPath;

        $host = $env['SMTP_HOST'] ?? 'smtp.gmail.com';
        $port = $env['SMTP_PORT'] ?? 587;
        $username = $env['SMTP_USERNAME'] ?? '';
        $password = $env['SMTP_PASSWORD'] ?? '';
        $secure = $env['SMTP_SECURE'] ?? 'tls';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->Port = (int)$port;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $secure;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($fromAddress, $fromName);
        $mail->addAddress($toEmail);
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $htmlBody;
        $mail->AltBody = $altBody;

        return $mail->send();
    }

    /**
     * Helper to get base URL.
     */
    private static function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $protocol . $host;
    }
}
