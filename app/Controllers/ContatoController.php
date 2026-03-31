<?php
declare(strict_types=1);
namespace LEX\App\Controllers;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n};

final class ContatoController
{
    public function index(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../Views/institucional/contato.php');
        $html = View::renderizar(__DIR__ . '/../Views/_layouts/public.php', [
            'conteudo'  => $conteudo,
            'pageTitle' => I18n::t('pagina.contato') . ' — Lexus',
        ]);
        return Resposta::html($html);
    }

    public function enviar(Requisicao $req): Resposta
    {
        // TODO: Validar, salvar e enviar email de contato
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('contato.sucesso')];
        return Resposta::redirecionar('/contato');
    }
}
