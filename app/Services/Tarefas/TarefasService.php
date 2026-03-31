<?php
declare(strict_types=1);
namespace LEX\App\Services\Tarefas;

use LEX\Core\BancoDeDados;
use PDO;

final class TarefasService
{
    public static function listar(int $page = 1, int $perPage = 20, array $filtros = []): array
    {
        $pdo = BancoDeDados::obter();
        $where = ['1=1'];
        $params = [];

        if (!empty($filtros['status'])) {
            $where[] = 't.status = :status';
            $params['status'] = $filtros['status'];
        }
        if (!empty($filtros['assigned_to'])) {
            $where[] = 't.assigned_to = :assigned_to';
            $params['assigned_to'] = (int)$filtros['assigned_to'];
        }
        if (!empty($filtros['priority'])) {
            $where[] = 't.priority = :priority';
            $params['priority'] = $filtros['priority'];
        }
        if (!empty($filtros['related_type'])) {
            $where[] = 't.related_type = :related_type';
            $params['related_type'] = $filtros['related_type'];
        }
        if (!empty($filtros['related_id'])) {
            $where[] = 't.related_id = :related_id';
            $params['related_id'] = (int)$filtros['related_id'];
        }

        $whereSql = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM tarefas t WHERE {$whereSql}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT t.id, t.title, t.description, t.assigned_to, t.related_type,
                    t.related_id, t.priority, t.status, t.due_date, t.completed_at,
                    t.created_by, t.created_at,
                    u.name AS responsavel_nome, uc.name AS criador_nome
             FROM tarefas t
             LEFT JOIN users u ON u.id = t.assigned_to
             LEFT JOIN users uc ON uc.id = t.created_by
             WHERE {$whereSql}
             ORDER BY FIELD(t.priority, 'urgente', 'alta', 'normal', 'baixa'), t.due_date ASC
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

    public static function criar(array $dados): int
    {
        $pdo = BancoDeDados::obter();
        $campos = ['title', 'description', 'assigned_to', 'related_type', 'related_id',
                    'priority', 'status', 'due_date', 'created_by'];
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

        $stmt = $pdo->prepare("INSERT INTO tarefas ({$cols}) VALUES ({$placeholders})");
        $stmt->execute($params);

        return (int)$pdo->lastInsertId();
    }

    public static function alterarStatus(int $id, string $status): bool
    {
        $pdo = BancoDeDados::obter();
        $extra = '';
        $params = ['id' => $id, 'status' => $status];

        if ($status === 'concluida') {
            $extra = ', completed_at = NOW()';
        }

        $stmt = $pdo->prepare("UPDATE tarefas SET status = :status{$extra} WHERE id = :id");
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    public static function listarVencendo(int $dias = 3): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT t.id, t.title, t.priority, t.status, t.due_date, t.assigned_to,
                    u.name AS responsavel_nome
             FROM tarefas t
             LEFT JOIN users u ON u.id = t.assigned_to
             WHERE t.status IN ('pendente', 'em_andamento')
               AND t.due_date IS NOT NULL
               AND t.due_date <= DATE_ADD(NOW(), INTERVAL :dias DAY)
             ORDER BY t.due_date ASC"
        );
        $stmt->bindValue('dias', $dias, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
