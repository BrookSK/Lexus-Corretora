<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Parceiro;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};

final class AuthController
{
    public function loginForm(Requisicao $req): Resposta
    {
        if (Auth::parceiroLogado()) return Resposta::redirecionar('/parceiro/dashboard');
        $html = View::renderizar(__DIR__ . '/../../Views/parceiro/auth/login.php');
        return Resposta::html($html);
    }

    public function login(Requisicao $req): Resposta
    {
        $email = trim($req->post('email', ''));
        $senha = $req->post('password', '');
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("SELECT * FROM parceiros WHERE email = :e AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['e' => $email]);
        $par = $stmt->fetch();

        if (!$par || !password_verify($senha, $par['password'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('auth.login_falha')];
            return Resposta::redirecionar('/parceiro/entrar');
        }
        if (!$par['is_active']) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('auth.conta_inativa')];
            return Resposta::redirecionar('/parceiro/entrar');
        }

        Auth::loginParceiro($par);
        $pdo->prepare("UPDATE parceiros SET last_login_at = NOW() WHERE id = :id")->execute(['id' => $par['id']]);
        return Resposta::redirecionar('/parceiro/dashboard');
    }

    public function registroForm(Requisicao $req): Resposta
    {
        $html = View::renderizar(__DIR__ . '/../../Views/parceiro/auth/registro.php');
        return Resposta::html($html);
    }

    public function registro(Requisicao $req): Resposta
    {
        $nome  = trim($req->post('name', ''));
        $email = trim($req->post('email', ''));
        $senha = $req->post('password', '');
        $tipo  = $req->post('type', 'prestador');

        if (empty($nome) || empty($email) || strlen($senha) < 8) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('erro.validacao')];
            return Resposta::redirecionar('/parceiro/criar-conta');
        }

        $pdo = BancoDeDados::obter();
        $hash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare("INSERT INTO parceiros (name, email, password, type, status, is_active, created_at) VALUES (:n, :e, :p, :t, 'cadastrado', 1, NOW())");
        $stmt->execute(['n' => $nome, 'e' => $email, 'p' => $hash, 't' => $tipo]);
        $parceiroId = (int)$pdo->lastInsertId();

        try { \LEX\App\Services\Email\EmailService::boasVindasParceiro($email, $nome); } catch (\Throwable $e) { /* silenciar */ }

        Auth::loginParceiro(['id' => $parceiroId, 'name' => $nome, 'email' => $email]);
        return Resposta::redirecionar('/parceiro/dashboard');
    }

    public function esqueciSenhaForm(Requisicao $req): Resposta
    {
        $html = View::renderizar(__DIR__ . '/../../Views/parceiro/auth/esqueci-senha.php');
        return Resposta::html($html);
    }

    public function esqueciSenha(Requisicao $req): Resposta
    {
        $email = trim($req->post('email', ''));
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $pdo = \LEX\Core\BancoDeDados::obter();
            $stmt = $pdo->prepare("SELECT id, name FROM parceiros WHERE email = :e AND deleted_at IS NULL AND is_active = 1");
            $stmt->execute(['e' => $email]);
            $parceiro = $stmt->fetch();
            if ($parceiro) {
                $token = bin2hex(random_bytes(32));
                $expira = date('Y-m-d H:i:s', strtotime('+2 hours'));
                $pdo->prepare("DELETE FROM password_resets WHERE user_type = 'parceiro' AND email = :e")->execute(['e' => $email]);
                $pdo->prepare("INSERT INTO password_resets (user_type, email, token, expires_at) VALUES ('parceiro', :e, :t, :ex)")->execute(['e' => $email, 't' => $token, 'ex' => $expira]);
                $link = (\LEX\Core\SistemaConfig::url()) . '/parceiro/redefinir-senha/' . $token;
                \LEX\App\Services\Email\EmailService::enviar(
                    $email,
                    'Redefinição de senha',
                    '<p>Olá, ' . htmlspecialchars($parceiro['name']) . '!</p><p>Clique no link abaixo para redefinir sua senha (válido por 2 horas):</p><p><a href="' . $link . '">' . $link . '</a></p><p>Se não solicitou, ignore este e-mail.</p>'
                );
            }
        }
        $_SESSION['flash'] = ['type' => 'info', 'message' => I18n::t('auth.email_enviado')];
        return Resposta::redirecionar('/parceiro/esqueci-senha');
    }

    public function redefinirSenhaForm(Requisicao $req): Resposta
    {
        $html = View::renderizar(__DIR__ . '/../../Views/parceiro/auth/redefinir-senha.php', ['token' => $req->param('token')]);
        return Resposta::html($html);
    }

    public function redefinirSenha(Requisicao $req): Resposta
    {
        $token = trim($req->post('token', ''));
        $senha = $req->post('password', '');
        $confirmacao = $req->post('password_confirmation', '');

        if (empty($token) || empty($senha) || $senha !== $confirmacao || strlen($senha) < 8) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Dados inválidos. Verifique a senha.'];
            return Resposta::redirecionar('/parceiro/redefinir-senha/' . $token);
        }

        $pdo = \LEX\Core\BancoDeDados::obter();
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = :t AND user_type = 'parceiro' AND expires_at > NOW()");
        $stmt->execute(['t' => $token]);
        $reset = $stmt->fetch();

        if (!$reset) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Link inválido ou expirado.'];
            return Resposta::redirecionar('/parceiro/esqueci-senha');
        }

        $hash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
        $pdo->prepare("UPDATE parceiros SET password = :p WHERE email = :e")->execute(['p' => $hash, 'e' => $reset['email']]);
        $pdo->prepare("DELETE FROM password_resets WHERE token = :t")->execute(['t' => $token]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('auth.senha_redefinida')];
        return Resposta::redirecionar('/parceiro/entrar');
    }

    public function logout(Requisicao $req): Resposta
    {
        Auth::logoutParceiro();
        return Resposta::redirecionar('/parceiro/entrar');
    }
}
