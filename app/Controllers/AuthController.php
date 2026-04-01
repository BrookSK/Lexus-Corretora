<?php
declare(strict_types=1);
namespace LEX\App\Controllers;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};

final class AuthController
{
    public function loginForm(Requisicao $req): Resposta
    {
        // Se já está logado redireciona para o dashboard correto
        if (Auth::equipeLogada())   return Resposta::redirecionar('/equipe/dashboard');
        if (Auth::parceiroLogado()) return Resposta::redirecionar('/parceiro/dashboard');
        if (Auth::clienteLogado())  return Resposta::redirecionar('/cliente/dashboard');

        $html = View::renderizar(__DIR__ . '/../Views/auth/login.php');
        return Resposta::html($html);
    }

    public function login(Requisicao $req): Resposta
    {
        $email = trim($req->post('email', ''));
        $senha = $req->post('password', '');
        $pdo   = BancoDeDados::obter();

        // 1) Equipe / users
        $stmt = $pdo->prepare(
            "SELECT u.*, r.slug AS role_slug
             FROM users u
             LEFT JOIN user_roles ur ON ur.user_id = u.id
             LEFT JOIN roles r ON r.id = ur.role_id
             WHERE u.email = :e AND u.deleted_at IS NULL LIMIT 1"
        );
        $stmt->execute(['e' => $email]);
        $user = $stmt->fetch();
        if ($user && password_verify($senha, $user['password'])) {
            if (!$user['is_active']) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('auth.conta_inativa')];
                return Resposta::redirecionar('/login');
            }
            Auth::loginEquipe($user);
            $pdo->prepare("UPDATE users SET last_login_at = NOW() WHERE id = :id")->execute(['id' => $user['id']]);
            self::logAuth('equipe', $user['id'], $email, 'login', $req);
            return Resposta::redirecionar('/equipe/dashboard');
        }

        // 2) Parceiros
        $stmt = $pdo->prepare("SELECT * FROM parceiros WHERE email = :e AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['e' => $email]);
        $par = $stmt->fetch();
        if ($par && password_verify($senha, $par['password'])) {
            if (!$par['is_active']) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('auth.conta_inativa')];
                return Resposta::redirecionar('/login');
            }
            Auth::loginParceiro($par);
            $pdo->prepare("UPDATE parceiros SET last_login_at = NOW() WHERE id = :id")->execute(['id' => $par['id']]);
            return Resposta::redirecionar('/parceiro/dashboard');
        }

        // 3) Clientes
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE email = :e AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['e' => $email]);
        $cli = $stmt->fetch();
        if ($cli && password_verify($senha, $cli['password'])) {
            if (!$cli['is_active']) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('auth.conta_inativa')];
                return Resposta::redirecionar('/login');
            }
            Auth::loginCliente($cli);
            $pdo->prepare("UPDATE clientes SET last_login_at = NOW() WHERE id = :id")->execute(['id' => $cli['id']]);
            return Resposta::redirecionar('/cliente/dashboard');
        }

        self::logAuth('unknown', null, $email, 'login_failed', $req);
        $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('auth.login_falha')];
        return Resposta::redirecionar('/login');
    }

    public function logout(Requisicao $req): Resposta
    {
        if (Auth::equipeLogada()) {
            self::logAuth('equipe', Auth::equipeId(), Auth::equipeEmail(), 'logout', $req);
            Auth::logoutEquipe();
        } elseif (Auth::parceiroLogado()) {
            Auth::logoutParceiro();
        } elseif (Auth::clienteLogado()) {
            Auth::logoutCliente();
        }
        return Resposta::redirecionar('/login');
    }

    private static function logAuth(string $tipo, ?int $userId, ?string $email, string $action, Requisicao $req): void
    {
        try {
            $pdo = BancoDeDados::obter();
            $pdo->prepare(
                "INSERT INTO auth_logs (user_type, user_id, email, action, ip, user_agent, created_at)
                 VALUES (:t, :u, :e, :a, :ip, :ua, NOW())"
            )->execute(['t' => $tipo, 'u' => $userId, 'e' => $email, 'a' => $action, 'ip' => $req->ip(), 'ua' => $req->userAgent()]);
        } catch (\Exception) {}
    }
}
