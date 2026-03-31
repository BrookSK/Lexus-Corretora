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

        Auth::loginParceiro(['id' => (int)$pdo->lastInsertId(), 'name' => $nome, 'email' => $email]);
        return Resposta::redirecionar('/parceiro/dashboard');
    }

    public function esqueciSenhaForm(Requisicao $req): Resposta
    {
        $html = View::renderizar(__DIR__ . '/../../Views/parceiro/auth/esqueci-senha.php');
        return Resposta::html($html);
    }

    public function esqueciSenha(Requisicao $req): Resposta
    {
        $_SESSION['flash'] = ['type' => 'info', 'message' => I18n::t('auth.email_enviado')];
        return Resposta::redirecionar('/parceiro/esqueci-senha');
    }

    public function logout(Requisicao $req): Resposta
    {
        Auth::logoutParceiro();
        return Resposta::redirecionar('/parceiro/entrar');
    }
}
