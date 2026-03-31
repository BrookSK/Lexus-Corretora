<?php
declare(strict_types=1);
namespace LEX\App\Services\Notificacoes;

use LEX\Core\BancoDeDados;
use PDO;

final class NotificacoesService
{
    public static function criar(string $recipientType, int $recipientId, string $type, string $title, ?string $body = null, ?string $link = null): int
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "INSERT INTO notificacoes (recipient_type, recipient_id, type, title, body, link)
             VALUES (:recipient_type, :recipient_id, :type, :title, :body, :link)"
        );
        $stmt->execute([
            'recipient_type' => $recipientType,
            'recipient_id' => $recipientId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'link' => $link,
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function listarPorDestinatario(string $type, int $id, int $limit = 20): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT id, type, title, body, link, is_read, read_at, created_at
             FROM notificacoes
             WHERE recipient_type = :type AND recipient_id = :id
             ORDER BY created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue('type', $type);
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function marcarLida(int $id): bool
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "UPDATE notificacoes SET is_read = 1, read_at = NOW() WHERE id = :id AND is_read = 0"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public static function contarNaoLidas(string $type, int $id): int
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM notificacoes
             WHERE recipient_type = :type AND recipient_id = :id AND is_read = 0"
        );
        $stmt->execute(['type' => $type, 'id' => $id]);
        return (int)$stmt->fetchColumn();
    }
}
