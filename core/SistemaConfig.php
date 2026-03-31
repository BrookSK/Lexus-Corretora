<?php
declare(strict_types=1);
namespace LEX\Core;

final class SistemaConfig
{
    public static function nome(): string
    {
        return Settings::obter('sistema.nome', 'Lexus Corretora');
    }

    public static function logo(): string
    {
        return Settings::obter('sistema.logo', '/assets/img/logo.svg');
    }

    public static function favicon(): string
    {
        return Settings::obter('sistema.favicon', '/assets/img/favicon.ico');
    }

    public static function copyright(): string
    {
        $ano = date('Y');
        return Settings::obter('sistema.copyright', "© {$ano} Lexus — Estruturação Estratégica de Obras");
    }

    public static function versao(): string
    {
        static $versao = null;
        if ($versao !== null) return $versao;
        $changelog = __DIR__ . '/../CHANGELOG.md';
        if (file_exists($changelog)) {
            $conteudo = file_get_contents($changelog);
            if (preg_match('/##\s*\[?(\d+\.\d+\.\d+)/', $conteudo, $m)) {
                $versao = $m[1];
                return $versao;
            }
        }
        $versao = '1.0.0';
        return $versao;
    }

    public static function url(): string
    {
        $url = Settings::obter('sistema.url', '');
        if ($url) return rtrim($url, '/');
        $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $proto . '://' . $host;
    }

    public static function ambiente(): string
    {
        return Configuracao::obter('app.ambiente', 'producao');
    }

    public static function debug(): bool
    {
        return (bool)Configuracao::obter('app.debug', false);
    }

    public static function metaTitle(): string
    {
        return Settings::obter('seo.meta_title', 'Lexus Corretora — Estruturação Estratégica de Obras');
    }

    public static function metaDescription(): string
    {
        return Settings::obter('seo.meta_description', 'Conectamos clientes a uma rede qualificada de parceiros para obras e reformas.');
    }

    public static function ogImage(): string
    {
        return Settings::obter('seo.og_image', '/assets/img/og-image.jpg');
    }

    public static function contato(): array
    {
        return [
            'email'    => Settings::obter('contato.email', 'contato@lexuscorretora.com.br'),
            'telefone' => Settings::obter('contato.telefone', ''),
            'whatsapp' => Settings::obter('contato.whatsapp', ''),
        ];
    }

    public static function redesSociais(): array
    {
        return [
            'instagram' => Settings::obter('social.instagram', ''),
            'linkedin'  => Settings::obter('social.linkedin', ''),
            'facebook'  => Settings::obter('social.facebook', ''),
        ];
    }
}
