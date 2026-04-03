<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Parceiro;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Propostas\PropostasService;
use LEX\App\Services\Demandas\DemandasService;
use LEX\App\Services\Timeline\TimelineService;
use LEX\App\Services\Arquivos\ArquivosService;

final class PropostasController
{
    public function index(Requisicao $req): Resposta
    {
        $propostas = PropostasService::listarPorParceiro(Auth::parceiroId());
        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/propostas.php', ['propostas' => $propostas]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'parceiro',
            'pageTitle' => I18n::t('sidebar_par.propostas'),
            'breadcrumbs' => [['label' => I18n::t('sidebar_par.propostas')]],
        ]));
    }

    public function criar(Requisicao $req): Resposta
    {
        $demandaId = (int)$req->param('demandaId');
        $demanda = DemandasService::obterPorId($demandaId);
        if (!$demanda) return Resposta::redirecionar('/parceiro/oportunidades');
        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/propostas-criar.php', ['demanda' => $demanda]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'parceiro',
            'pageTitle' => 'Enviar Proposta',
            'breadcrumbs' => [['label' => I18n::t('sidebar_par.propostas'), 'url' => '/parceiro/propostas'], ['label' => 'Nova']],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        $dados['parceiro_id'] = Auth::parceiroId();
        $dados['status'] = 'enviada';
        $id = PropostasService::criar($dados);
        if (!empty($dados['demanda_id'])) {
            TimelineService::registrar((int)$dados['demanda_id'], 'proposta_enviada', 'Nova proposta recebida', 'parceiro', Auth::parceiroId());
        }

        // Notificar cliente sobre nova proposta
        try {
            $demandaId = (int)($dados['demanda_id'] ?? 0);
            if ($demandaId) {
                $demanda = DemandasService::obterPorId($demandaId);
                if ($demanda && !empty($demanda['cliente_email'])) {
                    \LEX\App\Services\Email\EmailService::novaPropostaCliente(
                        $demanda['cliente_email'],
                        $demanda['cliente_nome'] ?? '',
                        $demanda['code'] ?? '',
                        Auth::parceiroNome() ?? ''
                    );
                }
                // Webhook
                \LEX\App\Services\Webhooks\WebhookService::disparar('nova_proposta', [
                    'parceiro_id'    => Auth::parceiroId(),
                    'parceiro_nome'  => Auth::parceiroNome() ?? '',
                    'demanda_id'     => $demandaId,
                    'demanda_codigo' => $demanda['code'] ?? '',
                    'demanda_titulo' => $demanda['title'] ?? '',
                    'cliente_nome'   => $demanda['cliente_nome'] ?? '',
                    'cliente_email'  => $demanda['cliente_email'] ?? '',
                    'proposta_id'    => $id,
                ]);
            }
        } catch (\Throwable $e) { /* silenciar */ }

        // Processar arquivos da proposta
        $filesRaw = $_FILES['files'] ?? [];
        if (!empty($filesRaw['name'])) {
            foreach ($filesRaw['name'] as $i => $nome) {
                $arq = ['name' => $nome, 'type' => $filesRaw['type'][$i], 'tmp_name' => $filesRaw['tmp_name'][$i], 'error' => $filesRaw['error'][$i], 'size' => $filesRaw['size'][$i]];
                if ($arq['error'] === UPLOAD_ERR_OK) {
                    try { ArquivosService::upload($arq, 'proposta', $id); } catch (\Throwable $e) { /* silenciar */ }
                }
            }
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Pré-orçamento enviado com sucesso.'];
        return Resposta::redirecionar('/parceiro/propostas');
    }
}
