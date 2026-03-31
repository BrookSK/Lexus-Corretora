<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Comissoes\ComissoesService;
use LEX\App\Services\Audit\AuditService;

final class ComissoesController
{
    public function index(Requisicao $req): Resposta
    {
        $page = max(1, (int)$req->get('page', '1'));
        $filtros = array_filter(['status' => $req->get('status'), 'parceiro_id' => $req->get('parceiro_id')]);
        $resultado = ComissoesService::listar($page, 20, $filtros);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/comissoes.php', [
            'items' => $resultado['items'], 'total' => $resultado['total'],
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.comissoes'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.comissoes')]],
        ]));
    }

    public function criar(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/comissoes-criar.php', []);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.comissoes'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.comissoes'), 'url' => '/equipe/comissoes'], ['label' => I18n::t('geral.criar')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        $dados['status'] = $dados['status'] ?? 'prevista';
        $id = ComissoesService::criar($dados);
        AuditService::registrar('equipe', Auth::equipeId(), 'comissao.criar', 'comissoes', $id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/comissoes/' . $id);
    }

    public function detalhe(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $comissao = ComissoesService::obterPorId($id);
        if (!$comissao) return Resposta::redirecionar('/equipe/comissoes');
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/comissoes-detalhe.php', ['comissao' => $comissao]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.comissoes'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.comissoes'), 'url' => '/equipe/comissoes'], ['label' => I18n::t('geral.detalhes')]],
        ]));
    }

    public function alterarStatus(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $status = $req->post('status', '');
        ComissoesService::alterarStatus($id, $status);
        AuditService::registrar('equipe', Auth::equipeId(), 'comissao.status', 'comissoes', $id, ['status' => $status]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/comissoes/' . $id);
    }
}
