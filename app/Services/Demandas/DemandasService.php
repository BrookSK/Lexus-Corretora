<?php
declare(strict_types=1);
namespace LEX\App\Services\Demandas;

use LEX\Core\BancoDeDados;
use PDO;

final class DemandasService
{
    public static function listar(int $page = 1, int $perPage = 20, array $filtros = []): array
    {
        $pdo = BancoDeDados::obter();
        $where = ['d.deleted_at IS NULL'];
        $params = [];

        if (!empty($filtros['busca'])) {
            $where[] = '(d.title LIKE :busca OR d.code LIKE :busca2)';
            $params['busca'] = '%' . $filtros['busca'] . '%';
            $params['busca2'] = '%' . $filtros['busca'] . '%';
        }
        if (!empty($filtros['status'])) {
            $where[] = 'd.status = :status';
            $params['status'] = $filtros['status'];
        }
        if (!empty($filtros['cliente_id'])) {
            $where[] = 'd.cliente_id = :cliente_id';
            $params['cliente_id'] = (int)$filtros['cliente_id'];
        }
        if (!empty($filtros['assigned_to'])) {
            $where[] = 'd.assigned_to = :assigned_to';
            $params['assigned_to'] = (int)$filtros['assigned_to'];
        }
        if (!empty($filtros['urgency'])) {
            $where[] = 'd.urgency = :urgency';
            $params['urgency'] = $filtros['urgency'];
        }
        if (!empty($filtros['city'])) {
            $where[] = 'd.city = :city';
            $params['city'] = $filtros['city'];
        }
        if (!empty($filtros['state'])) {
            $where[] = 'd.state = :state';
            $params['state'] = $filtros['state'];
        }
        if (!empty($filtros['origin'])) {
            $where[] = 'd.origin = :origin';
            $params['origin'] = $filtros['origin'];
        }
        if (!empty($filtros['data_inicio'])) {
            $where[] = 'd.created_at >= :data_inicio';
            $params['data_inicio'] = $filtros['data_inicio'];
        }
        if (!empty($filtros['data_fim'])) {
            $where[] = 'd.created_at <= :data_fim';
            $params['data_fim'] = $filtros['data_fim'] . ' 23:59:59';
        }

        $whereSql = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM demandas d WHERE {$whereSql}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT d.id, d.code, d.title, d.status, d.urgency, d.priority,
                    d.city, d.state, d.budget_min, d.budget_max, d.origin,
                    d.created_at, d.cliente_id, d.assigned_to,
                    c.name AS cliente_nome, u.name AS responsavel_nome
             FROM demandas d
             LEFT JOIN clientes c ON c.id = d.cliente_id
             LEFT JOIN users u ON u.id = d.assigned_to
             WHERE {$whereSql}
             ORDER BY d.created_at DESC
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

    public static function listarPorCliente(int $clienteId): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT id, code, title, status, urgency, priority, created_at
             FROM demandas
             WHERE cliente_id = :cliente_id AND deleted_at IS NULL
             ORDER BY created_at DESC"
        );
        $stmt->execute(['cliente_id' => $clienteId]);
        return $stmt->fetchAll();
    }

    public static function obterPorId(int $id): ?array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT d.*, c.name AS cliente_nome, c.email AS cliente_email,
                    u.name AS responsavel_nome
             FROM demandas d
             LEFT JOIN clientes c ON c.id = d.cliente_id
             LEFT JOIN users u ON u.id = d.assigned_to
             WHERE d.id = :id AND d.deleted_at IS NULL"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function criar(array $dados): int
    {
        $pdo = BancoDeDados::obter();
        $dados['code'] = self::gerarCodigo();

        // Campos FK: converter string vazia para null
        foreach (['cliente_id', 'parceiro_originador_id', 'assigned_to'] as $fk) {
            if (array_key_exists($fk, $dados) && $dados[$fk] === '') {
                $dados[$fk] = null;
            }
        }
        // Campos numéricos opcionais: converter string vazia para null
        foreach (['area_sqm', 'budget_min', 'budget_max'] as $num) {
            if (array_key_exists($num, $dados) && $dados[$num] === '') {
                $dados[$num] = null;
            }
        }
        // Campos de data: converter string vazia para null
        if (array_key_exists('desired_deadline', $dados) && $dados['desired_deadline'] === '') {
            $dados['desired_deadline'] = null;
        }

        $campos = ['code', 'origin', 'cliente_id', 'parceiro_originador_id', 'assigned_to',
                    'title', 'description', 'category', 'subcategory', 'work_type',
                    'city', 'state', 'country', 'address', 'area_sqm', 'current_phase',
                    'desired_deadline', 'budget_min', 'budget_max', 'currency_code',
                    'urgency', 'complexity', 'has_project', 'has_architect',
                    'wants_multiple_proposals', 'hiring_type', 'notes', 'internal_notes',
                    'status', 'priority', 'ideal_partner_profile'];
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

        $stmt = $pdo->prepare("INSERT INTO demandas ({$cols}) VALUES ({$placeholders})");
        $stmt->execute($params);

        return (int)$pdo->lastInsertId();
    }

    public static function atualizar(int $id, array $dados): bool
    {
        $pdo = BancoDeDados::obter();

        // Campos FK: converter string vazia para null
        foreach (['cliente_id', 'parceiro_originador_id', 'assigned_to'] as $fk) {
            if (array_key_exists($fk, $dados) && $dados[$fk] === '') {
                $dados[$fk] = null;
            }
        }
        foreach (['area_sqm', 'budget_min', 'budget_max'] as $num) {
            if (array_key_exists($num, $dados) && $dados[$num] === '') {
                $dados[$num] = null;
            }
        }
        if (array_key_exists('desired_deadline', $dados) && $dados['desired_deadline'] === '') {
            $dados['desired_deadline'] = null;
        }
        $campos = ['origin', 'cliente_id', 'parceiro_originador_id', 'assigned_to',
                    'title', 'description', 'category', 'subcategory', 'work_type',
                    'city', 'state', 'country', 'address', 'area_sqm', 'current_phase',
                    'desired_deadline', 'budget_min', 'budget_max', 'currency_code',
                    'urgency', 'complexity', 'has_project', 'has_architect',
                    'wants_multiple_proposals', 'hiring_type', 'notes', 'internal_notes',
                    'priority', 'score', 'ideal_partner_profile'];
        $set = [];
        $params = ['id' => $id];

        foreach ($campos as $campo) {
            if (array_key_exists($campo, $dados)) {
                $set[] = "`{$campo}` = :{$campo}";
                $params[$campo] = $dados[$campo];
            }
        }
        if (empty($set)) {
            return false;
        }

        $setSql = implode(', ', $set);
        $stmt = $pdo->prepare("UPDATE demandas SET {$setSql} WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public static function alterarStatus(int $id, string $status): bool
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "UPDATE demandas SET status = :status WHERE id = :id AND deleted_at IS NULL"
        );
        $stmt->execute(['id' => $id, 'status' => $status]);
        return $stmt->rowCount() > 0;
    }

    public static function gerarCodigo(): string
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->query("SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM demandas");
        $nextId = (int)$stmt->fetchColumn();
        return 'LEX-' . str_pad((string)$nextId, 6, '0', STR_PAD_LEFT);
    }

    public static function contarPorStatus(): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->query(
            "SELECT status, COUNT(*) AS total
             FROM demandas
             WHERE deleted_at IS NULL
             GROUP BY status
             ORDER BY total DESC"
        );
        return $stmt->fetchAll();
    }
}
