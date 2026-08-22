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
     * Send waste report status update email (Verified, In Progress, Resolved, Rejected).
     */
    public static function sendReportStatusEmail($toEmail, $trackingNumber, $statusKey, $guestName = '', $remark = '', $details = [])
    {
        $displayName = !empty($guestName) ? $guestName : 'Citizen';

        $statusKey = strtolower(trim($statusKey));
        $statusConfigs = [
            'verified' => [
                'badgeBg' => '#ECFDF5',
                'badgeColor' => '#065F46',
                'badgeBorder' => '#A7F3D0',
                'title' => 'Report Verified & Accepted',
                'headline' => 'Your waste report has been verified by the Barangay Office.',
                'message' => 'Good news! Barangay Dulong Bayan officials have inspected your report and confirmed its details. It is now queued for collection dispatch.',
                'subject' => "Waste Report Verified [{$trackingNumber}] - Barangay Dulong Bayan",
            ],
            'in_progress' => [
                'badgeBg' => '#FFFBEB',
                'badgeColor' => '#92400E',
                'badgeBorder' => '#FDE68A',
                'title' => 'Collection In Progress',
                'headline' => 'A collection team has been dispatched.',
                'message' => 'A waste collection team has been assigned and is currently addressing the reported incident at the specified location.',
                'subject' => "Collection In Progress [{$trackingNumber}] - Barangay Dulong Bayan",
            ],
            'resolved' => [
                'badgeBg' => '#F0FDF4',
                'badgeColor' => '#15803D',
                'badgeBorder' => '#BBF7D0',
                'title' => 'Issue Resolved & Cleaned',
                'headline' => 'The waste site has been cleared and verified.',
                'message' => 'The reported waste has been successfully collected and cleared by our team. Thank you for actively contributing to keeping our community clean and healthy!',
                'subject' => "Report Resolved [{$trackingNumber}] - Barangay Dulong Bayan",
            ],
            'rejected' => [
                'badgeBg' => '#FEF2F2',
                'badgeColor' => '#991B1B',
                'badgeBorder' => '#FECDD3',
                'title' => 'Report Declined / Rejected',
                'headline' => 'Your report could not be processed.',
                'message' => 'Your waste report was reviewed but could not be actioned at this time (e.g. duplicate submission, outside barangay jurisdiction, or already cleared).',
                'subject' => "Update on Waste Report [{$trackingNumber}] - Barangay Dulong Bayan",
            ],
        ];

        $cfg = $statusConfigs[$statusKey] ?? [
            'badgeBg' => '#F1F5F9',
            'badgeColor' => '#334155',
            'badgeBorder' => '#CBD5E1',
            'title' => 'Status Update: ' . ucfirst(str_replace('_', ' ', $statusKey)),
            'headline' => 'An update has been posted to your waste report.',
            'message' => 'The status of your waste report has been updated by barangay officials.',
            'subject' => "Waste Report Update [{$trackingNumber}] - Barangay Dulong Bayan",
        ];

        $categoryName = !empty($details['category_name']) ? htmlspecialchars($details['category_name'], ENT_QUOTES, 'UTF-8') : 'Waste Incident';
        $locationStr  = !empty($details['location']) ? htmlspecialchars($details['location'], ENT_QUOTES, 'UTF-8') : (!empty($details['purok_name']) ? htmlspecialchars($details['purok_name'], ENT_QUOTES, 'UTF-8') : 'Barangay Dulong Bayan');

        $remarkHtml = '';
        if (!empty($remark)) {
            $remarkHtml = "
                <div style='margin-top: 16px; padding: 12px 16px; background-color: #F8FAFC; border-left: 3px solid #10B981; border-radius: 6px;'>
                    <p style='margin: 0 0 4px 0; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B;'>Officer Remark / Reason</p>
                    <p style='margin: 0; font-size: 13px; color: #334155; line-height: 1.5;'>" . nl2br(htmlspecialchars($remark, ENT_QUOTES, 'UTF-8')) . "</p>
                </div>
            ";
        }

        $htmlBody = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$cfg['subject']}</title>
        </head>
        <body style='margin: 0; padding: 24px 12px; background-color: #F1F5F9; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; color: #1E293B;'>
            <div style='max-width: 560px; margin: 0 auto; background-color: #FFFFFF; border-radius: 16px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 4px 12px rgba(0,0,0,0.05);'>
                
                <!-- Header Banner -->
                <div style='background-color: #0B2E22; padding: 24px 28px; text-align: left;'>
                    <div style='display: flex; align-items: center;'>
                        <span style='font-size: 18px; font-weight: 800; color: #FFFFFF; letter-spacing: -0.3px;'>LINARAYA</span>
                        <span style='display: inline-block; margin-left: 8px; padding: 2px 8px; background-color: rgba(255,255,255,0.15); border-radius: 12px; font-size: 11px; font-weight: 600; color: #6EE7B7;'>Barangay Dulong Bayan</span>
                    </div>
                    <h1 style='margin: 12px 0 0 0; font-size: 20px; font-weight: 800; color: #FFFFFF; line-height: 1.3;'>{$cfg['title']}</h1>
                </div>

                <!-- Main Content -->
                <div style='padding: 28px;'>
                    <p style='margin: 0 0 12px 0; font-size: 15px; color: #334155;'>Hello <strong>" . htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') . "</strong>,</p>
                    <p style='margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #475569;'>{$cfg['message']}</p>

                    <!-- Status Highlight Box -->
                    <div style='padding: 16px 20px; background-color: {$cfg['badgeBg']}; border: 1px solid {$cfg['badgeBorder']}; border-radius: 12px; margin-bottom: 20px;'>
                        <div style='font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: {$cfg['badgeColor']}; margin-bottom: 4px;'>Current Status</div>
                        <div style='font-size: 16px; font-weight: 800; color: {$cfg['badgeColor']};'>{$cfg['title']}</div>
                        <div style='font-size: 12px; color: {$cfg['badgeColor']}; opacity: 0.9; margin-top: 2px;'>{$cfg['headline']}</div>
                    </div>

                    <!-- Report Details Table -->
                    <div style='border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; margin-bottom: 20px;'>
                        <table style='width: 100%; border-collapse: collapse; font-size: 13px;'>
                            <tr style='background-color: #F8FAFC; border-bottom: 1px solid #E2E8F0;'>
                                <td style='padding: 10px 16px; font-weight: 600; color: #64748B; width: 40%;'>Tracking Number</td>
                                <td style='padding: 10px 16px; font-weight: 700; color: #0F172A; font-family: monospace;'>{$trackingNumber}</td>
                            </tr>
                            <tr style='border-bottom: 1px solid #E2E8F0;'>
                                <td style='padding: 10px 16px; font-weight: 600; color: #64748B;'>Category</td>
                                <td style='padding: 10px 16px; font-weight: 600; color: #0F172A;'>{$categoryName}</td>
                            </tr>
                            <tr style='border-bottom: 1px solid #E2E8F0;'>
                                <td style='padding: 10px 16px; font-weight: 600; color: #64748B;'>Location / Zone</td>
                                <td style='padding: 10px 16px; color: #334155;'>{$locationStr}</td>
                            </tr>
                            <tr>
                                <td style='padding: 10px 16px; font-weight: 600; color: #64748B;'>Updated At</td>
                                <td style='padding: 10px 16px; color: #334155;'>" . date('F j, Y · g:i A') . "</td>
                            </tr>
                        </table>
                    </div>

                    {$remarkHtml}
                </div>

                <!-- Footer -->
                <div style='background-color: #F8FAFC; padding: 20px 28px; border-top: 1px solid #E2E8F0; text-align: center;'>
                    <p style='margin: 0 0 4px 0; font-size: 12px; font-weight: 700; color: #475569;'>Barangay Dulong Bayan Waste Management System</p>
                    <p style='margin: 0; font-size: 11px; color: #94A3B8;'>This is an automated notification sent for Waste Incident Reference #{$trackingNumber}. Please do not reply directly to this email.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        $altBody = "Hello {$displayName},\n\nUpdate on Waste Report #{$trackingNumber}: {$cfg['title']}.\n{$cfg['headline']}\n\nLocation: {$locationStr}\nTracking Code: {$trackingNumber}\n\n- Barangay Dulong Bayan";

        return self::sendMail($toEmail, $cfg['subject'], $htmlBody, $altBody, $displayName);
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
