<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};
use LEX\App\Services\Arquivos\ArquivosService;

final class ContaController
{
    private static function salvarAvatar(string $tabela, int $id): ?string
    {
        $arq = $_FILES['avatar'] ?? [];
        if (empty($arq['tmp_name']) || ($arq['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return null;
        $ext  = strtolower(pathinfo($arq['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp','gif'], true)) return null;
        $dest = 'uploads/avatars/' . $tabela . '_' . $id . '_' . time() . '.' . $ext;
        $dir  = dirname(__DIR__, 3) . '/public/' . dirname($dest);
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        if (!move_uploaded_file($arq['tmp_name'], dirname(__DIR__, 3) . '/public/' . $dest)) return null;
        return $dest;
    }

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

        // Avatar
        $avatar = self::salvarAvatar('user', $userId);
        if ($avatar !== null) {
            $pdo->prepare("UPDATE users SET avatar = :a WHERE id = :id")->execute(['a' => $avatar, 'id' => $userId]);
            $_SESSION['equipe_avatar'] = $avatar;
        }

        // Atualizar sessão
        $_SESSION['equipe_nome']  = $nome;
        $_SESSION['equipe_email'] = $email;

        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/minha-conta');
    }
}
