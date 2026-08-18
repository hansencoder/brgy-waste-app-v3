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
            'rejected'    => "Your waste report {$trackingNumber} could not be processed. Track your report at: /index.php?url=guest/track"
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
            $sent = false;
            if ($gateway === 'iprogsms') {
                $sent = self::sendViaIprogSms($apiKey, $phone, $message);
            } elseif ($gateway === 'brevo') {
                $sent = self::sendViaBrevo($apiKey, $senderId, $phone, $message);
            } elseif ($gateway === 'semaphore') {
                $sent = self::sendViaSemaphore($apiKey, $senderId, $phone, $message);
            }

            // If gateway call failed (e.g. insufficient credits), log to file as safety fallback
            if (!$sent) {
                self::logToFile($phone, "[GATEWAY FAILED] " . $message);
            }

            return $sent;
        } catch (Exception $e) {
            error_log('[SmsHelper] Failed to send SMS to ' . $phone . ': ' . $e->getMessage());
            self::logToFile($phone, "[ERROR: " . $e->getMessage() . "] " . $message);
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
    // BREVO GATEWAY
    // ============================================================
    private static function sendViaBrevo(string $apiKey, string $senderId, string $phone, string $message): bool
    {
        $normalizedPhone = self::normalizePhone($phone);
        $url = 'https://api.brevo.com/v3/transactionalSMS/sms';

        // Brevo sender name must be alphanumeric and max 11 chars
        $cleanSender = substr(preg_replace('/[^a-zA-Z0-9]/', '', $senderId), 0, 11);
        if (empty($cleanSender)) {
            $cleanSender = 'WasteWatch';
        }

        $payload = [
            'sender' => $cleanSender,
            'recipient' => $normalizedPhone,
            'content' => $message,
            'type' => 'transactional'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'api-key: ' . trim($apiKey),
            'Accept: application/json',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($httpCode < 200 || $httpCode >= 300) {
            error_log('[SmsHelper] Brevo SMS failed (HTTP ' . $httpCode . '): ' . ($response ?: $curlError));
            return false;
        }

        return true;
    }

    // ============================================================
    // IPROGSMS GATEWAY
    // ============================================================
    private static function sendViaIprogSms(string $apiKey, string $phone, string $message): bool
    {
        $localPhone = self::normalizePhoneLocal($phone);
        $url = 'https://www.iprogsms.com/api/v1/sms_messages';

        $data = [
            'api_token'    => trim($apiKey),
            'phone_number' => $localPhone,
            'message'      => $message,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            $json = json_decode($response, true);
            if (isset($json['status']) && ($json['status'] == 200 || $json['status'] === 'success')) {
                return true;
            }
            if (isset($json['message']) && stripos($json['message'], 'successfully') !== false) {
                return true;
            }
        }

        error_log('[SmsHelper] iProgSMS failed (HTTP ' . $httpCode . '): ' . ($response ?: $curlError));
        return false;
    }

    // ============================================================
    // PHONE NUMBER NORMALIZATION (Local PH Format 09XXXXXXXXX)
    // ============================================================
    public static function normalizePhoneLocal(string $phone): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 639 (12 digits), replace 63 with 0
        if (preg_match('/^63(9\d{9})$/', $cleaned, $matches)) {
            return '0' . $matches[1];
        }

        // If starts with 09 (11 digits), return as is
        if (preg_match('/^09\d{9}$/', $cleaned)) {
            return $cleaned;
        }

        // If starts with 9 (10 digits), prepend 0
        if (preg_match('/^9\d{9}$/', $cleaned)) {
            return '0' . $cleaned;
        }

        return $cleaned;
    }

    // ============================================================
    // PHONE NUMBER NORMALIZATION (E.164 / PH Format)
    // ============================================================
    public static function normalizePhone(string $phone): string
    {
        // Remove spaces, dashes, parentheses
        $cleaned = preg_replace('/[^0-9+]/', '', $phone);

        // If starts with +639, return as is
        if (preg_match('/^\+639\d{9}$/', $cleaned)) {
            return $cleaned;
        }

        // If starts with 639 (12 digits), prepend +
        if (preg_match('/^639\d{9}$/', $cleaned)) {
            return '+' . $cleaned;
        }

        // If starts with 09 (11 digits), replace 0 with +63
        if (preg_match('/^09(\d{9})$/', $cleaned, $matches)) {
            return '+639' . $matches[1];
        }

        // If 9XXXXXXXXX (10 digits), prepend +63
        if (preg_match('/^9\d{9}$/', $cleaned)) {
            return '+63' . $cleaned;
        }

        return $cleaned;
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
