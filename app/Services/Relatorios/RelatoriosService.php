<?php
declare(strict_types=1);
namespace LEX\App\Services\Relatorios;

use LEX\Core\BancoDeDados;
use PDO;

final class RelatoriosService
{
    public static function demandasPorPeriodo(string $inicio, string $fim): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT DATE(created_at) AS data, COUNT(*) AS total, status
             FROM demandas
             WHERE deleted_at IS NULL
               AND created_at >= :inicio
               AND created_at <= :fim
             GROUP BY DATE(created_at), status
             ORDER BY data ASC"
        );
        $stmt->execute(['inicio' => $inicio, 'fim' => $fim . ' 23:59:59']);
        return $stmt->fetchAll();
    }

    public static function oportunidadesPorEtapa(): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->query(
            "SELECT status, COUNT(*) AS total,
                    COALESCE(SUM(budget_max), 0) AS valor_total
             FROM demandas
             WHERE deleted_at IS NULL
             GROUP BY status
             ORDER BY FIELD(status,
                'novo','em_triagem','em_estruturacao','pronto_repasse','distribuido',
                'aguardando_respostas','recebendo_propostas','em_curadoria',
                'apresentado_cliente','em_negociacao','contrato_formalizacao',
                'fechado_ganho','fechado_perda','pausado','cancelado')"
        );
        return $stmt->fetchAll();
    }

    public static function parceirosAtivos(): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->query(
            "SELECT p.type, COUNT(*) AS total,
                    SUM(p.is_vetriks) AS vetriks,
                    ROUND(AVG(p.score), 1) AS score_medio
             FROM parceiros p
             WHERE p.deleted_at IS NULL AND p.is_active = 1
               AND p.status IN ('aprovado', 'vetriks_ativo')
             GROUP BY p.type
             ORDER BY total DESC"
        );
        return $stmt->fetchAll();
    }

    public static function comissoesPorPeriodo(string $inicio, string $fim): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT DATE(cm.created_at) AS data, cm.status,
                    COUNT(*) AS quantidade,
                    SUM(cm.commission_amount) AS total
             FROM comissoes cm
             WHERE cm.created_at >= :inicio
               AND cm.created_at <= :fim
             GROUP BY DATE(cm.created_at), cm.status
             ORDER BY data ASC"
        );
        $stmt->execute(['inicio' => $inicio, 'fim' => $fim . ' 23:59:59']);
        return $stmt->fetchAll();
    }

    public static function ticketMedio(): float
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->query(
            "SELECT COALESCE(AVG(ct.amount), 0) AS ticket_medio
             FROM contratos ct
             WHERE ct.status IN ('formalizado', 'pendente_confirmacao')"
        );
        return (float)$stmt->fetchColumn();
    }

    public static function tempoMedioAteProposta(): float
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->query(
            "SELECT COALESCE(AVG(DATEDIFF(pr.created_at, d.created_at)), 0) AS dias_medio
             FROM propostas pr
             JOIN demandas d ON d.id = pr.demanda_id
             WHERE pr.id IN (
                 SELECT MIN(p2.id) FROM propostas p2 GROUP BY p2.demanda_id
             )"
        );
        return (float)$stmt->fetchColumn();
    }

    public static function kpis(string $inicio, string $fim): array
    {
        $pdo = BancoDeDados::obter();
        $fim23 = $fim . ' 23:59:59';

        $d = $pdo->prepare("SELECT COUNT(*) FROM demandas WHERE deleted_at IS NULL AND created_at BETWEEN :i AND :f");
        $d->execute(['i' => $inicio, 'f' => $fim23]);
        $totalDemandas = (int)$d->fetchColumn();

        $c = $pdo->prepare("SELECT COUNT(*), COALESCE(SUM(amount),0) FROM contratos WHERE status IN ('formalizado','pendente_confirmacao') AND created_at BETWEEN :i AND :f");
        $c->execute(['i' => $inicio, 'f' => $fim23]);
        [$totalContratos, $faturamento] = $c->fetch(PDO::FETCH_NUM);
        $totalContratos = (int)$totalContratos;
        $faturamento    = (float)$faturamento;

        $cm = $pdo->prepare("SELECT COALESCE(SUM(commission_amount),0) FROM comissoes WHERE status NOT IN ('cancelada') AND created_at BETWEEN :i AND :f");
        $cm->execute(['i' => $inicio, 'f' => $fim23]);
        $comissoes = (float)$cm->fetchColumn();

        $lc = $pdo->prepare("SELECT COUNT(*) FROM leads WHERE created_at BETWEEN :i AND :f");
        $lc->execute(['i' => $inicio, 'f' => $fim23]);
        $totalLeads = (int)$lc->fetchColumn();

        return [
            'total_demandas'  => $totalDemandas,
            'total_contratos' => $totalContratos,
            'faturamento'     => $faturamento,
            'ticket_medio'    => $totalContratos > 0 ? $faturamento / $totalContratos : 0.0,
            'comissoes'       => $comissoes,
            'total_leads'     => $totalLeads,
        ];
    }

    public static function faturamentoMensal(int $meses = 12): array
    {
        $pdo  = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT DATE_FORMAT(created_at,'%Y-%m') AS mes,
                    COUNT(*) AS contratos,
                    COALESCE(SUM(amount),0) AS faturamento,
                    COALESCE(AVG(amount),0) AS ticket_medio
             FROM contratos
             WHERE status IN ('formalizado','pendente_confirmacao')
               AND created_at >= DATE_SUB(NOW(), INTERVAL :m MONTH)
             GROUP BY mes ORDER BY mes ASC"
        );
        $stmt->bindValue('m', $meses, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function maioresParceiros(int $limite = 10, string $inicio = '', string $fim = ''): array
    {
        $pdo    = BancoDeDados::obter();
        $extra  = '';
        $params = [];
        if ($inicio && $fim) {
            $extra  = " AND ct.created_at BETWEEN :i AND :f";
            $params = ['i' => $inicio, 'f' => $fim . ' 23:59:59'];
        }
        $stmt = $pdo->prepare(
            "SELECT p.name AS parceiro, p.type,
                    COUNT(ct.id) AS total_contratos,
                    COALESCE(SUM(ct.amount),0) AS faturamento_total,
                    COALESCE(AVG(ct.amount),0) AS ticket_medio
             FROM contratos ct
             JOIN parceiros p ON p.id = ct.parceiro_id
             WHERE ct.status IN ('formalizado','pendente_confirmacao'){$extra}
             GROUP BY ct.parceiro_id
             ORDER BY total_contratos DESC
             LIMIT :lim"
        );
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue('lim', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function demandasPorStatusAgrupado(string $inicio, string $fim): array
    {
        $pdo  = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT status, COUNT(*) AS total
             FROM demandas
             WHERE deleted_at IS NULL AND created_at BETWEEN :i AND :f
             GROUP BY status ORDER BY total DESC"
        );
        $stmt->execute(['i' => $inicio, 'f' => $fim . ' 23:59:59']);
        return $stmt->fetchAll();
    }

    public static function demandasMensais(int $meses = 12): array
    {
        $pdo  = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT DATE_FORMAT(created_at,'%Y-%m') AS mes, COUNT(*) AS total
             FROM demandas
             WHERE deleted_at IS NULL
               AND created_at >= DATE_SUB(NOW(), INTERVAL :m MONTH)
             GROUP BY mes ORDER BY mes ASC"
        );
        $stmt->bindValue('m', $meses, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function comissoesMensais(int $meses = 12): array
    {
        $pdo  = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT DATE_FORMAT(created_at,'%Y-%m') AS mes,
                    COALESCE(SUM(commission_amount),0) AS total
             FROM comissoes
             WHERE status NOT IN ('cancelada')
               AND created_at >= DATE_SUB(NOW(), INTERVAL :m MONTH)
             GROUP BY mes ORDER BY mes ASC"
        );
        $stmt->bindValue('m', $meses, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
