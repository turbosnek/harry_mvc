<?php

class CsrfHelper
{
    public static function generateToken(): string
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;

        return $token;
    }

    public static function validateToken(string $token): bool
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            unset($_SESSION['csrf_token']); // Token se použije jen jednou
            return true;
        }

        return false;
    }
}