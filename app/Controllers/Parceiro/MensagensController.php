<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Parceiro;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Mensagens\MensagensService;

final class MensagensController
{
    public function index(Requisicao $req): Resposta
    {
        $conversas = MensagensService::listarConversas('parceiro', Auth::parceiroId());
        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/mensagens.php', ['conversas' => $conversas]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'parceiro',
            'pageTitle' => I18n::t('sidebar_par.mensagens'),
            'breadcrumbs' => [['label' => I18n::t('sidebar_par.mensagens')]],
        ]));
    }

    public function enviar(Requisicao $req): Resposta
    {
        $conversaId = (int)$req->post('conversa_id', '0');
        $body = trim($req->post('body', ''));
        if ($conversaId > 0 && $body !== '') {
            MensagensService::enviarMensagem($conversaId, 'parceiro', Auth::parceiroId(), $body);
        }
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/parceiro/mensagens');
    }
}
