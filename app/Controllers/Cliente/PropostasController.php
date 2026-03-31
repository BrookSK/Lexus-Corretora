<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Cliente;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};

final class PropostasController
{
    public function index(Requisicao $req): Resposta
    {
        $clienteId = Auth::clienteId();
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare("
            SELECT pr.*, d.code AS demanda_code, d.title AS demanda_title, p.name AS parceiro_nome
            FROM propostas pr
            JOIN demandas d ON d.id = pr.demanda_id
            JOIN parceiros p ON p.id = pr.parceiro_id
            WHERE d.cliente_id = :cid AND pr.presented_to_client = 1
            ORDER BY pr.created_at DESC
        ");
        $stmt->execute(['cid' => $clienteId]);
        $propostas = $stmt->fetchAll();

        $conteudo = View::renderizar(__DIR__ . '/../../Views/cliente/propostas.php', ['propostas' => $propostas]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'cliente',
            'pageTitle' => I18n::t('sidebar_cli.propostas'),
            'breadcrumbs' => [['label' => I18n::t('sidebar_cli.propostas')]],
        ]));
    }
}
