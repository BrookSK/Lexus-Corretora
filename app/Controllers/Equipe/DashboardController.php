<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, BancoDeDados};

final class DashboardController
{
    public function index(Requisicao $req): Resposta
    {
        $pdo = BancoDeDados::obter();
        $metricas = [
            'novas_demandas'      => (int)$pdo->query("SELECT COUNT(*) FROM demandas WHERE status = 'novo'")->fetchColumn(),
            'oportunidades_ativas'=> (int)$pdo->query("SELECT COUNT(*) FROM demandas WHERE status NOT IN ('fechado_ganho','fechado_perda','cancelado')")->fetchColumn(),
            'propostas_andamento' => (int)$pdo->query("SELECT COUNT(*) FROM propostas WHERE status IN ('enviada','em_analise','shortlist')")->fetchColumn(),
            'fechamentos'         => (int)$pdo->query("SELECT COUNT(*) FROM demandas WHERE status = 'fechado_ganho'")->fetchColumn(),
            'parceiros_ativos'    => (int)$pdo->query("SELECT COUNT(*) FROM parceiros WHERE is_active = 1 AND deleted_at IS NULL")->fetchColumn(),
            'clientes_ativos'     => (int)$pdo->query("SELECT COUNT(*) FROM clientes WHERE is_active = 1 AND deleted_at IS NULL")->fetchColumn(),
            'comissoes_previstas' => (float)$pdo->query("SELECT COALESCE(SUM(commission_amount),0) FROM comissoes WHERE status = 'prevista'")->fetchColumn(),
            'comissoes_recebidas' => (float)$pdo->query("SELECT COALESCE(SUM(commission_amount),0) FROM comissoes WHERE status = 'recebida'")->fetchColumn(),
        ];

        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/dashboard.php', [
            'metricas' => $metricas,
        ]);
        $html = View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo'   => $conteudo,
            'painelTipo' => 'equipe',
            'pageTitle'  => I18n::t('sidebar.dashboard'),
            'breadcrumbs'=> [['label' => I18n::t('sidebar.dashboard')]],
        ]);
        return Resposta::html($html);
    }
}
