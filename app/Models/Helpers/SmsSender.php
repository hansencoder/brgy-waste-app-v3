<?php
class SmsSender
{
    public static function sendSms($phoneE164, $message)
    {
        $envFile = dirname(__DIR__, 2) . '/.env';
        $env = file_exists($envFile) ? parse_ini_file($envFile) : [];

        $clicksendUser = $env['CLICKSEND_USERNAME'] ?? getenv('CLICKSEND_USERNAME');
        $clicksendKey = $env['CLICKSEND_API_KEY'] ?? getenv('CLICKSEND_API_KEY');
        $clicksendSender = $env['CLICKSEND_SENDER_ID'] ?? getenv('CLICKSEND_SENDER_ID');

        if ($clicksendUser && $clicksendKey) {
            $url = 'https://rest.clicksend.com/v3/sms/send';
            $payload = [
                'messages' => [
                    [
                        'source' => 'php',
                        'from' => $clicksendSender ?: 'PHPApp',
                        'body' => $message,
                        'to' => $phoneE164,
                        'custom_string' => 'OTP',
                        'unicode' => false
                    ]
                ]
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_USERPWD, $clicksendUser . ':' . $clicksendKey);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);

            $response = curl_exec($ch);
            $err = curl_error($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($err) {
                throw new Exception('ClickSend cURL error: ' . $err);
            }
            if ($status < 200 || $status >= 300) {
                throw new Exception('ClickSend returned HTTP ' . $status . ' response: ' . $response);
            }

            return $response;
        }

        throw new Exception('ClickSend credentials are not configured.');
    }
}
