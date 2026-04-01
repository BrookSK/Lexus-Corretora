<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};

final class ContaController
{
    public function index(Requisicao $req): Resposta
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => Auth::equipeId()]);
        $usuario = $stmt->fetch();
        $stmt->closeCursor();

        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/minha-conta.php', [
            'usuario' => $usuario,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo'   => $conteudo,
            'painelTipo' => 'equipe',
            'pageTitle'  => I18n::t('conta.titulo'),
            'breadcrumbs' => [['label' => I18n::t('conta.titulo')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $pdo = BancoDeDados::obter();
        $userId = Auth::equipeId();
        $nome = trim($req->post('name', ''));
        $email = trim($req->post('email', ''));

        if (empty($nome) || empty($email)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('erro.validacao')];
            return Resposta::redirecionar('/equipe/minha-conta');
        }

        $pdo->prepare("UPDATE users SET name = :n, email = :e WHERE id = :id")
            ->execute(['n' => $nome, 'e' => $email, 'id' => $userId]);

        // Alterar senha se preenchida
        $novaSenha = $req->post('new_password', '');
        $confirmaSenha = $req->post('new_password_confirmation', '');
        if (!empty($novaSenha)) {
            if ($novaSenha !== $confirmaSenha) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('erro.senhas_nao_coincidem')];
                return Resposta::redirecionar('/equipe/minha-conta');
            }
            if (strlen($novaSenha) >= 8) {
                $hash = password_hash($novaSenha, PASSWORD_BCRYPT, ['cost' => 12]);
                $pdo->prepare("UPDATE users SET password = :p WHERE id = :id")
                    ->execute(['p' => $hash, 'id' => $userId]);
            }
        }

        // Atualizar sessão
        $_SESSION['equipe_nome'] = $nome;
        $_SESSION['equipe_email'] = $email;

        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/minha-conta');
    }
}
