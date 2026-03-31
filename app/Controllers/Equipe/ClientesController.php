<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Clientes\ClientesService;
use LEX\App\Services\Audit\AuditService;

final class ClientesController
{
    public function index(Requisicao $req): Resposta
    {
        $page = max(1, (int)$req->get('page', '1'));
        $filtros = array_filter([
            'busca' => $req->get('busca'),
            'is_active' => $req->get('is_active'),
            'city' => $req->get('city'),
        ]);
        $resultado = ClientesService::listar($page, 20, $filtros);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/clientes.php', [
            'items' => $resultado['items'], 'total' => $resultado['total'], 'page' => $page,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.clientes'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.clientes')]],
        ]));
    }

    public function criar(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/clientes-criar.php', []);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.clientes'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.clientes'), 'url' => '/equipe/clientes'], ['label' => I18n::t('geral.criar')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        $id = ClientesService::criar($dados);
        AuditService::registrar('equipe', Auth::equipeId(), 'cliente.criar', 'clientes', $id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/clientes');
    }

    public function detalhe(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $cliente = ClientesService::obterPorId($id);
        if (!$cliente) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('geral.nenhum_registro')];
            return Resposta::redirecionar('/equipe/clientes');
        }
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/clientes-detalhe.php', ['cliente' => $cliente]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => $cliente['name'],
            'breadcrumbs' => [['label' => I18n::t('sidebar.clientes'), 'url' => '/equipe/clientes'], ['label' => $cliente['name']]],
        ]));
    }

    public function editar(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $cliente = ClientesService::obterPorId($id);
        if (!$cliente) return Resposta::redirecionar('/equipe/clientes');
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/clientes-editar.php', ['cliente' => $cliente]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('geral.editar'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.clientes'), 'url' => '/equipe/clientes'], ['label' => I18n::t('geral.editar')]],
        ]));
    }

    public function atualizar(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        ClientesService::atualizar($id, $dados);
        AuditService::registrar('equipe', Auth::equipeId(), 'cliente.atualizar', 'clientes', $id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/clientes/' . $id);
    }
}
