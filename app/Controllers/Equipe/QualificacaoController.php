<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Qualificacao\QualificacaoService;
use LEX\App\Services\Parceiros\ParceirosService;
use LEX\App\Services\Audit\AuditService;

final class QualificacaoController
{
    public function index(Requisicao $req): Resposta
    {
        $pendentes = QualificacaoService::listarPendentes();
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/qualificacao.php', ['items' => $pendentes]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.qualificacao'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.qualificacao')]],
        ]));
    }

    public function avaliar(Requisicao $req): Resposta
    {
        $parceiroId = (int)$req->param('parceiroId');
        $parceiro = ParceirosService::obterPorId($parceiroId);
        if (!$parceiro) return Resposta::redirecionar('/equipe/qualificacao');
        $qualificacao = QualificacaoService::obterPorParceiro($parceiroId);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/qualificacao-avaliar.php', [
            'parceiro' => $parceiro, 'qualificacao' => $qualificacao,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => 'Qualificar — ' . $parceiro['name'],
            'breadcrumbs' => [['label' => I18n::t('sidebar.qualificacao'), 'url' => '/equipe/qualificacao'], ['label' => $parceiro['name']]],
        ]));
    }

    public function salvarAvaliacao(Requisicao $req): Resposta
    {
        $parceiroId = (int)$req->param('parceiroId');
        $itens = [];
        $criterios = $req->post('criterio', []);
        $scores = $req->post('score', []);
        $notas = $req->post('nota', []);
        foreach ($criterios as $i => $criterio) {
            $itens[] = ['criterio' => $criterio, 'score' => (int)($scores[$i] ?? 0), 'max_score' => 10, 'notes' => $notas[$i] ?? ''];
        }
        $qId = QualificacaoService::criar($parceiroId, Auth::equipeId(), $itens);
        $parecer = $req->post('parecer', '');
        $status = $req->post('status_final', 'em_analise');
        $vetriks = $req->post('vetriks_granted', '0') === '1';
        QualificacaoService::atualizarParecer($qId, $parecer, $status, $vetriks);
        if ($status === 'aprovado') ParceirosService::alterarStatus($parceiroId, $vetriks ? 'vetriks_ativo' : 'aprovado');
        elseif ($status === 'reprovado') ParceirosService::alterarStatus($parceiroId, 'reprovado');
        AuditService::registrar('equipe', Auth::equipeId(), 'qualificacao.avaliar', 'parceiros', $parceiroId, ['status' => $status, 'vetriks' => $vetriks]);

        // Notificar parceiro por e-mail
        $parceiro = ParceirosService::obterPorId($parceiroId);
        if ($parceiro && !empty($parceiro['email'])) {
            try {
                \LEX\App\Services\Email\EmailService::resultadoQualificacao(
                    $parceiro['email'],
                    $parceiro['name'] ?? '',
                    $vetriks ? 'vetriks_ativo' : $status,
                    $parecer
                );
            } catch (\Throwable $e) { /* silenciar */ }
        }

        // Webhook
        try {
            \LEX\App\Services\Webhooks\WebhookService::disparar('qualificacao_resultado', [
                'parceiro_id'    => $parceiroId,
                'parceiro_nome'  => $parceiro['name'] ?? '',
                'parceiro_email' => $parceiro['email'] ?? '',
                'status'         => $status,
                'vetriks'        => $vetriks,
                'parecer'        => $parecer,
            ]);
        } catch (\Throwable $e) { /* silenciar */ }
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/parceiros/' . $parceiroId);
    }
}
