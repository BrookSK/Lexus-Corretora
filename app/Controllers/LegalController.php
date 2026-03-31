<?php
declare(strict_types=1);
namespace LEX\App\Controllers;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Settings};

final class LegalController
{
    public function termos(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../Views/institucional/legal.php', [
            'titulo'   => I18n::t('pagina.termos'),
            'conteudoHtml' => Settings::obter('legal.termos', '<p>Termos de uso em elaboração.</p>'),
        ]);
        $html = View::renderizar(__DIR__ . '/../Views/_layouts/public.php', [
            'conteudo'  => $conteudo,
            'pageTitle' => I18n::t('pagina.termos') . ' — Lexus',
        ]);
        return Resposta::html($html);
    }

    public function privacidade(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../Views/institucional/legal.php', [
            'titulo'   => I18n::t('pagina.privacidade'),
            'conteudoHtml' => Settings::obter('legal.privacidade', '<p>Política de privacidade em elaboração.</p>'),
        ]);
        $html = View::renderizar(__DIR__ . '/../Views/_layouts/public.php', [
            'conteudo'  => $conteudo,
            'pageTitle' => I18n::t('pagina.privacidade') . ' — Lexus',
        ]);
        return Resposta::html($html);
    }
}
