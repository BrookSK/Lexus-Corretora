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

        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/dashboard.php', [
            'oportunidadesRecebidas' => $oportunidadesRecebidas,
            'propostasEnviadas'      => $propostasEnviadas,
            'comissoesRecebidas'     => $comissoesRecebidas,
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
