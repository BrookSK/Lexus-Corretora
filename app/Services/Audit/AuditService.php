<?php
declare(strict_types=1);
namespace LEX\App\Services\Audit;

use LEX\Core\BancoDeDados;

final class AuditService
{
    public static function registrar(string $actorType, ?int $actorId, string $action, ?string $entityType = null, ?int $entityId = null, ?array $payload = null): void
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "INSERT INTO audit_logs (actor_type, actor_id, action, entity_type, entity_id, payload, ip, user_agent)
             VALUES (:actor_type, :actor_id, :action, :entity_type, :entity_id, :payload, :ip, :user_agent)"
        );
        $stmt->execute([
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'payload' => $payload !== null ? json_encode($payload, JSON_UNESCAPED_UNICODE) : null,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);
    }
}
