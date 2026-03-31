<?php
declare(strict_types=1);
namespace LEX\Core;

final class Rbac
{
    private static ?array $permissoesCache = null;

    public static function temPermissao(int $userId, string $permissao): bool
    {
        $permissoes = self::obterPermissoes($userId);
        return in_array($permissao, $permissoes, true) || in_array('*', $permissoes, true);
    }

    public static function obterPermissoes(int $userId): array
    {
        if (self::$permissoesCache !== null && isset(self::$permissoesCache[$userId])) {
            return self::$permissoesCache[$userId];
        }
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("
            SELECT DISTINCT p.slug
            FROM permissions p
            INNER JOIN role_permissions rp ON rp.permission_id = p.id
            INNER JOIN user_roles ur ON ur.role_id = rp.role_id
            WHERE ur.user_id = :uid
        ");
        $stmt->execute(['uid' => $userId]);
        $perms = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        self::$permissoesCache[$userId] = $perms;
        return $perms;
    }

    public static function obterRoles(int $userId): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("
            SELECT r.slug FROM roles r
            INNER JOIN user_roles ur ON ur.role_id = r.id
            WHERE ur.user_id = :uid
        ");
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public static function temRole(int $userId, string $role): bool
    {
        return in_array($role, self::obterRoles($userId), true);
    }

    public static function resetar(): void
    {
        self::$permissoesCache = null;
    }
}
