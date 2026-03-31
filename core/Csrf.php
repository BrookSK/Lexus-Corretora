<?php
declare(strict_types=1);
namespace LEX\Core;

final class Csrf
{
    private const TOKEN_LENGTH = 64;

    public static function gerar(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(self::TOKEN_LENGTH / 2));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function campo(): string
    {
        $token = self::gerar();
        return '<input type="hidden" name="_csrf_token" value="' . View::e($token) . '">';
    }

    public static function validar(?string $token): bool
    {
        if (empty($token) || empty($_SESSION['_csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['_csrf_token'], $token);
    }

    public static function validarRequisicao(): bool
    {
        $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        return self::validar($token);
    }

    public static function regenerar(): void
    {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(self::TOKEN_LENGTH / 2));
    }
}
