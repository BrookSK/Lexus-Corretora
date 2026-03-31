<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};
use LEX\App\Services\Audit\AuditService;

final class UsuariosController
{
    public function index(Requisicao $req): Resposta
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->query("SELECT u.id, u.name, u.email, u.is_active, u.last_login_at, u.created_at, GROUP_CONCAT(r.name) AS roles FROM users u LEFT JOIN user_roles ur ON ur.user_id = u.id LEFT JOIN roles r ON r.id = ur.role_id WHERE u.deleted_at IS NULL GROUP BY u.id ORDER BY u.created_at DESC");
        $usuarios = $stmt->fetchAll();
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/usuarios.php', ['items' => $usuarios]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => I18n::t('sidebar.usuarios'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.usuarios')]],
        ]));
    }

    public function criar(Requisicao $req): Resposta
    {
        $pdo = BancoDeDados::obter();
        $roles = $pdo->query("SELECT * FROM roles ORDER BY name")->fetchAll();
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/usuarios-criar.php', ['roles' => $roles]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => I18n::t('sidebar.usuarios'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.usuarios'), 'url' => '/equipe/usuarios'], ['label' => I18n::t('geral.criar')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $pdo = BancoDeDados::obter();
        $nome = trim($req->post('name', ''));
        $email = trim($req->post('email', ''));
        $senha = $req->post('password', '');
        $roleId = (int)$req->post('role_id', '0');
        $hash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_active) VALUES (:n, :e, :p, 1)");
        $stmt->execute(['n' => $nome, 'e' => $email, 'p' => $hash]);
        $userId = (int)$pdo->lastInsertId();
        if ($roleId > 0) {
            $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:u, :r)")->execute(['u' => $userId, 'r' => $roleId]);
        }
        AuditService::registrar('equipe', Auth::equipeId(), 'usuario.criar', 'users', $userId);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/usuarios');
    }

    public function editar(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $pdo = BancoDeDados::obter();
        $user = $pdo->prepare("SELECT * FROM users WHERE id = :id AND deleted_at IS NULL");
        $user->execute(['id' => $id]);
        $usuario = $user->fetch();
        if (!$usuario) return Resposta::redirecionar('/equipe/usuarios');
        $roles = $pdo->query("SELECT * FROM roles ORDER BY name")->fetchAll();
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/usuarios-editar.php', ['usuario' => $usuario, 'roles' => $roles]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => I18n::t('geral.editar'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.usuarios'), 'url' => '/equipe/usuarios'], ['label' => I18n::t('geral.editar')]],
        ]));
    }

    public function atualizar(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $pdo = BancoDeDados::obter();
        $nome = trim($req->post('name', ''));
        $email = trim($req->post('email', ''));
        $isActive = (int)$req->post('is_active', '1');
        $pdo->prepare("UPDATE users SET name = :n, email = :e, is_active = :a WHERE id = :id")->execute(['n' => $nome, 'e' => $email, 'a' => $isActive, 'id' => $id]);
        $senha = $req->post('password', '');
        if (!empty($senha)) {
            $hash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
            $pdo->prepare("UPDATE users SET password = :p WHERE id = :id")->execute(['p' => $hash, 'id' => $id]);
        }
        AuditService::registrar('equipe', Auth::equipeId(), 'usuario.atualizar', 'users', $id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/usuarios');
    }
}
