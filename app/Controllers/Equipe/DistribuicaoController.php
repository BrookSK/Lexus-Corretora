<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Demandas\DemandasService;
use LEX\App\Services\Matching\MatchingService;
use LEX\App\Services\Distribuicao\DistribuicaoService;
use LEX\App\Services\Timeline\TimelineService;
use LEX\App\Services\Audit\AuditService;

final class DistribuicaoController
{
    public function index(Requisicao $req): Resposta
    {
        $demandaId = (int)$req->param('demandaId');
        $demanda = DemandasService::obterPorId($demandaId);
        if (!$demanda) return Resposta::redirecionar('/equipe/demandas');
        $sugestoes = MatchingService::sugerirParceiros($demandaId, 20);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/distribuicao.php', [
            'demanda' => $demanda, 'sugestoes' => $sugestoes,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => 'Distribuição — ' . $demanda['code'],
            'breadcrumbs' => [['label' => I18n::t('sidebar.demandas'), 'url' => '/equipe/demandas'], ['label' => 'Distribuição']],
        ]));
    }

    public function distribuir(Requisicao $req): Resposta
    {
        $demandaId = (int)$req->param('demandaId');
        $parceiroIds = $req->post('parceiro_ids', []);
        if (empty($parceiroIds)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Selecione ao menos um parceiro.'];
            return Resposta::redirecionar('/equipe/distribuicao/' . $demandaId);
        }
        $distId = DistribuicaoService::distribuir($demandaId, $parceiroIds, Auth::equipeId());
        TimelineService::registrar($demandaId, 'distribuida', 'Oportunidade distribuída para ' . count($parceiroIds) . ' parceiro(s)', 'equipe', Auth::equipeId());
        AuditService::registrar('equipe', Auth::equipeId(), 'demanda.distribuir', 'demandas', $demandaId, ['parceiros' => $parceiroIds]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Oportunidade distribuída com sucesso.'];
        return Resposta::redirecionar('/equipe/demandas/' . $demandaId);
    }
}
