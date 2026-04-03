<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Demandas\DemandasService;
use LEX\App\Services\Propostas\PropostasService;
use LEX\App\Services\Timeline\TimelineService;
use LEX\App\Services\Audit\AuditService;
use LEX\App\Services\Arquivos\ArquivosService;

final class DemandasController
{
    public function index(Requisicao $req): Resposta
    {
        $tab = $req->get('tab', 'todas');
        $page = max(1, (int)$req->get('page', '1'));
        $filtros = array_filter([
            'busca' => $req->get('busca'), 'status' => $req->get('status'),
            'urgency' => $req->get('urgency'), 'origin' => $req->get('origin'),
            'repasse_status' => $req->get('repasse_status'),
        ]);
        
        if ($tab === 'repasse') {
            $resultado = DemandasService::listarRepasses($page, 20, $filtros);
        } else {
            $resultado = DemandasService::listar($page, 20, $filtros);
        }
        
        $repassesPendentes = DemandasService::contarRepassesPendentes();
        
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/demandas.php', [
            'items' => $resultado['items'], 
            'total' => $resultado['total'], 
            'page' => $page,
            'repassesPendentes' => $repassesPendentes,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.demandas'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.demandas')]],
        ]));
    }

    public function criar(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/demandas-criar.php', []);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.demandas'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.demandas'), 'url' => '/equipe/demandas'], ['label' => I18n::t('geral.criar')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        $dados['origin'] = $dados['origin'] ?? 'equipe';
        $dados['status'] = $dados['status'] ?? 'novo';
        $dados['assigned_to'] = Auth::equipeId();
        $id = DemandasService::criar($dados);
        TimelineService::registrar($id, 'demanda_criada', 'Demanda criada pela equipe', 'equipe', Auth::equipeId());
        AuditService::registrar('equipe', Auth::equipeId(), 'demanda.criar', 'demandas', $id);

        // Processar arquivos enviados
        $filesRaw = $_FILES['files'] ?? [];
        if (!empty($filesRaw['name'])) {
            foreach ($filesRaw['name'] as $i => $nome) {
                $arq = ['name' => $nome, 'type' => $filesRaw['type'][$i], 'tmp_name' => $filesRaw['tmp_name'][$i], 'error' => $filesRaw['error'][$i], 'size' => $filesRaw['size'][$i]];
                if ($arq['error'] === UPLOAD_ERR_OK) {
                    try { ArquivosService::upload($arq, 'demanda', $id); } catch (\Throwable $e) { /* silenciar */ }
                }
            }
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/demandas/' . $id);
    }

    public function detalhe(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $demanda = DemandasService::obterPorId($id);
        if (!$demanda) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => I18n::t('geral.nenhum_registro')];
            return Resposta::redirecionar('/equipe/demandas');
        }
        $propostas = PropostasService::listarPorDemanda($id);
        $timeline = TimelineService::listarPorDemanda($id);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/demandas-detalhe.php', [
            'demanda' => $demanda, 'propostas' => $propostas, 'timeline' => $timeline,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => $demanda['code'],
            'breadcrumbs' => [['label' => I18n::t('sidebar.demandas'), 'url' => '/equipe/demandas'], ['label' => $demanda['code']]],
        ]));
    }

    public function editar(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $demanda = DemandasService::obterPorId($id);
        if (!$demanda) return Resposta::redirecionar('/equipe/demandas');
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/demandas-editar.php', ['demanda' => $demanda]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('geral.editar'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.demandas'), 'url' => '/equipe/demandas'], ['label' => I18n::t('geral.editar')]],
        ]));
    }

    public function atualizar(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        DemandasService::atualizar($id, $dados);
        AuditService::registrar('equipe', Auth::equipeId(), 'demanda.atualizar', 'demandas', $id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/demandas/' . $id);
    }

    public function alterarStatus(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $status = $req->post('status', '');
        DemandasService::alterarStatus($id, $status);
        TimelineService::registrar($id, 'status_alterado', "Status alterado para: {$status}", 'equipe', Auth::equipeId());
        AuditService::registrar('equipe', Auth::equipeId(), 'demanda.status', 'demandas', $id, ['status' => $status]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/demandas/' . $id);
    }
}

    public function aprovarRepasse(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $demanda = DemandasService::obterPorId($id);
        
        if (!$demanda || !$demanda['is_repasse']) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Demanda não encontrada ou não é um repasse'];
            return Resposta::redirecionar('/equipe/demandas?tab=repasse');
        }
        
        DemandasService::aprovarRepasse($id);
        TimelineService::registrar($id, 'repasse_aprovado', 'Repasse aprovado pela equipe', 'equipe', Auth::equipeId());
        AuditService::registrar('equipe', Auth::equipeId(), 'demanda.aprovar_repasse', 'demandas', $id);
        
        // Disparar notificação para o parceiro
        \LEX\App\Services\Notificacoes\EventosService::dispararEvento('repasse_aprovado', [
            'codigo' => $demanda['code'],
            'titulo' => $demanda['title'],
            'parceiro_id' => $demanda['parceiro_originador_id'],
        ]);
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Repasse aprovado com sucesso!'];
        return Resposta::redirecionar('/equipe/demandas/' . $id);
    }
