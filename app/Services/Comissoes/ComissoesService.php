<?php
declare(strict_types=1);
namespace LEX\App\Services\Comissoes;

use LEX\Core\BancoDeDados;
use PDO;

final class ComissoesService
{
    public static function listar(int $page = 1, int $perPage = 20, array $filtros = []): array
    {
        $pdo = BancoDeDados::obter();
        $where = ['1=1'];
        $params = [];

        if (!empty($filtros['status'])) {
            $where[] = 'cm.status = :status';
            $params['status'] = $filtros['status'];
        }
        if (!empty($filtros['parceiro_id'])) {
            $where[] = 'cm.parceiro_id = :parceiro_id';
            $params['parceiro_id'] = (int)$filtros['parceiro_id'];
        }
        if (!empty($filtros['demanda_id'])) {
            $where[] = 'cm.demanda_id = :demanda_id';
            $params['demanda_id'] = (int)$filtros['demanda_id'];
        }
        if (!empty($filtros['data_inicio'])) {
            $where[] = 'cm.created_at >= :data_inicio';
            $params['data_inicio'] = $filtros['data_inicio'];
        }
        if (!empty($filtros['data_fim'])) {
            $where[] = 'cm.created_at <= :data_fim';
            $params['data_fim'] = $filtros['data_fim'] . ' 23:59:59';
        }

        $whereSql = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM comissoes cm WHERE {$whereSql}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT cm.id, cm.demanda_id, cm.contrato_id, cm.parceiro_id,
                    cm.base_amount, cm.commission_pct, cm.commission_amount,
                    cm.currency_code, cm.status, cm.expected_date, cm.received_date,
                    cm.created_at,
                    d.code AS demanda_code, d.title AS demanda_title,
                    p.name AS parceiro_nome
             FROM comissoes cm
             JOIN demandas d ON d.id = cm.demanda_id
             JOIN parceiros p ON p.id = cm.parceiro_id
             WHERE {$whereSql}
             ORDER BY cm.created_at DESC
             LIMIT :limit OFFSET :offset"
        );
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return ['items' => $stmt->fetchAll(), 'total' => $total];
    }

    public static function listarPorParceiro(int $parceiroId): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT cm.*, d.code AS demanda_code, d.title AS demanda_title
             FROM comissoes cm
             JOIN demandas d ON d.id = cm.demanda_id
             WHERE cm.parceiro_id = :parceiro_id
             ORDER BY cm.created_at DESC"
        );
        $stmt->execute(['parceiro_id' => $parceiroId]);
        return $stmt->fetchAll();
    }

    public static function obterPorId(int $id): ?array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT cm.*, d.code AS demanda_code, d.title AS demanda_title,
                    p.name AS parceiro_nome, p.email AS parceiro_email,
                    c.name AS cliente_nome
             FROM comissoes cm
             JOIN demandas d ON d.id = cm.demanda_id
             JOIN parceiros p ON p.id = cm.parceiro_id
             LEFT JOIN clientes c ON c.id = cm.cliente_id
             WHERE cm.id = :id"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function criar(array $dados): int
    {
        $pdo = BancoDeDados::obter();

        $baseAmount = (float)($dados['base_amount'] ?? 0);
        $commissionPct = (float)($dados['commission_pct'] ?? 0);
        $dados['commission_amount'] = round($baseAmount * $commissionPct / 100, 2);

        $campos = ['demanda_id', 'contrato_id', 'parceiro_id', 'cliente_id',
                    'base_amount', 'commission_pct', 'commission_amount', 'currency_code',
                    'status', 'expected_date', 'notes'];
        $insert = [];
        $params = [];
        foreach ($campos as $campo) {
            if (array_key_exists($campo, $dados)) {
                $insert[] = $campo;
                $params[$campo] = $dados[$campo];
            }
        }

        $cols = implode(', ', array_map(fn($c) => "`{$c}`", $insert));
        $placeholders = implode(', ', array_map(fn($c) => ":{$c}", $insert));

        $stmt = $pdo->prepare("INSERT INTO comissoes ({$cols}) VALUES ({$placeholders})");
        $stmt->execute($params);

        return (int)$pdo->lastInsertId();
    }

    public static function alterarStatus(int $id, string $status): bool
    {
        $pdo = BancoDeDados::obter();
        $extra = '';
        $params = ['id' => $id, 'status' => $status];

        if ($status === 'recebida') {
            $extra = ', received_date = CURDATE()';
        }

        $stmt = $pdo->prepare("UPDATE comissoes SET status = :status{$extra} WHERE id = :id");
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    public static function totalPorStatus(): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->query(
            "SELECT status, COUNT(*) AS quantidade, SUM(commission_amount) AS total
             FROM comissoes
             GROUP BY status
             ORDER BY total DESC"
        );
        return $stmt->fetchAll();
    }
}
