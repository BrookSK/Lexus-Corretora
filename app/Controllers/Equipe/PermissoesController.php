<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};
use LEX\App\Services\Audit\AuditService;

final class PermissoesController
{
    public function index(Requisicao $req): Resposta
    {
        $pdo = BancoDeDados::obter();
        $roles = $pdo->query("SELECT * FROM roles ORDER BY id")->fetchAll();
        $permissions = $pdo->query("SELECT * FROM permissions ORDER BY group_name, name")->fetchAll();
        $assigned = $pdo->query("SELECT role_id, permission_id FROM role_permissions")->fetchAll();
        $assignedMap = [];
        foreach ($assigned as $a) {
            $assignedMap[$a['role_id']][$a['permission_id']] = true;
        }
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/permissoes.php', [
            'roles' => $roles, 'permissions' => $permissions, 'assignedMap' => $assignedMap,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => I18n::t('sidebar.permissoes'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.permissoes')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $pdo = BancoDeDados::obter();
        $perms = $req->post('perms', []);
        $pdo->exec("DELETE FROM role_permissions");
        $stmt = $pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (:r, :p)");
        foreach ($perms as $key => $val) {
            [$roleId, $permId] = explode('_', $key);
            $stmt->execute(['r' => (int)$roleId, 'p' => (int)$permId]);
        }
        AuditService::registrar('equipe', Auth::equipeId(), 'permissoes.salvar', 'role_permissions', null);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/permissoes');
    }
}
