<?php
declare(strict_types=1);
namespace LEX\App\Services\Mensagens;

use LEX\Core\BancoDeDados;
use PDO;

final class MensagensService
{
    public static function criarConversa(string $type, array $participantes, ?string $subject = null, ?int $demandaId = null): int
    {
        $pdo = BancoDeDados::obter();

        $stmt = $pdo->prepare(
            "INSERT INTO conversas (type, subject, demanda_id)
             VALUES (:type, :subject, :demanda_id)"
        );
        $stmt->execute([
            'type' => $type,
            'subject' => $subject,
            'demanda_id' => $demandaId,
        ]);
        $conversaId = (int)$pdo->lastInsertId();

        $stmtPart = $pdo->prepare(
            "INSERT INTO conversa_participantes (conversa_id, participant_type, participant_id)
             VALUES (:conversa_id, :participant_type, :participant_id)"
        );
        foreach ($participantes as $p) {
            $stmtPart->execute([
                'conversa_id' => $conversaId,
                'participant_type' => $p['type'],
                'participant_id' => (int)$p['id'],
            ]);
        }

        return $conversaId;
    }

    public static function enviarMensagem(int $conversaId, string $senderType, int $senderId, string $body): int
    {
        $pdo = BancoDeDados::obter();

        $stmt = $pdo->prepare(
            "INSERT INTO mensagens (conversa_id, sender_type, sender_id, body)
             VALUES (:conversa_id, :sender_type, :sender_id, :body)"
        );
        $stmt->execute([
            'conversa_id' => $conversaId,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'body' => $body,
        ]);

        $pdo->prepare("UPDATE conversas SET updated_at = NOW() WHERE id = :id")
            ->execute(['id' => $conversaId]);

        return (int)$pdo->lastInsertId();
    }

    public static function listarConversas(string $participantType, int $participantId): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT c.id, c.subject, c.type, c.status, c.demanda_id, c.updated_at,
                    d.code AS demanda_code,
                    (SELECT COUNT(*) FROM mensagens m WHERE m.conversa_id = c.id) AS total_mensagens,
                    (SELECT m2.body FROM mensagens m2 WHERE m2.conversa_id = c.id ORDER BY m2.created_at DESC LIMIT 1) AS ultima_mensagem
             FROM conversas c
             JOIN conversa_participantes cp ON cp.conversa_id = c.id
             LEFT JOIN demandas d ON d.id = c.demanda_id
             WHERE cp.participant_type = :ptype AND cp.participant_id = :pid
               AND c.status != 'arquivada'
             ORDER BY c.updated_at DESC"
        );
        $stmt->execute(['ptype' => $participantType, 'pid' => $participantId]);
        return $stmt->fetchAll();
    }

    public static function listarMensagens(int $conversaId): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT m.id, m.sender_type, m.sender_id, m.body, m.is_system, m.created_at
             FROM mensagens m
             WHERE m.conversa_id = :conversa_id
             ORDER BY m.created_at ASC"
        );
        $stmt->execute(['conversa_id' => $conversaId]);
        return $stmt->fetchAll();
    }

    public static function marcarLida(int $conversaId, string $participantType, int $participantId): bool
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "UPDATE conversa_participantes
             SET last_read_at = NOW()
             WHERE conversa_id = :conversa_id
               AND participant_type = :ptype
               AND participant_id = :pid"
        );
        $stmt->execute([
            'conversa_id' => $conversaId,
            'ptype' => $participantType,
            'pid' => $participantId,
        ]);
        return $stmt->rowCount() > 0;
    }
}
