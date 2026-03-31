<?php
declare(strict_types=1);
namespace LEX\Core;

final class View
{
    public static function renderizar(string $arquivo, array $dados = []): string
    {
        if (!file_exists($arquivo)) {
            throw new \RuntimeException("View não encontrada: $arquivo");
        }
        extract($dados, EXTR_SKIP);
        ob_start();
        require $arquivo;
        return ob_get_clean() ?: '';
    }

    public static function e(?string $valor): string
    {
        if ($valor === null) return '';
        return htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function raw(string $valor): string
    {
        return $valor;
    }
}
