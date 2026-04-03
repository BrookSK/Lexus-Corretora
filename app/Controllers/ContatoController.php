<?php
declare(strict_types=1);
namespace LEX\App\Controllers;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Settings};
use LEX\App\Services\Integracoes\TrelloService;

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
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);

        // Integração Trello
        try { TrelloService::cardContato($dados); } catch (\Throwable $e) { /* silenciar */ }

        // Notificar equipe por e-mail
        try {
            $emailEquipe = \LEX\Core\Settings::obter('smtp.de_email', '');
            if ($emailEquipe) {
                \LEX\App\Services\Email\EmailService::novoContatoEquipe(
                    $emailEquipe,
                    $dados['name'] ?? 'Desconhecido',
                    $dados['email'] ?? '',
                    $dados['message'] ?? ($dados['notes'] ?? '')
                );
            }
        } catch (\Throwable $e) { /* silenciar */ }

        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('contato.sucesso')];
        return Resposta::redirecionar('/contato');
    }
}
