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
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("
            SELECT od.id, od.status, od.sent_at, od.viewed_at,
                   d.id AS demanda_id, d.code AS demanda_code, d.title, d.city, d.state,
                   d.budget_min, d.budget_max, d.urgency, d.created_at
            FROM oportunidade_destinatarios od
            JOIN oportunidade_distribuicoes odi ON odi.id = od.distribuicao_id
            JOIN demandas d ON d.id = odi.demanda_id
            WHERE od.parceiro_id = :pid AND d.deleted_at IS NULL
            ORDER BY od.sent_at DESC
        ");
        $stmt->execute(['pid' => $parceiroId]);
        $oportunidades = $stmt->fetchAll();

        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/oportunidades.php', ['oportunidades' => $oportunidades]);
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
