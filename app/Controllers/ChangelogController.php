<?php
declare(strict_types=1);
namespace LEX\App\Controllers;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n};

final class ChangelogController
{
    public function index(Requisicao $req): Resposta
    {
        $arquivo = __DIR__ . '/../../CHANGELOG.md';
        $conteudoMd = file_exists($arquivo) ? file_get_contents($arquivo) : 'Changelog não disponível.';
        $conteudo = View::renderizar(__DIR__ . '/../Views/institucional/changelog.php', [
            'changelogRaw' => $conteudoMd,
        ]);
        $html = View::renderizar(__DIR__ . '/../Views/_layouts/public.php', [
            'conteudo'  => $conteudo,
            'pageTitle' => I18n::t('pagina.changelog') . ' — Lexus',
        ]);
        return Resposta::html($html);
    }
}
