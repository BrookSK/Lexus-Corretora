<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Propostas\PropostasService;
use LEX\App\Services\Audit\AuditService;
use LEX\App\Services\Arquivos\ArquivosService;

final class PropostasController
{
    public function index(Requisicao $req): Resposta
    {
        $filtros = array_filter([
            'status'    => $req->get('status'),
            'category'  => $req->get('category'),
            'state'     => $req->get('state'),
            'city'      => $req->get('city'),
            'date_from' => $req->get('date_from'),
            'date_to'   => $req->get('date_to'),
        ]);
        $resultado = PropostasService::listar(1, 500, $filtros);

        // Agrupar por demanda
        $agrupadas = [];
        foreach ($resultado['items'] as $item) {
            $did = $item['demanda_id'];
            if (!isset($agrupadas[$did])) {
                $agrupadas[$did] = [
                    'demanda_id'       => $did,
                    'demanda_code'     => $item['demanda_code'] ?? ('#' . $did),
                    'demanda_title'    => $item['demanda_title'] ?? '',
                    'demanda_category' => $item['demanda_category'] ?? '',
                    'demanda_state'    => $item['demanda_state'] ?? '',
                    'demanda_city'     => $item['demanda_city'] ?? '',
                    'cliente_nome'     => $item['cliente_nome'] ?? '',
                    'propostas'        => [],
                ];
            }
            $agrupadas[$did]['propostas'][] = $item;
        }
        $agrupadas = array_values($agrupadas);

        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/propostas.php', [
            'agrupadas' => $agrupadas, 'total' => $resultado['total'], 'filtros' => $filtros,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.propostas'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.propostas')]],
        ]));
    }

    public function detalhe(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $proposta = PropostasService::obterPorId($id);
        if (!$proposta) return Resposta::redirecionar('/equipe/propostas');
        $proposta['arquivos'] = ArquivosService::listarPorEntidade('proposta', $id);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/propostas-detalhe.php', ['proposta' => $proposta]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.propostas'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.propostas'), 'url' => '/equipe/propostas'], ['label' => I18n::t('geral.detalhes')]],
        ]));
    }

    public function comparar(Requisicao $req): Resposta
    {
        $demandaId = (int)$req->param('demandaId');
        $propostas = PropostasService::compararPorDemanda($demandaId);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/propostas-comparar.php', [
            'propostas' => $propostas, 'demandaId' => $demandaId,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => 'Comparar Propostas',
            'breadcrumbs' => [['label' => I18n::t('sidebar.propostas'), 'url' => '/equipe/propostas'], ['label' => 'Comparar']],
        ]));
    }

    public function alterarStatus(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $status = $req->post('status', '');
        PropostasService::alterarStatus($id, $status);
        AuditService::registrar('equipe', Auth::equipeId(), 'proposta.status', 'propostas', $id, ['status' => $status]);

        // Notificar parceiro
        try {
            $proposta = PropostasService::obterPorId($id);
            if ($proposta && !empty($proposta['parceiro_email'])) {
                $codigo = $proposta['demanda_code'] ?? '';
                if ($status === 'selecionada' || $status === 'convertida') {
                    \LEX\App\Services\Email\EmailService::propostaSelecionada(
                        $proposta['parceiro_email'], $proposta['parceiro_nome'] ?? '', $codigo
                    );
                } elseif ($status === 'descartada' || $status === 'perdida') {
                    \LEX\App\Services\Email\EmailService::propostaRecusada(
                        $proposta['parceiro_email'], $proposta['parceiro_nome'] ?? '', $codigo
                    );
                }
            }
            // Webhook
            $whEvento = in_array($status, ['selecionada','convertida']) ? 'proposta_selecionada' : (in_array($status, ['descartada','perdida']) ? 'proposta_recusada' : null);
            if ($whEvento && $proposta) {
                \LEX\App\Services\Webhooks\WebhookService::disparar($whEvento, [
                    'proposta_id'    => $id,
                    'parceiro_id'    => $proposta['parceiro_id'] ?? null,
                    'parceiro_nome'  => $proposta['parceiro_nome'] ?? '',
                    'parceiro_email' => $proposta['parceiro_email'] ?? '',
                    'demanda_codigo' => $proposta['demanda_code'] ?? '',
                    'demanda_titulo' => $proposta['demanda_title'] ?? '',
                    'status'         => $status,
                    'valor'          => $proposta['amount'] ?? '',
                ]);
            }
        } catch (\Throwable $e) { /* silenciar */ }
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/propostas/' . $id);
    }
}
