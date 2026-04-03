<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Cliente;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};

final class AuthController
{
    public function loginForm(Requisicao $req): Resposta
    {
        if (Auth::clienteLogado()) return Resposta::redirecionar('/cliente/dashboard');
        $html = View::renderizar(__DIR__ . '/../../Views/cliente/auth/login.php');
        return Resposta::html($html);
    }

    public function login(Requisicao $req): Resposta
    {
        $email = trim($req->post('email', ''));
        $senha = $req->post('password', '');
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE email = :e AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['e' => $email]);
        $cli = $stmt->fetch();

        if (!$cli || !password_verify($senha, $cli['password'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('auth.login_falha')];
            return Resposta::redirecionar('/cliente/entrar');
        }
        if (!$cli['is_active']) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('auth.conta_inativa')];
            return Resposta::redirecionar('/cliente/entrar');
        }

        Auth::loginCliente($cli);
        $pdo->prepare("UPDATE clientes SET last_login_at = NOW() WHERE id = :id")->execute(['id' => $cli['id']]);
        return Resposta::redirecionar('/cliente/dashboard');
    }

    public function registroForm(Requisicao $req): Resposta
    {
        $html = View::renderizar(__DIR__ . '/../../Views/cliente/auth/registro.php');
        return Resposta::html($html);
    }

    public function registro(Requisicao $req): Resposta
    {
        $nome  = trim($req->post('name', ''));
        $email = trim($req->post('email', ''));
        $senha = $req->post('password', '');

        if (empty($nome) || empty($email) || strlen($senha) < 8) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('erro.validacao')];
            return Resposta::redirecionar('/cliente/criar-conta');
        }

        $pdo = BancoDeDados::obter();
        $exists = $pdo->prepare("SELECT id FROM clientes WHERE email = :e");
        $exists->execute(['e' => $email]);
        if ($exists->fetch()) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'E-mail já cadastrado.'];
            return Resposta::redirecionar('/cliente/criar-conta');
        }

        $hash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare("INSERT INTO clientes (name, email, password, is_active, created_at) VALUES (:n, :e, :p, 1, NOW())");
        $stmt->execute(['n' => $nome, 'e' => $email, 'p' => $hash]);

        Auth::loginCliente(['id' => (int)$pdo->lastInsertId(), 'name' => $nome, 'email' => $email]);
        return Resposta::redirecionar('/cliente/dashboard');
    }

    public function esqueciSenhaForm(Requisicao $req): Resposta
    {
        $html = View::renderizar(__DIR__ . '/../../Views/cliente/auth/esqueci-senha.php');
        return Resposta::html($html);
    }

    public function esqueciSenha(Requisicao $req): Resposta
    {
        $email = trim($req->post('email', ''));
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $pdo = \LEX\Core\BancoDeDados::obter();
            $stmt = $pdo->prepare("SELECT id, name FROM clientes WHERE email = :e AND deleted_at IS NULL AND is_active = 1");
            $stmt->execute(['e' => $email]);
            $cliente = $stmt->fetch();
            if ($cliente) {
                $token = bin2hex(random_bytes(32));
                $expira = date('Y-m-d H:i:s', strtotime('+2 hours'));
                $pdo->prepare("DELETE FROM password_resets WHERE user_type = 'cliente' AND email = :e")->execute(['e' => $email]);
                $pdo->prepare("INSERT INTO password_resets (user_type, email, token, expires_at) VALUES ('cliente', :e, :t, :ex)")->execute(['e' => $email, 't' => $token, 'ex' => $expira]);
                $link = (\LEX\Core\SistemaConfig::url()) . '/cliente/redefinir-senha/' . $token;
                \LEX\App\Services\Email\EmailService::enviar(
                    $email,
                    'Redefinição de senha',
                    '<p>Olá, ' . htmlspecialchars($cliente['name']) . '!</p><p>Clique no link abaixo para redefinir sua senha (válido por 2 horas):</p><p><a href="' . $link . '">' . $link . '</a></p><p>Se não solicitou, ignore este e-mail.</p>'
                );
            }
        }
        // Sempre mostrar a mesma mensagem por segurança
        $_SESSION['flash'] = ['type' => 'info', 'message' => I18n::t('auth.email_enviado')];
        return Resposta::redirecionar('/cliente/esqueci-senha');
    }

    public function redefinirSenhaForm(Requisicao $req): Resposta
    {
        $html = View::renderizar(__DIR__ . '/../../Views/cliente/auth/redefinir-senha.php', ['token' => $req->param('token')]);
        return Resposta::html($html);
    }

    public function redefinirSenha(Requisicao $req): Resposta
    {
        $token = trim($req->post('token', ''));
        $senha = $req->post('password', '');
        $confirmacao = $req->post('password_confirmation', '');

        if (empty($token) || empty($senha) || $senha !== $confirmacao || strlen($senha) < 8) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Dados inválidos. Verifique a senha.'];
            return Resposta::redirecionar('/cliente/redefinir-senha/' . $token);
        }

        $pdo = \LEX\Core\BancoDeDados::obter();
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = :t AND user_type = 'cliente' AND expires_at > NOW()");
        $stmt->execute(['t' => $token]);
        $reset = $stmt->fetch();

        if (!$reset) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Link inválido ou expirado.'];
            return Resposta::redirecionar('/cliente/esqueci-senha');
        }

        $hash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
        $pdo->prepare("UPDATE clientes SET password = :p WHERE email = :e")->execute(['p' => $hash, 'e' => $reset['email']]);
        $pdo->prepare("DELETE FROM password_resets WHERE token = :t")->execute(['t' => $token]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('auth.senha_redefinida')];
        return Resposta::redirecionar('/cliente/entrar');
    }

    public function logout(Requisicao $req): Resposta
    {
        Auth::logoutCliente();
        return Resposta::redirecionar('/cliente/entrar');
    }
}
