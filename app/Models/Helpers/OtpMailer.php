<?php

class OtpMailer
{
    public static function sendOtpEmail($toEmail, $otp, $userName = '')
    {
                $autoloadPath = dirname(__DIR__, 3) . '/vendor/autoload.php';
        if (!file_exists($autoloadPath)) {
            throw new Exception('PHPMailer is not installed yet. Run composer require phpmailer/phpmailer from the project root.');
        }

        require_once $autoloadPath;

        $envFile = dirname(__DIR__, 3) . '/.env';
        $env = file_exists($envFile) ? parse_ini_file($envFile, true) : [];

        $host = $env['SMTP_HOST'] ?? getenv('SMTP_HOST') ?? 'smtp.gmail.com';
        $port = $env['SMTP_PORT'] ?? getenv('SMTP_PORT') ?? 587;
        $username = $env['SMTP_USERNAME'] ?? getenv('SMTP_USERNAME') ?? '';
        $password = $env['SMTP_PASSWORD'] ?? getenv('SMTP_PASSWORD') ?? '';
        $secure = $env['SMTP_SECURE'] ?? getenv('SMTP_SECURE') ?? 'tls';
        $fromAddress = $env['MAIL_FROM_ADDRESS'] ?? getenv('MAIL_FROM_ADDRESS') ?? $username;
        $fromName = $env['MAIL_FROM_NAME'] ?? getenv('MAIL_FROM_NAME') ?? 'Barangay Waste App';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = $host;
        $mail->Port = (int) $port;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $secure;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($fromAddress, $fromName);
        $mail->addAddress($toEmail);

        $mail->Subject = 'Your verification code';
        $mail->isHTML(true);
        $mail->Body = "
            <p>Hello {$userName},</p>
            <p>Your verification code is <strong>{$otp}</strong>.</p>
            <p>This code expires in 10 minutes.</p>
        ";
        $mail->AltBody = "Hello {$userName}, your verification code is {$otp}. It expires in 10 minutes.";

        $mail->send();
    }
    /**
     * Send temporary password email for new staff accounts.
     */
    public static function sendTempPasswordEmail($toEmail, $tempPassword, $userName = '')
    {
        $autoloadPath = dirname(__DIR__, 3) . '/vendor/autoload.php';
        if (!file_exists($autoloadPath)) {
            throw new Exception('PHPMailer is not installed.');
        }
        require_once $autoloadPath;

        $envFile = dirname(__DIR__, 3) . '/.env';
        $env = file_exists($envFile) ? parse_ini_file($envFile) : [];

        $host = $env['SMTP_HOST'] ?? getenv('SMTP_HOST') ?? 'smtp.gmail.com';
        $port = $env['SMTP_PORT'] ?? getenv('SMTP_PORT') ?? 587;
        $username = $env['SMTP_USERNAME'] ?? getenv('SMTP_USERNAME') ?? '';
        $password = $env['SMTP_PASSWORD'] ?? getenv('SMTP_PASSWORD') ?? '';
        $secure = $env['SMTP_SECURE'] ?? getenv('SMTP_SECURE') ?? 'tls';
        $fromAddress = $env['MAIL_FROM_ADDRESS'] ?? getenv('MAIL_FROM_ADDRESS') ?? $username;
        $fromName = $env['MAIL_FROM_NAME'] ?? getenv('MAIL_FROM_NAME') ?? 'Barangay Waste App';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = $host;
        $mail->Port = (int) $port;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $secure;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($fromAddress, $fromName);
        $mail->addAddress($toEmail);

        $mail->Subject = 'Your Staff Account Credentials';
        $mail->isHTML(true);
        $mail->Body = "
            <p>Hello {$userName},</p>
            <p>Your staff account has been created for the Barangay Waste Reporting System.</p>
            <p><strong>Username:</strong> {$userName}</p>
            <p><strong>Temporary Password:</strong> {$tempPassword}</p>
            <p>Please log in and change your password immediately.</p>
            <p><a href='" . self::getBaseUrl() . "/auth'>Log in here</a></p>
            <p>This password will expire in 24 hours.</p>
        ";
        $mail->AltBody = "Hello {$userName}, your temporary password is {$tempPassword}. Log in at " . self::getBaseUrl() . "/auth";

        $mail->send();
    }

    /**
     * Helper to get base URL.
     */
    private static function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        return $protocol . $_SERVER['HTTP_HOST'] . '/brgy-waste-app-v3/public';
    }

        /**
     * Send password reset OTP email.
     */
    public static function sendPasswordResetEmail($toEmail, $otp, $userName = '')
    {
        $autoloadPath = dirname(__DIR__, 3) . '/vendor/autoload.php';
        if (!file_exists($autoloadPath)) {
            throw new Exception('PHPMailer is not installed.');
        }
        require_once $autoloadPath;

        $envFile = dirname(__DIR__, 3) . '/.env';
        $env = file_exists($envFile) ? parse_ini_file($envFile) : [];

        $host = $env['SMTP_HOST'] ?? getenv('SMTP_HOST') ?? 'smtp.gmail.com';
        $port = $env['SMTP_PORT'] ?? getenv('SMTP_PORT') ?? 587;
        $username = $env['SMTP_USERNAME'] ?? getenv('SMTP_USERNAME') ?? '';
        $password = $env['SMTP_PASSWORD'] ?? getenv('SMTP_PASSWORD') ?? '';
        $secure = $env['SMTP_SECURE'] ?? getenv('SMTP_SECURE') ?? 'tls';
        $fromAddress = $env['MAIL_FROM_ADDRESS'] ?? getenv('MAIL_FROM_ADDRESS') ?? $username;
        $fromName = $env['MAIL_FROM_NAME'] ?? getenv('MAIL_FROM_NAME') ?? 'Barangay Waste App';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = $host;
        $mail->Port = (int) $port;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $secure;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($fromAddress, $fromName);
        $mail->addAddress($toEmail);

        $mail->Subject = 'Reset Your Password';
        $mail->isHTML(true);
        $mail->Body = "
            <p>Hello {$userName},</p>
            <p>You requested to reset your password. Your verification code is <strong>{$otp}</strong>.</p>
            <p>This code expires in 10 minutes. If you did not request this, please ignore this email.</p>
        ";
        $mail->AltBody = "Hello {$userName}, your password reset code is {$otp}. Expires in 10 minutes.";

        $mail->send();
    }
}
