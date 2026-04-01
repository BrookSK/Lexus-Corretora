<?php
declare(strict_types=1);
namespace LEX\App\Services\Contratos;

use LEX\Core\BancoDeDados;
use PDO;

final class ContratosService
{
    public static function listar(int $page = 1, int $perPage = 20, array $filtros = []): array
    {
        $pdo = BancoDeDados::obter();
        $where = ['1=1'];
        $params = [];

        if (!empty($filtros['status'])) {
            $where[] = 'ct.status = :status';
            $params['status'] = $filtros['status'];
        }
        if (!empty($filtros['demanda_id'])) {
            $where[] = 'ct.demanda_id = :demanda_id';
            $params['demanda_id'] = (int)$filtros['demanda_id'];
        }
        if (!empty($filtros['cliente_id'])) {
            $where[] = 'ct.cliente_id = :cliente_id';
            $params['cliente_id'] = (int)$filtros['cliente_id'];
        }
        if (!empty($filtros['parceiro_id'])) {
            $where[] = 'ct.parceiro_id = :parceiro_id';
            $params['parceiro_id'] = (int)$filtros['parceiro_id'];
        }

        $whereSql = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM contratos ct WHERE {$whereSql}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT ct.id, ct.demanda_id, ct.proposta_id, ct.cliente_id, ct.parceiro_id,
                    ct.amount, ct.currency_code, ct.status, ct.formalized_at, ct.created_at,
                    d.code AS demanda_code, d.title AS demanda_title,
                    c.name AS cliente_nome, p.name AS parceiro_nome
             FROM contratos ct
             JOIN demandas d ON d.id = ct.demanda_id
             JOIN clientes c ON c.id = ct.cliente_id
             JOIN parceiros p ON p.id = ct.parceiro_id
             WHERE {$whereSql}
             ORDER BY ct.created_at DESC
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

    public static function obterPorId(int $id): ?array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT ct.*,
                    d.code AS demanda_code, d.title AS demanda_title,
                    d.description AS demanda_description, d.category AS demanda_category,
                    d.city AS demanda_city, d.state AS demanda_state,
                    d.budget_min AS demanda_budget_min, d.budget_max AS demanda_budget_max,
                    d.urgency AS demanda_urgency, d.status AS demanda_status,
                    c.name AS cliente_nome, c.email AS cliente_email,
                    c.phone AS cliente_phone, c.whatsapp AS cliente_whatsapp,
                    c.company AS cliente_company, c.document AS cliente_document,
                    p.name AS parceiro_nome, p.email AS parceiro_email,
                    p.phone AS parceiro_phone, p.whatsapp AS parceiro_whatsapp,
                    p.type AS parceiro_type, p.document AS parceiro_document,
                    pr.description AS proposta_descricao, pr.deadline_days AS proposta_prazo,
                    pr.conditions AS proposta_condicoes, pr.differentials AS proposta_diferenciais
             FROM contratos ct
             JOIN demandas d ON d.id = ct.demanda_id
             JOIN clientes c ON c.id = ct.cliente_id
             JOIN parceiros p ON p.id = ct.parceiro_id
             LEFT JOIN propostas pr ON pr.id = ct.proposta_id
             WHERE ct.id = :id"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function criar(array $dados): int
    {
        $pdo = BancoDeDados::obter();

        foreach (['demanda_id', 'proposta_id', 'cliente_id', 'parceiro_id'] as $fk) {
            if (array_key_exists($fk, $dados) && $dados[$fk] === '') {
                $dados[$fk] = null;
            }
        }
        if (array_key_exists('formalized_at', $dados) && $dados['formalized_at'] === '') {
            $dados['formalized_at'] = null;
        }
        $campos = ['demanda_id', 'proposta_id', 'cliente_id', 'parceiro_id',
                    'amount', 'currency_code', 'status', 'formalized_at',
                    'notes', 'internal_notes'];
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

        $stmt = $pdo->prepare("INSERT INTO contratos ({$cols}) VALUES ({$placeholders})");
        $stmt->execute($params);

        return (int)$pdo->lastInsertId();
    }

    public static function alterarStatus(int $id, string $status): bool
    {
        $pdo = BancoDeDados::obter();
        $extra = '';
        $params = ['id' => $id, 'status' => $status];

        if ($status === 'formalizado') {
            $extra = ', formalized_at = CURDATE()';
        }

        $stmt = $pdo->prepare("UPDATE contratos SET status = :status{$extra} WHERE id = :id");
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }
}
