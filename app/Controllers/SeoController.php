<?php
declare(strict_types=1);
namespace LEX\App\Controllers;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{SistemaConfig, Settings};

final class SeoController
{
    public function robots(Requisicao $req): Resposta
    {
        $url = SistemaConfig::url();
        $txt = "User-agent: *\nAllow: /\nDisallow: /equipe/\nDisallow: /cliente/\nDisallow: /parceiro/\n\nSitemap: {$url}/sitemap.xml\n";
        return Resposta::texto($txt);
    }

    public function sitemap(Requisicao $req): Resposta
    {
        $url = SistemaConfig::url();
        $pages = ['/', '/sobre', '/como-funciona', '/para-clientes', '/para-parceiros', '/vetriks', '/contato', '/abrir-demanda', '/seja-parceiro', '/termos', '/privacidade', '/status', '/changelog'];
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($pages as $page) {
            $xml .= "  <url><loc>{$url}{$page}</loc><changefreq>weekly</changefreq></url>\n";
        }
        $xml .= '</urlset>';
        return Resposta::xml($xml);
    }
}
