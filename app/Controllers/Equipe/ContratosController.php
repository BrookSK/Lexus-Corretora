<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Contratos\ContratosService;
use LEX\App\Services\Audit\AuditService;

final class ContratosController
{
    public function index(Requisicao $req): Resposta
    {
        $page = max(1, (int)$req->get('page', '1'));
        $filtros = array_filter(['status' => $req->get('status')]);
        $resultado = ContratosService::listar($page, 20, $filtros);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/contratos.php', [
            'items' => $resultado['items'], 'total' => $resultado['total'],
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.contratos'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.contratos')]],
        ]));
    }

    public function criar(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/contratos-criar.php', []);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.contratos'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.contratos'), 'url' => '/equipe/contratos'], ['label' => I18n::t('geral.criar')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        $id = ContratosService::criar($dados);
        AuditService::registrar('equipe', Auth::equipeId(), 'contrato.criar', 'contratos', $id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/contratos/' . $id);
    }

    public function detalhe(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $contrato = ContratosService::obterPorId($id);
        if (!$contrato) return Resposta::redirecionar('/equipe/contratos');
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/contratos-detalhe.php', ['contrato' => $contrato]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.contratos'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.contratos'), 'url' => '/equipe/contratos'], ['label' => I18n::t('geral.detalhes')]],
        ]));
    }

    public function alterarStatus(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $status = $req->post('status', '');
        ContratosService::alterarStatus($id, $status);
        AuditService::registrar('equipe', Auth::equipeId(), 'contrato.status', 'contratos', $id, ['status' => $status]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/contratos/' . $id);
    }
}
