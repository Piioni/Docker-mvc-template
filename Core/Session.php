<?php

namespace Core;

class Session
{
    public static function has_key($key): bool
    {
        return (bool)static::get_value($key);
    }

    public static function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get_value($key, $default = null)
    {
        return $_SESSION['_flash'][$key] ?? $_SESSION[$key] ?? $default;
    }

    public static function set_flash($key, $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function clear_flash(): void
    {
        unset($_SESSION['_flash']);
    }

    public static function clear_all(): void
    {
        $_SESSION = [];
    }

    public static function destroy_session(): void
    {
        static::clear_all();

        if (session_status() !== PHP_SESSION_NONE) {
            session_destroy();
        }

        if (!headers_sent()) {
            $params = session_get_cookie_params();
            setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
    }
}