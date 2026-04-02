<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Parceiro;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};
use LEX\App\Services\Parceiros\ParceirosService;

final class ContaController
{
    public function index(Requisicao $req): Resposta
    {
        $parceiro = ParceirosService::obterPorId(Auth::parceiroId());
        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/minha-conta.php', ['parceiro' => $parceiro]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'parceiro',
            'pageTitle' => I18n::t('sidebar_par.minha_conta'),
            'breadcrumbs' => [['label' => I18n::t('sidebar_par.minha_conta')]],
        ]));
    }

    private static function salvarAvatar(int $id): ?string
    {
        $arq = $_FILES['avatar'] ?? [];
        if (empty($arq['tmp_name']) || ($arq['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return null;
        $ext = strtolower(pathinfo($arq['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp','gif'], true)) return null;
        $dest = 'uploads/avatars/parceiro_' . $id . '_' . time() . '.' . $ext;
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
        ParceirosService::atualizar(Auth::parceiroId(), $dados);

        // Avatar
        $avatar = self::salvarAvatar(Auth::parceiroId());
        if ($avatar !== null) {
            BancoDeDados::obter()
                ->prepare("UPDATE parceiros SET avatar = :a WHERE id = :id")
                ->execute(['a' => $avatar, 'id' => Auth::parceiroId()]);
            $_SESSION['parceiro_avatar'] = $avatar;
        }
        if (!empty($dados['name'])) $_SESSION['parceiro_nome'] = $dados['name'];

        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/parceiro/minha-conta');
    }
}
