<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};

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
        return Resposta::redirecionar('/equipe/entrar');
    }

    public function primeiroAcesso(Requisicao $req): Resposta
    {
        return Resposta::redirecionar('/equipe/entrar');
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
