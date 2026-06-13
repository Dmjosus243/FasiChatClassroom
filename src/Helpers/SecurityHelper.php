<?php
namespace Helpers;

class SecurityHelper
{
    public static function generateCsrfToken(): string
    {
        if (!SessionHelper::has('csrf_token')) {
            $token = bin2hex(random_bytes(32));
            SessionHelper::set('csrf_token', $token);
        }
        return SessionHelper::get('csrf_token');
    }

    public static function verifyCsrfToken(string $token): bool
    {
        return hash_equals(SessionHelper::get('csrf_token', ''), $token);
    }

    public static function sanitizeInput(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}