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

    public static function formatDuration(int $seconds): string
    {
        return gmdate("i:s", $seconds);
    }
}