<?php
class Phone
{
    public static function sanitize($phone)
    {
        return preg_replace('/[^\d+]/', '', trim($phone));
    }

    public static function toE164($phone)
    {
        $phone = self::sanitize($phone);
        if ($phone === '') {
            return '';
        }

        if ($phone[0] === '+') {
            return $phone;
        }

        // Philippine local mobile numbers: 09XXXXXXXXX or 9XXXXXXXXX
        if (preg_match('/^0(9\d{9})$/', $phone, $matches)) {
            return '+63' . $matches[1];
        }

        if (preg_match('/^9\d{9}$/', $phone)) {
            return '+63' . $phone;
        }

        return $phone;
    }

    public static function isValidE164($phone)
    {
        return preg_match('/^\+\d{8,15}$/', $phone) === 1;
    }
}
