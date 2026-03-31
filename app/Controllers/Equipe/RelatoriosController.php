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
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/relatorios.php', []);
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
