<?php
declare(strict_types=1);
namespace LEX\App\Services\Distribuicao;

use LEX\Core\BancoDeDados;
use PDO;

final class DistribuicaoService
{
    public static function distribuir(int $demandaId, array $parceiroIds, int $distribuidoPor, string $tipo = 'manual'): int
    {
        $pdo = BancoDeDados::obter();

        $stmt = $pdo->prepare(
            "INSERT INTO oportunidade_distribuicoes (demanda_id, distributed_by, distribution_type)
             VALUES (:demanda_id, :distributed_by, :tipo)"
        );
        $stmt->execute([
            'demanda_id' => $demandaId,
            'distributed_by' => $distribuidoPor,
            'tipo' => $tipo,
        ]);
        $distribuicaoId = (int)$pdo->lastInsertId();

        $stmtDest = $pdo->prepare(
            "INSERT INTO oportunidade_destinatarios (distribuicao_id, parceiro_id, status, sent_at)
             VALUES (:distribuicao_id, :parceiro_id, 'enviado', NOW())"
        );
        foreach ($parceiroIds as $parceiroId) {
            $stmtDest->execute([
                'distribuicao_id' => $distribuicaoId,
                'parceiro_id' => (int)$parceiroId,
            ]);
        }

        $pdo->prepare(
            "UPDATE demandas SET status = 'distribuido' WHERE id = :id AND deleted_at IS NULL"
        )->execute(['id' => $demandaId]);

        return $distribuicaoId;
    }

    public static function listarDestinatarios(int $distribuicaoId): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT od.id, od.parceiro_id, od.status, od.sent_at, od.viewed_at,
                    od.responded_at, od.sla_deadline, od.notes,
                    p.name AS parceiro_nome, p.email AS parceiro_email,
                    p.type AS parceiro_type, p.score AS parceiro_score, p.is_vetriks
             FROM oportunidade_destinatarios od
             JOIN parceiros p ON p.id = od.parceiro_id
             WHERE od.distribuicao_id = :distribuicao_id
             ORDER BY p.score DESC"
        );
        $stmt->execute(['distribuicao_id' => $distribuicaoId]);
        return $stmt->fetchAll();
    }

    public static function atualizarStatusDestinatario(int $destinatarioId, string $status): bool
    {
        $pdo = BancoDeDados::obter();
        $extra = '';

        if ($status === 'visualizado') {
            $extra = ', viewed_at = NOW()';
        } elseif (in_array($status, ['interessado', 'recusado', 'proposta_enviada'], true)) {
            $extra = ', responded_at = NOW()';
        }

        $stmt = $pdo->prepare(
            "UPDATE oportunidade_destinatarios SET status = :status{$extra} WHERE id = :id"
        );
        $stmt->execute(['id' => $destinatarioId, 'status' => $status]);
        return $stmt->rowCount() > 0;
    }
}
