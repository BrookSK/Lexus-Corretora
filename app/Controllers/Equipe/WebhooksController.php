<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Webhooks\WebhookService;
use LEX\App\Services\Audit\AuditService;

final class WebhooksController
{
    public function index(Requisicao $req): Resposta
    {
        $webhooks = WebhookService::listar();
        $eventos  = WebhookService::eventosDisponiveis();
        $logs     = WebhookService::listarLogs(30);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/webhooks.php', [
            'webhooks' => $webhooks, 'eventos' => $eventos, 'logs' => $logs,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => 'Webhooks',
            'breadcrumbs' => [['label' => I18n::t('sidebar.configuracoes'), 'url' => '/equipe/configuracoes'], ['label' => 'Webhooks']],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        $id = (int)($dados['id'] ?? 0);
        if ($id > 0) {
            WebhookService::atualizar($id, $dados);
        } else {
            WebhookService::criar($dados);
        }
        AuditService::registrar('equipe', Auth::equipeId(), 'webhook.salvar', 'webhook_configs', $id ?: null);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Webhook salvo com sucesso.'];
        return Resposta::redirecionar('/equipe/webhooks');
    }

    public function excluir(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        WebhookService::excluir($id);
        AuditService::registrar('equipe', Auth::equipeId(), 'webhook.excluir', 'webhook_configs', $id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Webhook removido.'];
        return Resposta::redirecionar('/equipe/webhooks');
    }
}
