<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Parceiro;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};

final class DashboardController
{
    public function index(Requisicao $req): Resposta
    {
        $parceiroId = Auth::parceiroId();
        $pdo = BancoDeDados::obter();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM oportunidade_destinatarios od INNER JOIN oportunidade_distribuicoes odi ON odi.id = od.distribuicao_id WHERE od.parceiro_id = :p");
        $stmt->execute(['p' => $parceiroId]);
        $oportunidadesRecebidas = (int)$stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM propostas WHERE parceiro_id = :p");
        $stmt->execute(['p' => $parceiroId]);
        $propostasEnviadas = (int)$stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COALESCE(SUM(commission_amount),0) FROM comissoes WHERE parceiro_id = :p AND status = 'recebida'");
        $stmt->execute(['p' => $parceiroId]);
        $comissoesRecebidas = (float)$stmt->fetchColumn();

        $stmt = $pdo->prepare("
            SELECT od.id, od.status, od.sent_at,
                   d.code AS demanda_code, d.title, d.city, d.state, d.budget_min, d.budget_max
            FROM oportunidade_destinatarios od
            JOIN oportunidade_distribuicoes odi ON odi.id = od.distribuicao_id
            JOIN demandas d ON d.id = odi.demanda_id
            WHERE od.parceiro_id = :p
              AND od.status IN ('enviado','visualizado')
              AND d.deleted_at IS NULL
            ORDER BY od.sent_at DESC
            LIMIT 5
        ");
        $stmt->execute(['p' => $parceiroId]);
        $oportunidadesPendentes = $stmt->fetchAll();

        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/dashboard.php', [
            'oportunidadesRecebidas'  => $oportunidadesRecebidas,
            'propostasEnviadas'       => $propostasEnviadas,
            'comissoesRecebidas'      => $comissoesRecebidas,
            'oportunidadesPendentes'  => $oportunidadesPendentes,
        ]);
        $html = View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo'   => $conteudo,
            'painelTipo' => 'parceiro',
            'pageTitle'  => I18n::t('sidebar_par.dashboard'),
            'breadcrumbs'=> [['label' => I18n::t('sidebar_par.dashboard')]],
        ]);
        return Resposta::html($html);
    }
}
