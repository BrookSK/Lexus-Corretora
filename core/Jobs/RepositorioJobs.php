<?php
declare(strict_types=1);
namespace LEX\Core\Jobs;

use LEX\Core\BancoDeDados;

final class RepositorioJobs
{
    public static function criar(string $tipo, array $payload, ?string $runAt = null): int
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("INSERT INTO jobs (type, payload, status, run_at, created_at) VALUES (:t, :p, 'pending', :r, NOW())");
        $stmt->execute([
            't' => $tipo,
            'p' => json_encode($payload, JSON_UNESCAPED_UNICODE),
            'r' => $runAt,
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function proximo(): ?array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("SELECT * FROM jobs WHERE status = 'pending' AND (run_at IS NULL OR run_at <= NOW()) ORDER BY created_at ASC LIMIT 1 FOR UPDATE SKIP LOCKED");
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    public static function marcarProcessando(int $id): void
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("UPDATE jobs SET status = 'processing', started_at = NOW() WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public static function marcarConcluido(int $id): void
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("UPDATE jobs SET status = 'completed', finished_at = NOW() WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public static function marcarFalha(int $id, string $erro): void
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("UPDATE jobs SET status = 'failed', error = :e, finished_at = NOW(), attempts = attempts + 1 WHERE id = :id");
        $stmt->execute(['id' => $id, 'e' => $erro]);
    }
}
