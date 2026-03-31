<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Parceiro;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
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

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token'], $dados['current_password'], $dados['new_password_confirmation']);
        if (!empty($dados['new_password'])) {
            $dados['password'] = $dados['new_password'];
        }
        unset($dados['new_password']);
        ParceirosService::atualizar(Auth::parceiroId(), $dados);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/parceiro/minha-conta');
    }
}
