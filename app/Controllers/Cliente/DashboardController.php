<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Cliente;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};

final class DashboardController
{
    public function index(Requisicao $req): Resposta
    {
        $clienteId = Auth::clienteId();
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM demandas WHERE cliente_id = :c");
        $stmt->execute(['c' => $clienteId]);
        $totalDemandas = (int)$stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM propostas p INNER JOIN demandas d ON d.id = p.demanda_id WHERE d.cliente_id = :c AND p.presented_to_client = 1");
        $stmt->execute(['c' => $clienteId]);
        $totalPropostas = (int)$stmt->fetchColumn();

        $conteudo = View::renderizar(__DIR__ . '/../../Views/cliente/dashboard.php', [
            'totalDemandas'  => $totalDemandas,
            'totalPropostas' => $totalPropostas,
        ]);
        $html = View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo'   => $conteudo,
            'painelTipo' => 'cliente',
            'pageTitle'  => I18n::t('sidebar_cli.dashboard'),
            'breadcrumbs'=> [['label' => I18n::t('sidebar_cli.dashboard')]],
        ]);
        return Resposta::html($html);
    }
}
