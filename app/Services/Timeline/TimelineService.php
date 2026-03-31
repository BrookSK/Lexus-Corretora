<?php
declare(strict_types=1);
namespace LEX\App\Services\Timeline;

use LEX\Core\BancoDeDados;
use PDO;

final class TimelineService
{
    public static function registrar(int $demandaId, string $eventType, string $description, string $actorType = 'sistema', ?int $actorId = null, ?array $metadata = null): int
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "INSERT INTO oportunidade_timeline (demanda_id, event_type, description, actor_type, actor_id, metadata)
             VALUES (:demanda_id, :event_type, :description, :actor_type, :actor_id, :metadata)"
        );
        $stmt->execute([
            'demanda_id' => $demandaId,
            'event_type' => $eventType,
            'description' => $description,
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'metadata' => $metadata !== null ? json_encode($metadata, JSON_UNESCAPED_UNICODE) : null,
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function listarPorDemanda(int $demandaId): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT ot.id, ot.event_type, ot.description, ot.actor_type, ot.actor_id,
                    ot.metadata, ot.created_at
             FROM oportunidade_timeline ot
             WHERE ot.demanda_id = :demanda_id
             ORDER BY ot.created_at DESC"
        );
        $stmt->execute(['demanda_id' => $demandaId]);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            if (!empty($row['metadata']) && is_string($row['metadata'])) {
                $row['metadata'] = json_decode($row['metadata'], true);
            }
        }
        unset($row);

        return $rows;
    }
}
