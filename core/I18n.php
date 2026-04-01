<?php
declare(strict_types=1);
namespace LEX\Core;

final class I18n
{
    private static array $traducoes = [];
    private static string $idioma = 'pt-BR';
    private static string $moeda = 'BRL';
    private static array $idiomasDisponiveis = ['pt-BR', 'en-US', 'es-ES'];
    private static array $moedasDisponiveis = ['BRL', 'USD'];

    public static function iniciar(): void
    {
        if (isset($_SESSION['idioma']) && in_array($_SESSION['idioma'], self::$idiomasDisponiveis, true)) {
            self::$idioma = $_SESSION['idioma'];
        } elseif (isset($_COOKIE['idioma']) && in_array($_COOKIE['idioma'], self::$idiomasDisponiveis, true)) {
            self::$idioma = $_COOKIE['idioma'];
        }
        if (isset($_SESSION['moeda']) && in_array($_SESSION['moeda'], self::$moedasDisponiveis, true)) {
            self::$moeda = $_SESSION['moeda'];
        } elseif (isset($_COOKIE['moeda']) && in_array($_COOKIE['moeda'], self::$moedasDisponiveis, true)) {
            self::$moeda = $_COOKIE['moeda'];
        }
        self::carregar();
    }

    private static function carregar(): void
    {
        $arquivo = __DIR__ . '/../app/Idiomas/' . self::$idioma . '.php';
        if (file_exists($arquivo)) {
            self::$traducoes = require $arquivo;
        }
    }

    public static function t(string $chave, array $params = []): string
    {
        $texto = self::$traducoes[$chave] ?? $chave;
        foreach ($params as $k => $v) {
            $texto = str_replace(':' . $k, (string)$v, $texto);
        }
        return $texto;
    }

    public static function preco(float $valorBrl): string
    {
        if (self::$moeda === 'USD') {
            $taxa = (float)(Settings::obter('billing.taxa_conversao_usd', '5.0'));
            $valorUsd = $valorBrl / $taxa;
            return '$ ' . number_format($valorUsd, 2, '.', ',');
        }
        return 'R$ ' . number_format($valorBrl, 2, ',', '.');
    }

    public static function formatarMoeda(int|float|string|null $valor, string $moeda = ''): string
    {
        $valor = (float)($valor ?? 0);
        $m = $moeda ?: self::$moeda;
        if ($m === 'USD') {
            return '$ ' . number_format($valor, 2, '.', ',');
        }
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }

    public static function idioma(): string { return self::$idioma; }
    public static function moeda(): string { return self::$moeda; }
    public static function idiomasDisponiveis(): array { return self::$idiomasDisponiveis; }
    public static function moedasDisponiveis(): array { return self::$moedasDisponiveis; }

    public static function definirIdioma(string $idioma): void
    {
        if (in_array($idioma, self::$idiomasDisponiveis, true)) {
            self::$idioma = $idioma;
            $_SESSION['idioma'] = $idioma;
            setcookie('idioma', $idioma, time() + 86400 * 365, '/', '', true, true);
            self::carregar();
        }
    }

    public static function definirMoeda(string $moeda): void
    {
        if (in_array($moeda, self::$moedasDisponiveis, true)) {
            self::$moeda = $moeda;
            $_SESSION['moeda'] = $moeda;
            setcookie('moeda', $moeda, time() + 86400 * 365, '/', '', true, true);
        }
    }
}
