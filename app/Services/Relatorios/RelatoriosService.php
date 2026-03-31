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
}
