<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Parceiro;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth, Csrf};
use LEX\App\Services\Demandas\DemandasService;
use LEX\App\Services\Arquivos\ArquivosService;

final class RepasseController
{
    public function index(Requisicao $req): Resposta
    {
        $parceiroId = Auth::parceiroId();
        $pdo = \LEX\Core\BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT id, code, title, status, urgency, category, city, state, created_at
             FROM demandas
             WHERE parceiro_originador_id = :pid AND deleted_at IS NULL
             ORDER BY created_at DESC"
        );
        $stmt->execute(['pid' => $parceiroId]);
        $repasses = $stmt->fetchAll();

        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/repasse.php', [
            'repasses' => $repasses,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo'    => $conteudo,
            'painelTipo'  => 'parceiro',
            'pageTitle'   => I18n::t('sidebar_par.repasse'),
            'breadcrumbs' => [['label' => I18n::t('sidebar_par.repasse')]],
        ]));
    }

    public function criar(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../../Views/parceiro/repasse-criar.php');
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo'    => $conteudo,
            'painelTipo'  => 'parceiro',
            'pageTitle'   => I18n::t('sidebar_par.repasse'),
            'breadcrumbs' => [
                ['label' => I18n::t('sidebar_par.repasse'), 'url' => '/parceiro/repasse'],
                ['label' => I18n::t('geral.novo')],
            ],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);

        // Combinar dados do cliente indicado nas notas
        $clientNome = trim($dados['client_nome'] ?? '');
        $clientTel  = trim($dados['client_telefone'] ?? '');
        $contactInfo = 'Cliente indicado: ' . ($clientNome ?: '—');
        if ($clientTel) $contactInfo .= "\nContato: " . $clientTel;
        $dados['notes'] = $contactInfo . (empty($dados['notes']) ? '' : "\n\n" . $dados['notes']);
        unset($dados['client_nome'], $dados['client_telefone']);

        $dados['parceiro_originador_id'] = Auth::parceiroId();
        $dados['origin']  = 'repasse_parceiro';
        $dados['status']  = 'novo';

        $id = DemandasService::criar($dados);

        // Processar arquivos
        $filesRaw = $_FILES['files'] ?? [];
        if (!empty($filesRaw['name'])) {
            foreach ($filesRaw['name'] as $i => $nome) {
                $arq = [
                    'name'     => $nome,
                    'type'     => $filesRaw['type'][$i],
                    'tmp_name' => $filesRaw['tmp_name'][$i],
                    'error'    => $filesRaw['error'][$i],
                    'size'     => $filesRaw['size'][$i],
                ];
                if ($arq['error'] === UPLOAD_ERR_OK) {
                    try { ArquivosService::upload($arq, 'demanda', $id); } catch (\Throwable $e) { /* silenciar */ }
                }
            }
        }

        // Obter dados do parceiro para notificação
        $pdo = \LEX\Core\BancoDeDados::obter();
        $stmt = $pdo->prepare("SELECT name FROM parceiros WHERE id = :id");
        $stmt->execute(['id' => Auth::parceiroId()]);
        $parceiro = $stmt->fetch();
        
        // Obter código da demanda
        $demanda = DemandasService::obterPorId($id);
        
        // Disparar notificação
        \LEX\App\Services\Notificacoes\EventosService::dispararEvento('demanda_repassada', [
            'codigo' => $demanda['code'],
            'titulo' => $dados['title'],
            'parceiro' => $parceiro['name'] ?? 'Parceiro',
            'categoria' => $dados['category'] ?? '',
            'cidade' => $dados['city'] ?? '',
        ]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Repasse enviado com sucesso! Nossa equipe entrará em contato.'];
        return Resposta::redirecionar('/parceiro/repasse');
    }
}
