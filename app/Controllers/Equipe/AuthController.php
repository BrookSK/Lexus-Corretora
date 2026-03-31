<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados, AppLogger};

final class AuthController
{
    public function loginForm(Requisicao $req): Resposta
    {
        if (Auth::equipeLogada()) return Resposta::redirecionar('/equipe/dashboard');
        $html = View::renderizar(__DIR__ . '/../../Views/equipe/auth/login.php');
        return Resposta::html($html);
    }

    public function login(Requisicao $req): Resposta
    {
        $email = trim($req->post('email', ''));
        $senha = $req->post('password', '');
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("SELECT u.*, r.slug as role_slug FROM users u LEFT JOIN user_roles ur ON ur.user_id = u.id LEFT JOIN roles r ON r.id = ur.role_id WHERE u.email = :e AND u.deleted_at IS NULL LIMIT 1");
        $stmt->execute(['e' => $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($senha, $user['password'])) {
            self::logAuth('equipe', null, $email, 'login_failed', $req);
            $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('auth.login_falha')];
            return Resposta::redirecionar('/equipe/entrar');
        }
        if (!$user['is_active']) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('auth.conta_inativa')];
            return Resposta::redirecionar('/equipe/entrar');
        }

        Auth::loginEquipe($user);
        $pdo->prepare("UPDATE users SET last_login_at = NOW() WHERE id = :id")->execute(['id' => $user['id']]);
        self::logAuth('equipe', $user['id'], $email, 'login', $req);
        return Resposta::redirecionar('/equipe/dashboard');
    }

    public function primeiroAcessoForm(Requisicao $req): Resposta
    {
        try {
            $pdo = BancoDeDados::obter();
            $count = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
            if ($count > 0) return Resposta::redirecionar('/equipe/entrar');
        } catch (\Exception $e) {
            // Tabela users pode não existir ainda — tudo bem, é primeiro acesso
        }
        $html = View::renderizar(__DIR__ . '/../../Views/equipe/auth/primeiro-acesso.php');
        return Resposta::html($html);
    }

    public function primeiroAcesso(Requisicao $req): Resposta
    {
        $pdo = BancoDeDados::obter();

        // Garantir que as tabelas existem antes de criar o superadmin
        try {
            $tabelas = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
            if (empty($tabelas) || !in_array('users', $tabelas)) {
                $schema = __DIR__ . '/../../../database/schema.sql';
                if (file_exists($schema)) {
                    $sql = file_get_contents($schema);
                    $pdo->exec($sql);
                }
            }
        } catch (\Exception $e) {
            AppLogger::erro('Erro ao criar schema no primeiro acesso: ' . $e->getMessage());
        }

        try {
            $count = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
            if ($count > 0) return Resposta::redirecionar('/equipe/entrar');
        } catch (\Exception $e) {
            // Se ainda falhar, algo está errado com o banco
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erro ao acessar o banco de dados. Verifique config/instalacao.php'];
            return Resposta::redirecionar('/equipe/primeiro-acesso');
        }

        $nome  = trim($req->post('name', ''));
        $email = trim($req->post('email', ''));
        $senha = $req->post('password', '');

        if (empty($nome) || empty($email) || strlen($senha) < 8) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('erro.validacao')];
            return Resposta::redirecionar('/equipe/primeiro-acesso');
        }

        $hash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_active, created_at) VALUES (:n, :e, :p, 1, NOW())");
        $stmt->execute(['n' => $nome, 'e' => $email, 'p' => $hash]);
        $userId = (int)$pdo->lastInsertId();

        // Atribuir role superadmin
        $roleId = (int)$pdo->query("SELECT id FROM roles WHERE slug = 'superadmin' LIMIT 1")->fetchColumn();
        if ($roleId) {
            $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:u, :r)")->execute(['u' => $userId, 'r' => $roleId]);
        }

        Auth::loginEquipe(['id' => $userId, 'name' => $nome, 'email' => $email, 'role_slug' => 'superadmin']);
        return Resposta::redirecionar('/equipe/inicializacao');
    }

    public function logout(Requisicao $req): Resposta
    {
        self::logAuth('equipe', Auth::equipeId(), Auth::equipeEmail(), 'logout', $req);
        Auth::logoutEquipe();
        return Resposta::redirecionar('/equipe/entrar');
    }

    private static function logAuth(string $tipo, ?int $userId, ?string $email, string $action, Requisicao $req): void
    {
        try {
            $pdo = BancoDeDados::obter();
            $stmt = $pdo->prepare("INSERT INTO auth_logs (user_type, user_id, email, action, ip, user_agent, created_at) VALUES (:t, :u, :e, :a, :ip, :ua, NOW())");
            $stmt->execute(['t' => $tipo, 'u' => $userId, 'e' => $email, 'a' => $action, 'ip' => $req->ip(), 'ua' => $req->userAgent()]);
        } catch (\Exception $e) { /* silenciar */ }
    }
}
