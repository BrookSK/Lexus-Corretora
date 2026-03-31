<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Cliente;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Clientes\ClientesService;

final class ContaController
{
    public function index(Requisicao $req): Resposta
    {
        $cliente = ClientesService::obterPorId(Auth::clienteId());
        $conteudo = View::renderizar(__DIR__ . '/../../Views/cliente/minha-conta.php', ['cliente' => $cliente]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'cliente',
            'pageTitle' => I18n::t('sidebar_cli.minha_conta'),
            'breadcrumbs' => [['label' => I18n::t('sidebar_cli.minha_conta')]],
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
        ClientesService::atualizar(Auth::clienteId(), $dados);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/cliente/minha-conta');
    }
}
