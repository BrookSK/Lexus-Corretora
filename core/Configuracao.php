<?php
declare(strict_types=1);
namespace LEX\Core;

final class Configuracao
{
    private static ?array $config = null;

    public static function carregar(): array
    {
        if (self::$config !== null) {
            return self::$config;
        }
        $arquivo = __DIR__ . '/../config/instalacao.php';
        if (!file_exists($arquivo)) {
            throw new \RuntimeException('Arquivo de configuração não encontrado: config/instalacao.php');
        }
        self::$config = require $arquivo;
        return self::$config;
    }

    public static function obter(string $chave, mixed $padrao = null): mixed
    {
        $config = self::carregar();
        $partes = explode('.', $chave);
        $valor = $config;
        foreach ($partes as $parte) {
            if (!is_array($valor) || !array_key_exists($parte, $valor)) {
                return $padrao;
            }
            $valor = $valor[$parte];
        }
        return $valor;
    }
}
