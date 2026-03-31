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
            // Retornar config mínima para o sistema funcionar sem instalacao.php
            self::$config = [
                'db' => ['host' => 'localhost', 'porta' => 3306, 'nome' => 'lexus_corretora', 'usuario' => 'root', 'senha' => '', 'charset' => 'utf8mb4'],
                'app' => ['url' => '', 'ambiente' => 'desenvolvimento', 'debug' => true, 'timezone' => 'America/Sao_Paulo', 'chave_app' => 'CONFIGURAR'],
                'smtp' => ['host' => '', 'porta' => 587, 'usuario' => '', 'senha' => '', 'de_email' => '', 'de_nome' => 'Lexus Corretora'],
            ];
            return self::$config;
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
