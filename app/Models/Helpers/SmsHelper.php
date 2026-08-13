<?php

/**
 * SmsHelper
 * 
 * Handles SMS OTP and status notification sending for guest reporters.
 * In development/local mode (no SMS_API_KEY configured), messages are
 * logged to storage/sms_log.txt instead of sent via real gateway.
 */
class SmsHelper
{
    // ============================================================
    // SEND OTP SMS
    // ============================================================
    public static function sendOtp(string $phone, string $otp, string $guestName = ''): bool
    {
        $name = !empty($guestName) ? $guestName : 'Guest';
        $message = "WasteWatch: Your verification code is {$otp}. Valid for 5 minutes. Do not share this code with anyone.";
        return self::send($phone, $message);
    }

    // ============================================================
    // SEND STATUS UPDATE SMS
    // ============================================================
    public static function sendStatusUpdate(string $phone, string $trackingNumber, string $status, string $guestName = ''): bool
    {
        $statusMessages = [
            'pending'     => "Your waste report {$trackingNumber} has been received and is pending verification.",
            'verified'    => "Good news! Your waste report {$trackingNumber} has been verified and will be actioned.",
            'in_progress' => "Update: Your waste report {$trackingNumber} is now being actively addressed by our team.",
            'resolved'    => "Your waste report {$trackingNumber} has been resolved. Thank you for helping keep our barangay clean!",
            'rejected'    => "Your waste report {$trackingNumber} could not be processed. Track your report at: /brgy-waste-app-v3/public/index.php?url=guest/track"
        ];

        $message = $statusMessages[$status] ?? "Your waste report {$trackingNumber} status has been updated to: {$status}.";
        $message .= " - WasteWatch";
        return self::send($phone, $message);
    }

    // ============================================================
    // CORE SEND METHOD (Gateway or Dev Logger)
    // ============================================================
    private static function send(string $phone, string $message): bool
    {
        $envFile = dirname(__DIR__, 3) . '/.env';
        $env = file_exists($envFile) ? parse_ini_file($envFile, true) : [];

        $apiKey = $env['SMS_API_KEY'] ?? getenv('SMS_API_KEY') ?? '';
        $senderId = $env['SMS_SENDER_ID'] ?? getenv('SMS_SENDER_ID') ?? 'WasteWatch';
        $gateway = $env['SMS_GATEWAY'] ?? getenv('SMS_GATEWAY') ?? 'semaphore';

        // Dev mode: no API key configured → log to file
        if (empty($apiKey)) {
            return self::logToFile($phone, $message);
        }

        // Production: send via SMS gateway
        try {
            if ($gateway === 'semaphore') {
                return self::sendViaSemaphore($apiKey, $senderId, $phone, $message);
            }
            // Fallback: log
            return self::logToFile($phone, $message);
        } catch (Exception $e) {
            error_log('[SmsHelper] Failed to send SMS to ' . $phone . ': ' . $e->getMessage());
            return false;
        }
    }

    // ============================================================
    // SEMAPHORE GATEWAY
    // ============================================================
    private static function sendViaSemaphore(string $apiKey, string $senderId, string $phone, string $message): bool
    {
        $url = 'https://api.semaphore.co/api/v4/messages';
        $data = [
            'apikey'      => $apiKey,
            'number'      => $phone,
            'message'     => $message,
            'sendername'  => $senderId,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode >= 200 && $httpCode < 300;
    }

    // ============================================================
    // DEV MODE: Log SMS to file instead of sending
    // ============================================================
    private static function logToFile(string $phone, string $message): bool
    {
        $logDir = dirname(__DIR__, 3) . '/storage';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        $logPath = $logDir . '/sms_log.txt';
        $timestamp = date('Y-m-d H:i:s');
        $entry = "[{$timestamp}] TO:{$phone}\nMSG: {$message}\n" . str_repeat('-', 60) . "\n";
        return file_put_contents($logPath, $entry, FILE_APPEND | LOCK_EX) !== false;
    }
}
