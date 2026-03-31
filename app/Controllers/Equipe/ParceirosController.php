<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Parceiros\ParceirosService;
use LEX\App\Services\Qualificacao\QualificacaoService;
use LEX\App\Services\Audit\AuditService;

final class ParceirosController
{
    public function index(Requisicao $req): Resposta
    {
        $page = max(1, (int)$req->get('page', '1'));
        $filtros = array_filter([
            'busca' => $req->get('busca'), 'status' => $req->get('status'),
            'type' => $req->get('type'), 'is_vetriks' => $req->get('is_vetriks'),
        ]);
        $resultado = ParceirosService::listar($page, 20, $filtros);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/parceiros.php', [
            'items' => $resultado['items'], 'total' => $resultado['total'], 'page' => $page,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.parceiros'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.parceiros')]],
        ]));
    }

    public function criar(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/parceiros-criar.php', []);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.parceiros'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.parceiros'), 'url' => '/equipe/parceiros'], ['label' => I18n::t('geral.criar')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        $id = ParceirosService::criar($dados);
        AuditService::registrar('equipe', Auth::equipeId(), 'parceiro.criar', 'parceiros', $id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/parceiros/' . $id);
    }

    public function detalhe(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $parceiro = ParceirosService::obterPorId($id);
        if (!$parceiro) return Resposta::redirecionar('/equipe/parceiros');
        $qualificacao = QualificacaoService::obterPorParceiro($id);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/parceiros-detalhe.php', [
            'parceiro' => $parceiro, 'qualificacao' => $qualificacao,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => $parceiro['name'],
            'breadcrumbs' => [['label' => I18n::t('sidebar.parceiros'), 'url' => '/equipe/parceiros'], ['label' => $parceiro['name']]],
        ]));
    }

    public function editar(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $parceiro = ParceirosService::obterPorId($id);
        if (!$parceiro) return Resposta::redirecionar('/equipe/parceiros');
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/parceiros-editar.php', ['parceiro' => $parceiro]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('geral.editar'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.parceiros'), 'url' => '/equipe/parceiros'], ['label' => I18n::t('geral.editar')]],
        ]));
    }

    public function atualizar(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        ParceirosService::atualizar($id, $dados);
        AuditService::registrar('equipe', Auth::equipeId(), 'parceiro.atualizar', 'parceiros', $id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/parceiros/' . $id);
    }
}
