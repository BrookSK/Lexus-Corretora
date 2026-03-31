<?php
declare(strict_types=1);
namespace LEX\App\Services\Qualificacao;

use LEX\Core\BancoDeDados;
use PDO;

final class QualificacaoService
{
    public static function obterPorParceiro(int $parceiroId): ?array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT pq.*, u.name AS evaluator_nome
             FROM parceiro_qualificacoes pq
             LEFT JOIN users u ON u.id = pq.evaluator_id
             WHERE pq.parceiro_id = :parceiro_id
             ORDER BY pq.created_at DESC
             LIMIT 1"
        );
        $stmt->execute(['parceiro_id' => $parceiroId]);
        $qualificacao = $stmt->fetch();

        if (!$qualificacao) {
            return null;
        }

        $stmtItens = $pdo->prepare(
            "SELECT id, criterio, score, max_score, notes
             FROM parceiro_qualificacao_itens
             WHERE qualificacao_id = :qid
             ORDER BY id ASC"
        );
        $stmtItens->execute(['qid' => $qualificacao['id']]);
        $qualificacao['itens'] = $stmtItens->fetchAll();

        return $qualificacao;
    }

    public static function criar(int $parceiroId, int $evaluatorId, array $itens): int
    {
        $pdo = BancoDeDados::obter();

        $overallScore = 0;
        $maxTotal = 0;
        foreach ($itens as $item) {
            $overallScore += (int)($item['score'] ?? 0);
            $maxTotal += (int)($item['max_score'] ?? 10);
        }
        $normalizedScore = $maxTotal > 0 ? (int)round(($overallScore / $maxTotal) * 100) : 0;

        $stmt = $pdo->prepare(
            "INSERT INTO parceiro_qualificacoes (parceiro_id, evaluator_id, overall_score, status)
             VALUES (:parceiro_id, :evaluator_id, :overall_score, 'em_analise')"
        );
        $stmt->execute([
            'parceiro_id' => $parceiroId,
            'evaluator_id' => $evaluatorId,
            'overall_score' => $normalizedScore,
        ]);
        $qualificacaoId = (int)$pdo->lastInsertId();

        $stmtItem = $pdo->prepare(
            "INSERT INTO parceiro_qualificacao_itens (qualificacao_id, criterio, score, max_score, notes)
             VALUES (:qid, :criterio, :score, :max_score, :notes)"
        );
        foreach ($itens as $item) {
            $stmtItem->execute([
                'qid' => $qualificacaoId,
                'criterio' => $item['criterio'],
                'score' => (int)($item['score'] ?? 0),
                'max_score' => (int)($item['max_score'] ?? 10),
                'notes' => $item['notes'] ?? null,
            ]);
        }

        $pdo->prepare("UPDATE parceiros SET score = :score WHERE id = :id")
            ->execute(['score' => $normalizedScore, 'id' => $parceiroId]);

        return $qualificacaoId;
    }

    public static function atualizarParecer(int $id, string $parecer, string $status, bool $vetriksGranted): bool
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "UPDATE parceiro_qualificacoes
             SET parecer = :parecer, status = :status, vetriks_granted = :vetriks,
                 evaluated_at = NOW()
             WHERE id = :id"
        );
        $stmt->execute([
            'id' => $id,
            'parecer' => $parecer,
            'status' => $status,
            'vetriks' => $vetriksGranted ? 1 : 0,
        ]);

        if ($stmt->rowCount() > 0 && $vetriksGranted) {
            $qStmt = $pdo->prepare("SELECT parceiro_id FROM parceiro_qualificacoes WHERE id = :id");
            $qStmt->execute(['id' => $id]);
            $parceiroId = (int)$qStmt->fetchColumn();

            if ($parceiroId > 0) {
                $pdo->prepare(
                    "UPDATE parceiros SET is_vetriks = 1, vetriks_since = CURDATE(), status = 'vetriks_ativo'
                     WHERE id = :id"
                )->execute(['id' => $parceiroId]);
            }
        }

        return $stmt->rowCount() > 0;
    }

    public static function listarPendentes(): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->query(
            "SELECT pq.id, pq.parceiro_id, pq.overall_score, pq.status, pq.created_at,
                    p.name AS parceiro_nome, p.email AS parceiro_email, p.type AS parceiro_type
             FROM parceiro_qualificacoes pq
             JOIN parceiros p ON p.id = pq.parceiro_id
             WHERE pq.status IN ('pendente', 'em_analise')
             ORDER BY pq.created_at ASC"
        );
        return $stmt->fetchAll();
    }
}
