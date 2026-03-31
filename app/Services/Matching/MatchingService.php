<?php
declare(strict_types=1);
namespace LEX\App\Services\Matching;

use LEX\Core\BancoDeDados;
use PDO;

final class MatchingService
{
    public static function sugerirParceiros(int $demandaId, int $limit = 10): array
    {
        $pdo = BancoDeDados::obter();

        $stmtDemanda = $pdo->prepare(
            "SELECT id, city, state, category, work_type, budget_min, budget_max,
                    urgency, complexity, ideal_partner_profile
             FROM demandas WHERE id = :id AND deleted_at IS NULL"
        );
        $stmtDemanda->execute(['id' => $demandaId]);
        $demanda = $stmtDemanda->fetch();

        if (!$demanda) {
            return [];
        }

        $where = [
            'p.deleted_at IS NULL',
            'p.is_active = 1',
            "p.status IN ('aprovado', 'vetriks_ativo')",
            "p.availability IN ('disponivel', 'parcial')",
        ];
        $params = [];

        if (!empty($demanda['state'])) {
            $where[] = '(p.id IN (SELECT parceiro_id FROM parceiro_regioes WHERE state = :state) OR JSON_CONTAINS(p.service_states, :state_json))';
            $params['state'] = $demanda['state'];
            $params['state_json'] = json_encode($demanda['state']);
        }

        $whereSql = implode(' AND ', $where);

        $stmt = $pdo->prepare(
            "SELECT p.id, p.name, p.email, p.type, p.score, p.is_vetriks,
                    p.availability, p.specialties, p.service_cities, p.service_states,
                    p.response_rate, p.close_rate,
                    ep.nome_fantasia AS empresa_nome
             FROM parceiros p
             LEFT JOIN empresas_parceiras ep ON ep.id = p.empresa_id
             WHERE {$whereSql}
             ORDER BY p.is_vetriks DESC, p.score DESC
             LIMIT :limit"
        );
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $parceiros = $stmt->fetchAll();

        foreach ($parceiros as &$parceiro) {
            $parceiro['match_score'] = self::calcularScore($demanda, $parceiro);
        }
        unset($parceiro);

        usort($parceiros, fn($a, $b) => $b['match_score'] <=> $a['match_score']);

        return $parceiros;
    }

    public static function calcularScore(array $demanda, array $parceiro): int
    {
        $score = 0;

        // Vetriks bonus (20 pts)
        if (!empty($parceiro['is_vetriks'])) {
            $score += 20;
        }

        // Partner score contribution (max 25 pts)
        $partnerScore = (int)($parceiro['score'] ?? 0);
        $score += (int)min(25, round($partnerScore * 0.25));

        // City match (15 pts)
        if (!empty($demanda['city']) && !empty($parceiro['service_cities'])) {
            $cities = is_string($parceiro['service_cities'])
                ? json_decode($parceiro['service_cities'], true) ?? []
                : (array)$parceiro['service_cities'];
            if (in_array($demanda['city'], $cities, true)) {
                $score += 15;
            }
        }

        // State match (10 pts)
        if (!empty($demanda['state']) && !empty($parceiro['service_states'])) {
            $states = is_string($parceiro['service_states'])
                ? json_decode($parceiro['service_states'], true) ?? []
                : (array)$parceiro['service_states'];
            if (in_array($demanda['state'], $states, true)) {
                $score += 10;
            }
        }

        // Availability bonus (10 pts)
        if (($parceiro['availability'] ?? '') === 'disponivel') {
            $score += 10;
        } elseif (($parceiro['availability'] ?? '') === 'parcial') {
            $score += 5;
        }

        // Response rate (max 10 pts)
        $responseRate = (float)($parceiro['response_rate'] ?? 0);
        $score += (int)round($responseRate / 10);

        // Close rate (max 10 pts)
        $closeRate = (float)($parceiro['close_rate'] ?? 0);
        $score += (int)round($closeRate / 10);

        return min(100, max(0, $score));
    }
}
