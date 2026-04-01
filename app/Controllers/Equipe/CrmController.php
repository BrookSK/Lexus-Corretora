<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\CRM\CRMService;
use LEX\App\Services\Audit\AuditService;

final class CrmController
{
    public function index(Requisicao $req): Resposta
    {
        $page = max(1, (int)$req->get('page', '1'));
        $filtros = array_filter(['busca' => $req->get('busca'), 'status' => $req->get('status'), 'origin' => $req->get('origin')]);
        $resultado = CRMService::listarLeads($page, 20, $filtros);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/crm.php', ['items' => $resultado['items'], 'total' => $resultado['total']]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => I18n::t('sidebar.crm'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.crm')]],
        ]));
    }

    public function criar(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/crm-criar.php', []);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => I18n::t('sidebar.crm'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.crm'), 'url' => '/equipe/crm'], ['label' => I18n::t('geral.criar')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        $id = CRMService::criarLead($dados);
        AuditService::registrar('equipe', Auth::equipeId(), 'lead.criar', 'leads', $id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/crm/' . $id);
    }

    public function detalhe(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $lead = CRMService::obterLead($id);
        if (!$lead) return Resposta::redirecionar('/equipe/crm');
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/crm-detalhe.php', ['lead' => $lead]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => $lead['name'],
            'breadcrumbs' => [['label' => I18n::t('sidebar.crm'), 'url' => '/equipe/crm'], ['label' => $lead['name']]],
        ]));
    }

    public function converter(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $clienteId = CRMService::converterParaCliente($id);
        AuditService::registrar('equipe', Auth::equipeId(), 'lead.converter', 'leads', $id, ['cliente_id' => $clienteId]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Lead convertido em cliente com sucesso.'];
        return Resposta::redirecionar('/equipe/clientes/' . $clienteId);
    }

    public function alterarStatus(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $status = $req->post('status', '');
        CRMService::atualizarLead($id, ['status' => $status]);
        AuditService::registrar('equipe', Auth::equipeId(), 'lead.status', 'leads', $id, ['status' => $status]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/crm/' . $id);
    }
}
