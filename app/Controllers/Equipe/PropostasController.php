<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Propostas\PropostasService;
use LEX\App\Services\Audit\AuditService;
use LEX\App\Services\Arquivos\ArquivosService;

final class PropostasController
{
    public function index(Requisicao $req): Resposta
    {
        $page = max(1, (int)$req->get('page', '1'));
        $filtros = array_filter([
            'status' => $req->get('status'), 'demanda_id' => $req->get('demanda_id'),
        ]);
        $resultado = PropostasService::listar($page, 20, $filtros);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/propostas.php', [
            'items' => $resultado['items'], 'total' => $resultado['total'],
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.propostas'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.propostas')]],
        ]));
    }

    public function detalhe(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $proposta = PropostasService::obterPorId($id);
        if (!$proposta) return Resposta::redirecionar('/equipe/propostas');
        $proposta['arquivos'] = ArquivosService::listarPorEntidade('proposta', $id);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/propostas-detalhe.php', ['proposta' => $proposta]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.propostas'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.propostas'), 'url' => '/equipe/propostas'], ['label' => I18n::t('geral.detalhes')]],
        ]));
    }

    public function comparar(Requisicao $req): Resposta
    {
        $demandaId = (int)$req->param('demandaId');
        $propostas = PropostasService::compararPorDemanda($demandaId);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/propostas-comparar.php', [
            'propostas' => $propostas, 'demandaId' => $demandaId,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => 'Comparar Propostas',
            'breadcrumbs' => [['label' => I18n::t('sidebar.propostas'), 'url' => '/equipe/propostas'], ['label' => 'Comparar']],
        ]));
    }

    public function alterarStatus(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $status = $req->post('status', '');
        PropostasService::alterarStatus($id, $status);
        AuditService::registrar('equipe', Auth::equipeId(), 'proposta.status', 'propostas', $id, ['status' => $status]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/propostas/' . $id);
    }
}
