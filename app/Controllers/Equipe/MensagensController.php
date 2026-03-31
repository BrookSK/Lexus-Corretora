<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Mensagens\MensagensService;

final class MensagensController
{
    public function index(Requisicao $req): Resposta
    {
        $conversas = MensagensService::listarConversas('equipe', Auth::equipeId());
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/mensagens.php', ['conversas' => $conversas]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => I18n::t('sidebar.mensagens'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.mensagens')]],
        ]));
    }

    public function conversa(Requisicao $req): Resposta
    {
        $conversaId = (int)$req->param('conversaId');
        $mensagens = MensagensService::listarMensagens($conversaId);
        MensagensService::marcarLida($conversaId, 'equipe', Auth::equipeId());
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/mensagens-conversa.php', [
            'conversaId' => $conversaId, 'mensagens' => $mensagens,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => I18n::t('sidebar.mensagens'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.mensagens'), 'url' => '/equipe/mensagens'], ['label' => 'Conversa']],
        ]));
    }

    public function enviar(Requisicao $req): Resposta
    {
        $conversaId = (int)$req->post('conversa_id', '0');
        $body = trim($req->post('body', ''));
        if ($conversaId > 0 && $body !== '') {
            MensagensService::enviarMensagem($conversaId, 'equipe', Auth::equipeId(), $body);
        }
        return Resposta::redirecionar('/equipe/mensagens/' . $conversaId);
    }
}
