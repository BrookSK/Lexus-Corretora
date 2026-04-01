<?php
declare(strict_types=1);
namespace LEX\App\Services\Propostas;

use LEX\Core\BancoDeDados;
use PDO;

final class PropostasService
{
    public static function listar(int $page = 1, int $perPage = 20, array $filtros = []): array
    {
        $pdo = BancoDeDados::obter();
        $where = ['1=1'];
        $params = [];

        if (!empty($filtros['status'])) {
            $where[] = 'pr.status = :status';
            $params['status'] = $filtros['status'];
        }
        if (!empty($filtros['demanda_id'])) {
            $where[] = 'pr.demanda_id = :demanda_id';
            $params['demanda_id'] = (int)$filtros['demanda_id'];
        }
        if (!empty($filtros['parceiro_id'])) {
            $where[] = 'pr.parceiro_id = :parceiro_id';
            $params['parceiro_id'] = (int)$filtros['parceiro_id'];
        }
        if (isset($filtros['is_shortlisted'])) {
            $where[] = 'pr.is_shortlisted = :is_shortlisted';
            $params['is_shortlisted'] = (int)$filtros['is_shortlisted'];
        }
        if (!empty($filtros['category'])) {
            $where[] = 'd.category = :category';
            $params['category'] = $filtros['category'];
        }
        if (!empty($filtros['state'])) {
            $where[] = 'd.state = :state';
            $params['state'] = $filtros['state'];
        }
        if (!empty($filtros['city'])) {
            $where[] = 'd.city LIKE :city';
            $params['city'] = '%' . $filtros['city'] . '%';
        }
        if (!empty($filtros['date_from'])) {
            $where[] = 'DATE(pr.created_at) >= :date_from';
            $params['date_from'] = $filtros['date_from'];
        }
        if (!empty($filtros['date_to'])) {
            $where[] = 'DATE(pr.created_at) <= :date_to';
            $params['date_to'] = $filtros['date_to'];
        }

        $whereSql = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $countStmt = $pdo->prepare(
            "SELECT COUNT(*) FROM propostas pr
             JOIN demandas d ON d.id = pr.demanda_id
             WHERE {$whereSql}"
        );
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT pr.id, pr.demanda_id, pr.parceiro_id, pr.amount, pr.currency_code,
                    pr.deadline_days, pr.status, pr.is_shortlisted, pr.is_recommended,
                    pr.internal_score, pr.created_at,
                    d.code AS demanda_code, d.title AS demanda_title,
                    d.category AS demanda_category, d.state AS demanda_state,
                    d.city AS demanda_city,
                    p.name AS parceiro_nome,
                    c.name AS cliente_nome
             FROM propostas pr
             JOIN demandas d ON d.id = pr.demanda_id
             JOIN parceiros p ON p.id = pr.parceiro_id
             LEFT JOIN clientes c ON c.id = d.cliente_id
             WHERE {$whereSql}
             ORDER BY pr.created_at DESC
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

    public static function listarPorDemanda(int $demandaId): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT pr.*, p.name AS parceiro_nome, p.type AS parceiro_type,
                    p.score AS parceiro_score, p.is_vetriks AS parceiro_vetriks
             FROM propostas pr
             JOIN parceiros p ON p.id = pr.parceiro_id
             WHERE pr.demanda_id = :demanda_id
             ORDER BY pr.is_shortlisted DESC, pr.internal_score DESC, pr.created_at ASC"
        );
        $stmt->execute(['demanda_id' => $demandaId]);
        return $stmt->fetchAll();
    }

    public static function listarPorParceiro(int $parceiroId): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT pr.*, d.code AS demanda_code, d.title AS demanda_title, d.status AS demanda_status
             FROM propostas pr
             JOIN demandas d ON d.id = pr.demanda_id
             WHERE pr.parceiro_id = :parceiro_id
             ORDER BY pr.created_at DESC"
        );
        $stmt->execute(['parceiro_id' => $parceiroId]);
        return $stmt->fetchAll();
    }

    public static function obterPorId(int $id): ?array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT pr.*, d.code AS demanda_code, d.title AS demanda_title,
                    p.name AS parceiro_nome, p.email AS parceiro_email,
                    p.type AS parceiro_type, p.score AS parceiro_score
             FROM propostas pr
             JOIN demandas d ON d.id = pr.demanda_id
             JOIN parceiros p ON p.id = pr.parceiro_id
             WHERE pr.id = :id"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function criar(array $dados): int
    {
        $pdo = BancoDeDados::obter();

        foreach (['demanda_id', 'parceiro_id'] as $fk) {
            if (array_key_exists($fk, $dados) && $dados[$fk] === '') {
                $dados[$fk] = null;
            }
        }
        foreach (['deadline_days', 'validity_days', 'internal_score', 'amount'] as $num) {
            if (array_key_exists($num, $dados) && $dados[$num] === '') {
                $dados[$num] = null;
            }
        }
        foreach (['deadline_date', 'valid_until'] as $dt) {
            if (array_key_exists($dt, $dados) && $dados[$dt] === '') {
                $dados[$dt] = null;
            }
        }
        $campos = ['demanda_id', 'parceiro_id', 'amount', 'currency_code', 'deadline_days',
                    'deadline_date', 'description', 'differentials', 'conditions',
                    'validity_days', 'valid_until', 'status', 'internal_score', 'internal_notes'];
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

        $stmt = $pdo->prepare("INSERT INTO propostas ({$cols}) VALUES ({$placeholders})");
        $stmt->execute($params);

        return (int)$pdo->lastInsertId();
    }

    public static function alterarStatus(int $id, string $status): bool
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("UPDATE propostas SET status = :status WHERE id = :id");
        $stmt->execute(['id' => $id, 'status' => $status]);

        if ($status === 'selecionada') {
            $existing = $pdo->prepare("SELECT id FROM contratos WHERE proposta_id = :pid LIMIT 1");
            $existing->execute(['pid' => $id]);
            if (!$existing->fetch()) {
                $prop = $pdo->prepare(
                    "SELECT pr.id, pr.demanda_id, pr.parceiro_id, pr.amount, pr.currency_code,
                            d.cliente_id
                     FROM propostas pr
                     JOIN demandas d ON d.id = pr.demanda_id
                     WHERE pr.id = :id"
                );
                $prop->execute(['id' => $id]);
                $p = $prop->fetch();
                if ($p) {
                    $pdo->prepare(
                        "INSERT INTO contratos (demanda_id, proposta_id, cliente_id, parceiro_id, amount, currency_code, status)
                         VALUES (:demanda_id, :proposta_id, :cliente_id, :parceiro_id, :amount, :currency_code, 'em_formalizacao')"
                    )->execute([
                        'demanda_id'    => $p['demanda_id'],
                        'proposta_id'   => $p['id'],
                        'cliente_id'    => $p['cliente_id'],
                        'parceiro_id'   => $p['parceiro_id'],
                        'amount'        => $p['amount'],
                        'currency_code' => $p['currency_code'] ?? 'BRL',
                    ]);
                }
            }
        }

        return $stmt->rowCount() > 0;
    }

    public static function marcarShortlist(int $id, bool $shortlist): bool
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "UPDATE propostas SET is_shortlisted = :shortlist WHERE id = :id"
        );
        $stmt->execute(['id' => $id, 'shortlist' => $shortlist ? 1 : 0]);
        return $stmt->rowCount() > 0;
    }

    public static function compararPorDemanda(int $demandaId): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT pr.id, pr.amount, pr.currency_code, pr.deadline_days, pr.deadline_date,
                    pr.description, pr.differentials, pr.conditions, pr.validity_days,
                    pr.status, pr.is_shortlisted, pr.is_recommended, pr.internal_score,
                    pr.created_at,
                    p.id AS parceiro_id, p.name AS parceiro_nome, p.type AS parceiro_type,
                    p.score AS parceiro_score, p.is_vetriks AS parceiro_vetriks,
                    p.response_rate AS parceiro_response_rate, p.close_rate AS parceiro_close_rate,
                    ep.nome_fantasia AS empresa_nome
             FROM propostas pr
             JOIN parceiros p ON p.id = pr.parceiro_id
             LEFT JOIN empresas_parceiras ep ON ep.id = p.empresa_id
             WHERE pr.demanda_id = :demanda_id
               AND pr.status NOT IN ('rascunho', 'descartada', 'perdida')
             ORDER BY pr.is_recommended DESC, pr.is_shortlisted DESC, pr.internal_score DESC, pr.amount ASC"
        );
        $stmt->execute(['demanda_id' => $demandaId]);
        return $stmt->fetchAll();
    }
}
