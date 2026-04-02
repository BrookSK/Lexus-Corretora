<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};
use LEX\App\Services\CRM\CRMService;
use LEX\App\Services\Audit\AuditService;

final class CrmController
{
    public function index(Requisicao $req): Resposta
    {
        $pdo   = BancoDeDados::obter();
        $busca = trim($req->get('busca', ''));

        $sql = "SELECT d.id, d.code, d.title, d.status, d.urgency, d.priority,
                       d.city, d.state, d.budget_min, d.budget_max, d.currency_code,
                       d.created_at, c.name AS cliente_nome
                FROM demandas d
                LEFT JOIN clientes c ON c.id = d.cliente_id
                WHERE d.deleted_at IS NULL";
        $params = [];
        if ($busca !== '') {
            $sql .= " AND (d.title LIKE :b OR d.code LIKE :b2 OR c.name LIKE :b3)";
            $params = ['b' => "%$busca%", 'b2' => "%$busca%", 'b3' => "%$busca%"];
        }
        $sql .= " ORDER BY d.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $demandas = $stmt->fetchAll();

        $kanban = [];
        foreach ($demandas as $d) {
            $kanban[$d['status']][] = $d;
        }

        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/crm.php', [
            'kanban' => $kanban,
            'busca'  => $busca,
            'total'  => count($demandas),
        ]);
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
