<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Cliente;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};
use LEX\App\Services\Clientes\ClientesService;

final class ContaController
{
    public function index(Requisicao $req): Resposta
    {
        $cliente = ClientesService::obterPorId(Auth::clienteId());
        // Sincronizar sessão com banco (corrige nome desatualizado na sessão)
        if ($cliente && !empty($cliente['name'])) {
            $_SESSION['cliente_nome'] = $cliente['name'];
        }
        $conteudo = View::renderizar(__DIR__ . '/../../Views/cliente/minha-conta.php', ['cliente' => $cliente]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'cliente',
            'pageTitle' => I18n::t('sidebar_cli.minha_conta'),
            'breadcrumbs' => [['label' => I18n::t('sidebar_cli.minha_conta')]],
        ]));
    }

    private static function salvarAvatar(int $id): ?string
    {
        $arq = $_FILES['avatar'] ?? [];
        if (empty($arq['tmp_name']) || ($arq['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return null;
        $ext = strtolower(pathinfo($arq['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp','gif'], true)) return null;
        $dest = 'uploads/avatars/cliente_' . $id . '_' . time() . '.' . $ext;
        $dir  = dirname(__DIR__, 3) . '/public/' . dirname($dest);
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        if (!move_uploaded_file($arq['tmp_name'], dirname(__DIR__, 3) . '/public/' . $dest)) return null;
        return $dest;
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token'], $dados['current_password'], $dados['new_password_confirmation']);
        if (!empty($dados['new_password'])) {
            $dados['password'] = $dados['new_password'];
        }
        unset($dados['new_password']);
        ClientesService::atualizar(Auth::clienteId(), $dados);

        // Avatar
        $avatar = self::salvarAvatar(Auth::clienteId());
        if ($avatar !== null) {
            BancoDeDados::obter()
                ->prepare("UPDATE clientes SET avatar = :a WHERE id = :id")
                ->execute(['a' => $avatar, 'id' => Auth::clienteId()]);
            $_SESSION['cliente_avatar'] = $avatar;
        }
        if (!empty($dados['name'])) $_SESSION['cliente_nome'] = $dados['name'];

        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/cliente/minha-conta');
    }
}
