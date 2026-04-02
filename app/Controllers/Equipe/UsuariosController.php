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
        $stmt = $pdo->query(
            "SELECT u.id, u.name, u.email, u.is_active, u.last_login_at, u.created_at,
                    (SELECT r.name FROM user_roles ur JOIN roles r ON r.id = ur.role_id
                     WHERE ur.user_id = u.id LIMIT 1) AS role_name
             FROM users u
             WHERE u.deleted_at IS NULL
             ORDER BY u.created_at DESC"
        );
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
        if (empty($roles)) {
            $defaults = [['superadmin','Super Admin'],['admin','Administrador'],['operador','Operador'],['comercial','Comercial']];
            $ins = $pdo->prepare("INSERT IGNORE INTO roles (slug, name) VALUES (:s, :n)");
            foreach ($defaults as [$s, $n]) $ins->execute(['s' => $s, 'n' => $n]);
            $roles = $pdo->query("SELECT * FROM roles ORDER BY name")->fetchAll();
        }
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/usuarios-criar.php', ['roles' => $roles]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => I18n::t('sidebar.usuarios'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.usuarios'), 'url' => '/equipe/usuarios'], ['label' => I18n::t('geral.criar')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $pdo = BancoDeDados::obter();
        $nome   = trim($req->post('name', ''));
        $email  = trim($req->post('email', ''));
        $senha  = $req->post('password', '');
        $roleId = (int)$req->post('role_id', '0');

        if (empty($nome) || empty($email)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Nome e e-mail são obrigatórios.'];
            return Resposta::redirecionar('/equipe/usuarios/novo');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'E-mail inválido.'];
            return Resposta::redirecionar('/equipe/usuarios/novo');
        }
        if (strlen($senha) < 8) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'A senha deve ter pelo menos 8 caracteres.'];
            return Resposta::redirecionar('/equipe/usuarios/novo');
        }

        $hash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_active) VALUES (:n, :e, :p, 1)");
            $stmt->execute(['n' => $nome, 'e' => $email, 'p' => $hash]);
            $userId = (int)$pdo->lastInsertId();
        } catch (\PDOException $e) {
            $msg = (str_contains($e->getMessage(), '1062') || str_contains($e->getMessage(), 'Duplicate'))
                ? 'Este e-mail já está cadastrado.'
                : 'Erro ao criar usuário. Tente novamente.';
            $_SESSION['flash'] = ['type' => 'error', 'message' => $msg];
            return Resposta::redirecionar('/equipe/usuarios/novo');
        }

        if ($roleId > 0) {
            try {
                $pdo->prepare("INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (:u, :r)")->execute(['u' => $userId, 'r' => $roleId]);
            } catch (\PDOException $e) { /* silenciar — role inválida */ }
        }

        try { AuditService::registrar('equipe', Auth::equipeId(), 'usuario.criar', 'users', $userId); } catch (\Throwable $e) { /* silenciar */ }

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
        try { AuditService::registrar('equipe', Auth::equipeId(), 'usuario.atualizar', 'users', $id); } catch (\Throwable $e) { /* silenciar */ }
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/usuarios');
    }
}
