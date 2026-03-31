<?php
declare(strict_types=1);
namespace LEX\Core;

final class Settings
{
    private static ?array $cache = null;

    public static function carregar(): void
    {
        if (self::$cache !== null) return;
        self::$cache = [];
        try {
            $pdo = BancoDeDados::obter();
            $stmt = $pdo->query("SELECT `key`, `value` FROM settings");
            while ($row = $stmt->fetch()) {
                self::$cache[$row['key']] = $row['value'];
            }
        } catch (\Exception $e) {
            self::$cache = [];
        }
    }

    public static function obter(string $chave, mixed $padrao = null): mixed
    {
        self::carregar();
        if (!array_key_exists($chave, self::$cache)) {
            return $padrao;
        }
        return self::decodificar(self::$cache[$chave]);
    }

    public static function definir(string $chave, mixed $valor): void
    {
        $valorStr = self::codificar($valor);
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("INSERT INTO settings (`key`, `value`) VALUES (:k, :v) ON DUPLICATE KEY UPDATE `value` = :v2");
        $stmt->execute(['k' => $chave, 'v' => $valorStr, 'v2' => $valorStr]);
        self::$cache[$chave] = $valorStr;
    }

    public static function remover(string $chave): void
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("DELETE FROM settings WHERE `key` = :k");
        $stmt->execute(['k' => $chave]);
        unset(self::$cache[$chave]);
    }

    private static function codificar(mixed $valor): string
    {
        if (is_bool($valor)) return $valor ? '1' : '0';
        if (is_array($valor) || is_object($valor)) return json_encode($valor, JSON_UNESCAPED_UNICODE);
        return (string)$valor;
    }

    private static function decodificar(string $valor): mixed
    {
        if ($valor === '') return '';
        $json = json_decode($valor, true);
        if (json_last_error() === JSON_ERROR_NONE && (is_array($json) || is_object($json))) {
            return $json;
        }
        if (is_numeric($valor)) {
            return str_contains($valor, '.') ? (float)$valor : (int)$valor;
        }
        return $valor;
    }

    public static function resetar(): void
    {
        self::$cache = null;
    }
}
