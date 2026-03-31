<?php
declare(strict_types=1);
namespace LEX\Core;

final class AppLogger
{
    public static function info(string $mensagem, array $contexto = []): void
    {
        self::log('INFO', $mensagem, $contexto);
    }

    public static function erro(string $mensagem, array $contexto = []): void
    {
        self::log('ERROR', $mensagem, $contexto);
    }

    public static function aviso(string $mensagem, array $contexto = []): void
    {
        self::log('WARNING', $mensagem, $contexto);
    }

    public static function debug(string $mensagem, array $contexto = []): void
    {
        self::log('DEBUG', $mensagem, $contexto);
    }

    private static function log(string $nivel, string $mensagem, array $contexto): void
    {
        $dir = __DIR__ . '/../storage/logs';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $arquivo = $dir . '/app-' . date('Y-m-d') . '.log';
        $linha = sprintf(
            "[%s] [%s] %s %s\n",
            date('Y-m-d H:i:s'),
            $nivel,
            $mensagem,
            $contexto ? json_encode($contexto, JSON_UNESCAPED_UNICODE) : ''
        );
        file_put_contents($arquivo, $linha, FILE_APPEND | LOCK_EX);
    }
}
