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
}
