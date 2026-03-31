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

        // DEBUG TEMPORÁRIO — remover depois
        if (!$cli) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'DEBUG: Nenhum usuário encontrado com esse email.'];
            return Resposta::redirecionar('/cliente/entrar');
        }
        if (!password_verify($senha, $cli['password'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'DEBUG: Usuário encontrado, mas senha não confere. Hash no banco: ' . substr($cli['password'], 0, 20) . '...'];
            return Resposta::redirecionar('/cliente/entrar');
        }
        // FIM DEBUG
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
        // TODO: Gerar token e enviar email
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
        // TODO: Validar token e redefinir senha
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('auth.senha_redefinida')];
        return Resposta::redirecionar('/cliente/entrar');
    }

    public function logout(Requisicao $req): Resposta
    {
        Auth::logoutCliente();
        return Resposta::redirecionar('/cliente/entrar');
    }
}
