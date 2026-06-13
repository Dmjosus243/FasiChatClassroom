<?php
namespace Helpers;

class ViewHelper
{
    public static function asset(string $path): string
    {
        return '/FasiChatClassroom/public/assets/' . ltrim($path, '/');
    }

    public static function url(string $path = ''): string
    {
        return '/FasiChatClassroom/public/' . ltrim($path, '/');
    }

    public static function csrfField(): string
    {
        $token = SecurityHelper::generateCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    public static function escape(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    public static function truncate(string $string, int $length = 100): string
    {
        if (strlen($string) <= $length) {
            return $string;
        }
        return substr($string, 0, $length) . '...';
    }

    public static function formatDate(string $date, string $format = 'd/m/Y H:i'): string
    {
        return date($format, strtotime($date));
    }
}