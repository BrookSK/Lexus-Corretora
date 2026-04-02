<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Parceiro;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};
use LEX\App\Services\Demandas\DemandasService;
use LEX\App\Services\Distribuicao\DistribuicaoService;

final class OportunidadesController
{
    public function index(Requisicao $req): Resposta
    {
        $parceiroId = Auth::parceiroId();
        $pdo        = BancoDeDados::obter();

        $filtroEstado    = trim($req->get('estado', ''));
        $filtroCidade    = trim($req->get('cidade', ''));
        $filtroCategoria = trim($req->get('categoria', ''));
        $filtroStatus    = trim($req->get('status', ''));
        $filtroValorMin  = $req->get('valor_min', '');
        $filtroValorMax  = $req->get('valor_max', '');

        $where  = ['od.parceiro_id = :pid', 'd.deleted_at IS NULL'];
        $params = ['pid' => $parceiroId];

        if ($filtroEstado)             { $where[] = 'd.state = :state';           $params['state']     = $filtroEstado; }
        if ($filtroCidade)             { $where[] = 'd.city LIKE :city';          $params['city']      = '%' . $filtroCidade . '%'; }
        if ($filtroCategoria)          { $where[] = 'd.category = :categoria';    $params['categoria'] = $filtroCategoria; }
        if ($filtroStatus)             { $where[] = 'od.status = :status';        $params['status']    = $filtroStatus; }
        if ($filtroValorMin !== '')    { $where[] = 'd.budget_max >= :valor_min';  $params['valor_min'] = (float)$filtroValorMin; }
        if ($filtroValorMax !== '')    { $where[] = 'd.budget_min <= :valor_max';  $params['valor_max'] = (float)$filtroValorMax; }

        $whereSql = implode(' AND ', $where);

        $stmt = $pdo->prepare("
            SELECT od.id, od.status, od.sent_at, od.viewed_at,
                   d.id AS demanda_id, d.code AS demanda_code, d.title, d.city, d.state,
                   d.budget_min, d.budget_max, d.urgency, d.category, d.description, d.created_at
            FROM oportunidade_destinatarios od
            JOIN oportunidade_distribuicoes odi ON odi.id = od.distribuicao_id
            JOIN demandas d ON d.id = odi.demanda_id
            WHERE {$whereSql}
            ORDER BY od.sent_at DESC
        ");
        $stmt->execute($params);
        $oportunidades = $stmt->fetchAll();

        // Listas para dropdowns de filtro
        $statesStmt = $pdo->prepare("
            SELECT DISTINCT d.state FROM oportunidade_destinatarios od
            JOIN oportunidade_distribuicoes odi ON odi.id = od.distribuicao_id
            JOIN demandas d ON d.id = odi.demanda_id
            WHERE od.parceiro_id = :pid AND d.deleted_at IS NULL AND d.state IS NOT NULL
            ORDER BY d.state
        ");
        $statesStmt->execute(['pid' => $parceiroId]);
        $estados = $statesStmt->fetchAll(\PDO::FETCH_COLUMN);

        $catsStmt = $pdo->prepare("
            SELECT DISTINCT d.category FROM oportunidade_destinatarios od
            JOIN oportunidade_distribuicoes odi ON odi.id = od.distribuicao_id
            JOIN demandas d ON d.id = odi.demanda_id
            WHERE od.parceiro_id = :pid AND d.deleted_at IS NULL AND d.category IS NOT NULL
            ORDER BY d.category
        ");
        $catsStmt->execute(['pid' => $parceiroId]);
        $categorias = $catsStmt->fetchAll(\PDO::FETCH_COLUMN);

        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/oportunidades.php', [
            'oportunidades'   => $oportunidades,
            'estados'         => $estados,
            'categorias'      => $categorias,
            'filtroEstado'    => $filtroEstado,
            'filtroCidade'    => $filtroCidade,
            'filtroCategoria' => $filtroCategoria,
            'filtroStatus'    => $filtroStatus,
            'filtroValorMin'  => $filtroValorMin,
            'filtroValorMax'  => $filtroValorMax,
            'parceiroId'      => $parceiroId,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'parceiro',
            'pageTitle' => I18n::t('sidebar_par.oportunidades'),
            'breadcrumbs' => [['label' => I18n::t('sidebar_par.oportunidades')]],
        ]));
    }

    public function detalhe(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("
            SELECT od.*, odi.demanda_id
            FROM oportunidade_destinatarios od
            JOIN oportunidade_distribuicoes odi ON odi.id = od.distribuicao_id
            WHERE od.id = :id AND od.parceiro_id = :pid
        ");
        $stmt->execute(['id' => $id, 'pid' => Auth::parceiroId()]);
        $oportunidade = $stmt->fetch();
        if (!$oportunidade) return Resposta::redirecionar('/parceiro/oportunidades');

        // Marcar como visualizado
        if ($oportunidade['status'] === 'enviado') {
            DistribuicaoService::atualizarStatusDestinatario($id, 'visualizado');
            $oportunidade['status'] = 'visualizado';
        }

        $demanda = DemandasService::obterPorId((int)$oportunidade['demanda_id']);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/oportunidades-detalhe.php', [
            'oportunidade' => $oportunidade, 'demanda' => $demanda,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'parceiro',
            'pageTitle' => $demanda['code'] ?? 'Oportunidade',
            'breadcrumbs' => [['label' => I18n::t('sidebar_par.oportunidades'), 'url' => '/parceiro/oportunidades'], ['label' => $demanda['code'] ?? '']],
        ]));
    }

    public function interesse(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        DistribuicaoService::atualizarStatusDestinatario($id, 'interessado');
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Interesse registrado com sucesso.'];
        return Resposta::redirecionar('/parceiro/oportunidades/' . $id);
    }
}
