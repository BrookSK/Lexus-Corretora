<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Tarefas\TarefasService;

final class TarefasController
{
    public function index(Requisicao $req): Resposta
    {
        $page = max(1, (int)$req->get('page', '1'));
        $filtros = array_filter(['status' => $req->get('status'), 'priority' => $req->get('priority'), 'assigned_to' => $req->get('assigned_to')]);
        $resultado = TarefasService::listar($page, 20, $filtros);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/tarefas.php', ['items' => $resultado['items'], 'total' => $resultado['total']]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => I18n::t('sidebar.tarefas'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.tarefas')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        $dados['created_by'] = Auth::equipeId();
        $dados['status'] = $dados['status'] ?? 'pendente';
        TarefasService::criar($dados);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/tarefas');
    }

    public function alterarStatus(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $status = $req->post('status', '');
        TarefasService::alterarStatus($id, $status);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/tarefas');
    }
}
