<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};
use LEX\App\Services\Tarefas\TarefasService;
use LEX\App\Services\Audit\AuditService;

final class TarefasController
{
    private function usuarios(): array
    {
        $pdo = BancoDeDados::obter();
        return $pdo->query("SELECT id, name FROM users WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name")->fetchAll();
    }

    public function index(Requisicao $req): Resposta
    {
        $page    = max(1, (int)$req->get('page', '1'));
        $filtros = array_filter([
            'status'      => $req->get('status', ''),
            'priority'    => $req->get('priority', ''),
            'assigned_to' => $req->get('assigned_to', ''),
        ]);
        $resultado = TarefasService::listar($page, 20, $filtros);
        $conteudo  = View::renderizar(__DIR__ . '/../../Views/equipe/tarefas.php', [
            'items'    => $resultado['items'],
            'total'    => $resultado['total'],
            'page'     => $page,
            'filtros'  => $filtros,
            'usuarios' => $this->usuarios(),
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.tarefas'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.tarefas')]],
        ]));
    }

    public function criar(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/tarefas-criar.php', [
            'usuarios' => $this->usuarios(),
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => 'Nova Tarefa',
            'breadcrumbs' => [
                ['label' => I18n::t('sidebar.tarefas'), 'url' => '/equipe/tarefas'],
                ['label' => 'Nova Tarefa'],
            ],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);

        if (empty(trim($dados['title'] ?? ''))) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'O título é obrigatório.'];
            return Resposta::redirecionar('/equipe/tarefas/criar');
        }

        $dados['created_by']   = Auth::equipeId();
        $dados['status']       = $dados['status']       ?? 'pendente';
        $dados['priority']     = $dados['priority']     ?? 'normal';
        $dados['assigned_to']  = !empty($dados['assigned_to'])  ? (int)$dados['assigned_to']  : null;
        $dados['due_date']     = !empty($dados['due_date'])     ? $dados['due_date']           : null;
        $dados['related_type'] = !empty($dados['related_type']) ? $dados['related_type']       : null;
        $dados['related_id']   = !empty($dados['related_id'])   ? (int)$dados['related_id']   : null;

        $id = TarefasService::criar($dados);
        try { AuditService::registrar('equipe', Auth::equipeId(), 'tarefa.criar', 'tarefas', $id); } catch (\Throwable $e) {}
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Tarefa criada com sucesso.'];
        return Resposta::redirecionar('/equipe/tarefas');
    }

    public function editar(Requisicao $req): Resposta
    {
        $id     = (int)$req->param('id');
        $tarefa = TarefasService::obter($id);
        if (!$tarefa) return Resposta::redirecionar('/equipe/tarefas');

        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/tarefas-editar.php', [
            'tarefa'   => $tarefa,
            'usuarios' => $this->usuarios(),
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => 'Editar Tarefa',
            'breadcrumbs' => [
                ['label' => I18n::t('sidebar.tarefas'), 'url' => '/equipe/tarefas'],
                ['label' => htmlspecialchars($tarefa['title'])],
            ],
        ]));
    }

    public function atualizar(Requisicao $req): Resposta
    {
        $id     = (int)$req->param('id');
        $tarefa = TarefasService::obter($id);
        if (!$tarefa) return Resposta::redirecionar('/equipe/tarefas');

        $dados = $req->todosPost();
        unset($dados['_csrf_token']);

        if (empty(trim($dados['title'] ?? ''))) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'O título é obrigatório.'];
            return Resposta::redirecionar("/equipe/tarefas/{$id}/editar");
        }

        $dados['assigned_to']  = !empty($dados['assigned_to'])  ? (int)$dados['assigned_to']  : null;
        $dados['due_date']     = !empty($dados['due_date'])     ? $dados['due_date']           : null;
        $dados['related_type'] = !empty($dados['related_type']) ? $dados['related_type']       : null;
        $dados['related_id']   = !empty($dados['related_id'])   ? (int)$dados['related_id']   : null;

        TarefasService::atualizar($id, $dados);
        try { AuditService::registrar('equipe', Auth::equipeId(), 'tarefa.atualizar', 'tarefas', $id); } catch (\Throwable $e) {}
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Tarefa atualizada com sucesso.'];
        return Resposta::redirecionar('/equipe/tarefas');
    }

    public function excluir(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        TarefasService::excluir($id);
        try { AuditService::registrar('equipe', Auth::equipeId(), 'tarefa.excluir', 'tarefas', $id); } catch (\Throwable $e) {}
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Tarefa excluída.'];
        return Resposta::redirecionar('/equipe/tarefas');
    }

    public function alterarStatus(Requisicao $req): Resposta
    {
        $id     = (int)$req->param('id');
        $status = $req->post('status', '');
        TarefasService::alterarStatus($id, $status);
        try { AuditService::registrar('equipe', Auth::equipeId(), 'tarefa.status', 'tarefas', $id, ['status' => $status]); } catch (\Throwable $e) {}
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/tarefas');
    }
}
