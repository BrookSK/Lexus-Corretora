<?php
declare(strict_types=1);
namespace LEX\App\Services\Parceiros;

use LEX\Core\BancoDeDados;
use PDO;

final class ParceirosService
{
    public static function listar(int $page = 1, int $perPage = 20, array $filtros = []): array
    {
        $pdo = BancoDeDados::obter();
        $where = ['p.deleted_at IS NULL'];
        $params = [];

        if (!empty($filtros['busca'])) {
            $where[] = '(p.name LIKE :busca OR p.email LIKE :busca2)';
            $params['busca'] = '%' . $filtros['busca'] . '%';
            $params['busca2'] = '%' . $filtros['busca'] . '%';
        }
        if (!empty($filtros['status'])) {
            $where[] = 'p.status = :status';
            $params['status'] = $filtros['status'];
        }
        if (!empty($filtros['type'])) {
            $where[] = 'p.type = :type';
            $params['type'] = $filtros['type'];
        }
        if (isset($filtros['is_vetriks'])) {
            $where[] = 'p.is_vetriks = :is_vetriks';
            $params['is_vetriks'] = (int)$filtros['is_vetriks'];
        }
        if (!empty($filtros['city'])) {
            $where[] = 'p.id IN (SELECT parceiro_id FROM parceiro_regioes WHERE city = :city)';
            $params['city'] = $filtros['city'];
        }
        if (!empty($filtros['state'])) {
            $where[] = 'p.id IN (SELECT parceiro_id FROM parceiro_regioes WHERE state = :state)';
            $params['state'] = $filtros['state'];
        }

        $whereSql = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM parceiros p WHERE {$whereSql}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT p.id, p.name, p.email, p.phone, p.type, p.status, p.score,
                    p.is_vetriks, p.availability, p.empresa_id, p.created_at,
                    ep.nome_fantasia AS empresa_nome
             FROM parceiros p
             LEFT JOIN empresas_parceiras ep ON ep.id = p.empresa_id
             WHERE {$whereSql}
             ORDER BY p.score DESC, p.created_at DESC
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
            "SELECT p.*, ep.nome_fantasia AS empresa_nome, ep.cnpj AS empresa_cnpj
             FROM parceiros p
             LEFT JOIN empresas_parceiras ep ON ep.id = p.empresa_id
             WHERE p.id = :id AND p.deleted_at IS NULL"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function criar(array $dados): int
    {
        $pdo = BancoDeDados::obter();
        if (!empty($dados['password'])) {
            $dados['password'] = password_hash($dados['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }

        $campos = ['empresa_id', 'name', 'email', 'password', 'phone', 'whatsapp', 'document',
                    'type', 'crea_cau', 'specialties', 'service_areas', 'service_cities',
                    'service_states', 'portfolio_url', 'bio', 'status', 'availability'];
        $insert = [];
        $params = [];
        foreach ($campos as $campo) {
            if (array_key_exists($campo, $dados)) {
                $insert[] = $campo;
                $val = $dados[$campo];
                $params[$campo] = is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : $val;
            }
        }

        $cols = implode(', ', array_map(fn($c) => "`{$c}`", $insert));
        $placeholders = implode(', ', array_map(fn($c) => ":{$c}", $insert));

        $stmt = $pdo->prepare("INSERT INTO parceiros ({$cols}) VALUES ({$placeholders})");
        $stmt->execute($params);

        return (int)$pdo->lastInsertId();
    }

    public static function atualizar(int $id, array $dados): bool
    {
        $pdo = BancoDeDados::obter();
        $campos = ['empresa_id', 'name', 'email', 'phone', 'whatsapp', 'document', 'type',
                    'avatar', 'crea_cau', 'specialties', 'service_areas', 'service_cities',
                    'service_states', 'portfolio_url', 'bio', 'availability',
                    'accepts_referral', 'referral_commission_pct', 'status', 'is_vetriks'];
        $set = [];
        $params = ['id' => $id];

        foreach ($campos as $campo) {
            if (array_key_exists($campo, $dados)) {
                $set[] = "`{$campo}` = :{$campo}";
                $val = $dados[$campo];
                $params[$campo] = is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : $val;
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
        $stmt = $pdo->prepare("UPDATE parceiros SET {$setSql} WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public static function alterarStatus(int $id, string $status): bool
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "UPDATE parceiros SET status = :status WHERE id = :id AND deleted_at IS NULL"
        );
        $stmt->execute(['id' => $id, 'status' => $status]);
        return $stmt->rowCount() > 0;
    }

    public static function listarPorCriterios(array $criterios): array
    {
        $pdo = BancoDeDados::obter();
        $where = ['p.deleted_at IS NULL', 'p.is_active = 1'];
        $params = [];

        if (!empty($criterios['city'])) {
            $where[] = 'p.id IN (SELECT parceiro_id FROM parceiro_regioes WHERE city = :city)';
            $params['city'] = $criterios['city'];
        }
        if (!empty($criterios['state'])) {
            $where[] = 'p.id IN (SELECT parceiro_id FROM parceiro_regioes WHERE state = :state)';
            $params['state'] = $criterios['state'];
        }
        if (!empty($criterios['type'])) {
            $where[] = 'p.type = :type';
            $params['type'] = $criterios['type'];
        }
        if (!empty($criterios['specialties'])) {
            $where[] = 'JSON_CONTAINS(p.specialties, :specialties)';
            $params['specialties'] = json_encode($criterios['specialties']);
        }
        if (isset($criterios['score_min'])) {
            $where[] = 'p.score >= :score_min';
            $params['score_min'] = (int)$criterios['score_min'];
        }
        if (isset($criterios['is_vetriks'])) {
            $where[] = 'p.is_vetriks = :is_vetriks';
            $params['is_vetriks'] = (int)$criterios['is_vetriks'];
        }

        $whereSql = implode(' AND ', $where);
        $stmt = $pdo->prepare(
            "SELECT p.id, p.name, p.email, p.type, p.score, p.is_vetriks,
                    p.availability, p.specialties, p.response_rate, p.close_rate
             FROM parceiros p
             WHERE {$whereSql}
             ORDER BY p.is_vetriks DESC, p.score DESC"
        );
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}
