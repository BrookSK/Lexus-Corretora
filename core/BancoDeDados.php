<?php
declare(strict_types=1);
namespace LEX\Core;

use PDO;
use PDOException;

final class BancoDeDados
{
    private static ?PDO $instancia = null;

    public static function obter(): PDO
    {
        if (self::$instancia !== null) {
            return self::$instancia;
        }
        $cfg = Configuracao::obter('db');
        if (!$cfg) {
            throw new \RuntimeException('Configuração de banco de dados não encontrada.');
        }
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $cfg['host'],
            $cfg['porta'] ?? 3306,
            $cfg['nome'],
            $cfg['charset'] ?? 'utf8mb4'
        );
        try {
            self::$instancia = new PDO($dsn, $cfg['usuario'], $cfg['senha'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            ]);
        } catch (PDOException $e) {
            throw new \RuntimeException('Erro ao conectar ao banco de dados: ' . $e->getMessage());
        }
        return self::$instancia;
    }

    public static function resetar(): void
    {
        self::$instancia = null;
    }
}
