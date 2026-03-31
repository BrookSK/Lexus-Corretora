<?php
declare(strict_types=1);
namespace LEX\Core;

final class RateLimiter
{
    private static array $tentativas = [];

    public static function verificar(string $chave, int $maxTentativas, int $janelaSeg): bool
    {
        $agora = time();
        if (!isset(self::$tentativas[$chave])) {
            self::$tentativas[$chave] = [];
        }
        self::$tentativas[$chave] = array_filter(
            self::$tentativas[$chave],
            fn(int $ts) => ($agora - $ts) < $janelaSeg
        );
        if (count(self::$tentativas[$chave]) >= $maxTentativas) {
            return false;
        }
        self::$tentativas[$chave][] = $agora;
        return true;
    }

    public static function registrar(string $chave): void
    {
        if (!isset(self::$tentativas[$chave])) {
            self::$tentativas[$chave] = [];
        }
        self::$tentativas[$chave][] = time();
    }

    public static function limpar(string $chave): void
    {
        unset(self::$tentativas[$chave]);
    }
}
