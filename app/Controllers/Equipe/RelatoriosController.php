<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n};
use LEX\App\Services\Relatorios\RelatoriosService;

final class RelatoriosController
{
    public function index(Requisicao $req): Resposta
    {
        $periodo = $req->get('periodo', '365');
        $inicio  = $req->get('inicio', '');
        $fim     = $req->get('fim',    '');

        if ($inicio === '' || $fim === '') {
            $dias   = in_array($periodo, ['30','90','180','365']) ? (int)$periodo : 365;
            $inicio = date('Y-m-d', strtotime("-{$dias} days"));
            $fim    = date('Y-m-d');
        }

        $meses = $periodo === '30' ? 3 : ($periodo === '90' ? 6 : 12);

        $dados = [
            'inicio'           => $inicio,
            'fim'              => $fim,
            'periodo'          => $periodo,
            'kpis'             => RelatoriosService::kpis($inicio, $fim),
            'fat_mensal'       => RelatoriosService::faturamentoMensal($meses),
            'dem_mensais'      => RelatoriosService::demandasMensais($meses),
            'dem_status'       => RelatoriosService::demandasPorStatusAgrupado($inicio, $fim),
            'maiores_parceiros'=> RelatoriosService::maioresParceiros(10, $inicio, $fim),
            'comissoes_mens'   => RelatoriosService::comissoesMensais($meses),
        ];

        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/relatorios.php', $dados);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.relatorios'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.relatorios')]],
        ]));
    }

    public function gerar(Requisicao $req): Resposta
    {
        $tipo = $req->param('tipo', 'demandas');
        $inicio = $req->get('inicio', date('Y-m-01'));
        $fim = $req->get('fim', date('Y-m-d'));
        $resultados = match ($tipo) {
            'demandas' => RelatoriosService::demandasPorPeriodo($inicio, $fim),
            'oportunidades' => RelatoriosService::oportunidadesPorEtapa(),
            'parceiros' => RelatoriosService::parceirosAtivos(),
            'comissoes' => RelatoriosService::comissoesPorPeriodo($inicio, $fim),
            default => [],
        };
        $metricas = [
            'ticket_medio' => RelatoriosService::ticketMedio(),
            'tempo_medio_proposta' => RelatoriosService::tempoMedioAteProposta(),
        ];
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/relatorios-gerar.php', [
            'tipo' => $tipo, 'resultados' => $resultados, 'metricas' => $metricas, 'inicio' => $inicio, 'fim' => $fim,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.relatorios'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.relatorios'), 'url' => '/equipe/relatorios'], ['label' => ucfirst($tipo)]],
        ]));
    }
}
