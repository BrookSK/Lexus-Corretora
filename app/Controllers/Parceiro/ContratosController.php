<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Parceiro;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, BancoDeDados};

final class ContratosController
{
    public function index(Requisicao $req): Resposta
    {
        $parceiroId = Auth::parceiroId();
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT ct.id, ct.amount, ct.status, ct.formalized_at, ct.created_at,
                    d.code AS demanda_code, d.title AS demanda_title,
                    d.city AS demanda_city, d.state AS demanda_state,
                    c.name AS cliente_nome, c.company AS cliente_company
             FROM contratos ct
             JOIN demandas d ON d.id = ct.demanda_id
             JOIN clientes c ON c.id = ct.cliente_id
             WHERE ct.parceiro_id = :pid
             ORDER BY ct.created_at DESC"
        );
        $stmt->execute(['pid' => $parceiroId]);
        $contratos = $stmt->fetchAll();

        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/contratos.php', ['contratos' => $contratos]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo'    => $conteudo,
            'painelTipo'  => 'parceiro',
            'pageTitle'   => 'Meus Contratos',
            'breadcrumbs' => [['label' => 'Contratos']],
        ]));
    }

    public function detalhe(Requisicao $req): Resposta
    {
        $id         = (int)$req->param('id');
        $parceiroId = Auth::parceiroId();
        $pdo        = BancoDeDados::obter();

        $stmt = $pdo->prepare(
            "SELECT ct.*,
                    d.code AS demanda_code, d.title AS demanda_title,
                    d.description AS demanda_description, d.category AS demanda_category,
                    d.city AS demanda_city, d.state AS demanda_state,
                    d.budget_min AS demanda_budget_min, d.budget_max AS demanda_budget_max,
                    d.urgency AS demanda_urgency,
                    c.name AS cliente_nome, c.company AS cliente_company,
                    c.email AS cliente_email, c.phone AS cliente_phone,
                    pr.description AS proposta_descricao, pr.deadline_days AS proposta_prazo,
                    pr.conditions AS proposta_condicoes
             FROM contratos ct
             JOIN demandas d ON d.id = ct.demanda_id
             JOIN clientes c ON c.id = ct.cliente_id
             LEFT JOIN propostas pr ON pr.id = ct.proposta_id
             WHERE ct.id = :id AND ct.parceiro_id = :pid"
        );
        $stmt->execute(['id' => $id, 'pid' => $parceiroId]);
        $contrato = $stmt->fetch();

        if (!$contrato) return Resposta::redirecionar('/parceiro/contratos');

        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/contratos-detalhe.php', ['contrato' => $contrato]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo'    => $conteudo,
            'painelTipo'  => 'parceiro',
            'pageTitle'   => 'Contrato #' . $id,
            'breadcrumbs' => [['label' => 'Contratos', 'url' => '/parceiro/contratos'], ['label' => '#' . $id]],
        ]));
    }
}
