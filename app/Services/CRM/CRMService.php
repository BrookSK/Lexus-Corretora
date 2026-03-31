<?php
declare(strict_types=1);
namespace LEX\App\Services\CRM;

use LEX\Core\BancoDeDados;
use PDO;

final class CRMService
{
    public static function listarLeads(int $page = 1, int $perPage = 20, array $filtros = []): array
    {
        $pdo = BancoDeDados::obter();
        $where = ['1=1'];
        $params = [];

        if (!empty($filtros['busca'])) {
            $where[] = '(l.name LIKE :busca OR l.email LIKE :busca2 OR l.company LIKE :busca3)';
            $params['busca'] = '%' . $filtros['busca'] . '%';
            $params['busca2'] = '%' . $filtros['busca'] . '%';
            $params['busca3'] = '%' . $filtros['busca'] . '%';
        }
        if (!empty($filtros['status'])) {
            $where[] = 'l.status = :status';
            $params['status'] = $filtros['status'];
        }
        if (!empty($filtros['origin'])) {
            $where[] = 'l.origin = :origin';
            $params['origin'] = $filtros['origin'];
        }
        if (!empty($filtros['assigned_to'])) {
            $where[] = 'l.assigned_to = :assigned_to';
            $params['assigned_to'] = (int)$filtros['assigned_to'];
        }

        $whereSql = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM leads l WHERE {$whereSql}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT l.id, l.name, l.email, l.phone, l.company, l.origin,
                    l.status, l.assigned_to, l.created_at,
                    u.name AS responsavel_nome
             FROM leads l
             LEFT JOIN users u ON u.id = l.assigned_to
             WHERE {$whereSql}
             ORDER BY l.created_at DESC
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

    public static function criarLead(array $dados): int
    {
        $pdo = BancoDeDados::obter();
        $campos = ['name', 'email', 'phone', 'company', 'origin', 'assigned_to', 'status', 'notes'];
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

        $stmt = $pdo->prepare("INSERT INTO leads ({$cols}) VALUES ({$placeholders})");
        $stmt->execute($params);

        return (int)$pdo->lastInsertId();
    }

    public static function obterLead(int $id): ?array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT l.*, u.name AS responsavel_nome
             FROM leads l
             LEFT JOIN users u ON u.id = l.assigned_to
             WHERE l.id = :id"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function atualizarLead(int $id, array $dados): bool
    {
        $pdo = BancoDeDados::obter();
        $campos = ['name', 'email', 'phone', 'company', 'origin', 'assigned_to', 'status', 'notes'];
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
        $stmt = $pdo->prepare("UPDATE leads SET {$setSql} WHERE id = :id");
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public static function converterParaCliente(int $leadId): int
    {
        $pdo = BancoDeDados::obter();

        $lead = self::obterLead($leadId);
        if (!$lead) {
            throw new \RuntimeException("Lead #{$leadId} não encontrado.");
        }

        $senha = password_hash(bin2hex(random_bytes(8)), PASSWORD_BCRYPT, ['cost' => 12]);

        $stmt = $pdo->prepare(
            "INSERT INTO clientes (name, email, phone, company, password)
             VALUES (:name, :email, :phone, :company, :password)"
        );
        $stmt->execute([
            'name' => $lead['name'],
            'email' => $lead['email'] ?? '',
            'phone' => $lead['phone'] ?? null,
            'company' => $lead['company'] ?? null,
            'password' => $senha,
        ]);
        $clienteId = (int)$pdo->lastInsertId();

        $pdo->prepare(
            "UPDATE leads SET status = 'convertido', converted_to_cliente_id = :cliente_id WHERE id = :id"
        )->execute(['cliente_id' => $clienteId, 'id' => $leadId]);

        return $clienteId;
    }
}
