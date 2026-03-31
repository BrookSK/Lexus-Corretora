<?php
declare(strict_types=1);
namespace LEX\App\Services\Clientes;

use LEX\Core\BancoDeDados;
use PDO;

final class ClientesService
{
    public static function listar(int $page = 1, int $perPage = 20, array $filtros = []): array
    {
        $pdo = BancoDeDados::obter();
        $where = ['c.deleted_at IS NULL'];
        $params = [];

        if (!empty($filtros['busca'])) {
            $where[] = '(c.name LIKE :busca OR c.email LIKE :busca2 OR c.company LIKE :busca3)';
            $params['busca'] = '%' . $filtros['busca'] . '%';
            $params['busca2'] = '%' . $filtros['busca'] . '%';
            $params['busca3'] = '%' . $filtros['busca'] . '%';
        }
        if (isset($filtros['is_active'])) {
            $where[] = 'c.is_active = :is_active';
            $params['is_active'] = (int)$filtros['is_active'];
        }
        if (!empty($filtros['city'])) {
            $where[] = 'c.city = :city';
            $params['city'] = $filtros['city'];
        }
        if (!empty($filtros['state'])) {
            $where[] = 'c.state = :state';
            $params['state'] = $filtros['state'];
        }

        $whereSql = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM clientes c WHERE {$whereSql}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT c.id, c.name, c.email, c.phone, c.whatsapp, c.company, c.document,
                    c.city, c.state, c.is_active, c.last_login_at, c.created_at
             FROM clientes c
             WHERE {$whereSql}
             ORDER BY c.created_at DESC
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
            "SELECT * FROM clientes WHERE id = :id AND deleted_at IS NULL"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function criar(array $dados): int
    {
        $pdo = BancoDeDados::obter();
        $dados['password'] = password_hash($dados['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        $campos = ['name', 'email', 'password', 'phone', 'whatsapp', 'company',
                    'document', 'city', 'state', 'country', 'address', 'language', 'currency'];
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

        $stmt = $pdo->prepare("INSERT INTO clientes ({$cols}) VALUES ({$placeholders})");
        $stmt->execute($params);

        return (int)$pdo->lastInsertId();
    }

    public static function atualizar(int $id, array $dados): bool
    {
        $pdo = BancoDeDados::obter();
        $campos = ['name', 'email', 'phone', 'whatsapp', 'company', 'document',
                    'avatar', 'city', 'state', 'country', 'address', 'language', 'currency'];
        $set = [];
        $params = ['id' => $id];

        foreach ($campos as $campo) {
            if (array_key_exists($campo, $dados)) {
                $set[] = "`{$campo}` = :{$campo}";
                $params[$campo] = $dados[$campo];
            }
        }
        if (!empty($dados['password'])) {
            $set[] = '`password` = :password';
            $params['password'] = password_hash($dados['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }
        if (empty($set)) {
            return false;
        }

        $setSql = implode(', ', $set);
        $stmt = $pdo->prepare("UPDATE clientes SET {$setSql} WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public static function excluir(int $id): bool
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("UPDATE clientes SET deleted_at = NOW() WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public static function ativar(int $id): bool
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("UPDATE clientes SET is_active = 1 WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public static function desativar(int $id): bool
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("UPDATE clientes SET is_active = 0 WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
