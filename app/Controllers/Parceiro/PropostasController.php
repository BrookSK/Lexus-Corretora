<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Parceiro;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Propostas\PropostasService;
use LEX\App\Services\Demandas\DemandasService;
use LEX\App\Services\Timeline\TimelineService;

final class PropostasController
{
    public function index(Requisicao $req): Resposta
    {
        $propostas = PropostasService::listarPorParceiro(Auth::parceiroId());
        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/propostas.php', ['propostas' => $propostas]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'parceiro',
            'pageTitle' => I18n::t('sidebar_par.propostas'),
            'breadcrumbs' => [['label' => I18n::t('sidebar_par.propostas')]],
        ]));
    }

    public function criar(Requisicao $req): Resposta
    {
        $demandaId = (int)$req->param('demandaId');
        $demanda = DemandasService::obterPorId($demandaId);
        if (!$demanda) return Resposta::redirecionar('/parceiro/oportunidades');
        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/propostas-criar.php', ['demanda' => $demanda]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'parceiro',
            'pageTitle' => 'Enviar Proposta',
            'breadcrumbs' => [['label' => I18n::t('sidebar_par.propostas'), 'url' => '/parceiro/propostas'], ['label' => 'Nova']],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        $dados['parceiro_id'] = Auth::parceiroId();
        $dados['status'] = 'enviada';
        $id = PropostasService::criar($dados);
        if (!empty($dados['demanda_id'])) {
            TimelineService::registrar((int)$dados['demanda_id'], 'proposta_enviada', 'Nova proposta recebida', 'parceiro', Auth::parceiroId());
        }
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Proposta enviada com sucesso.'];
        return Resposta::redirecionar('/parceiro/propostas');
    }
}
