<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Cliente;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Demandas\DemandasService;
use LEX\App\Services\Propostas\PropostasService;
use LEX\App\Services\Timeline\TimelineService;
use LEX\App\Services\Arquivos\ArquivosService;

final class DemandasController
{
    public function index(Requisicao $req): Resposta
    {
        $demandas = DemandasService::listarPorCliente(Auth::clienteId());
        $conteudo = View::renderizar(__DIR__ . '/../../Views/cliente/demandas.php', ['demandas' => $demandas]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'cliente',
            'pageTitle' => I18n::t('sidebar_cli.demandas'),
            'breadcrumbs' => [['label' => I18n::t('sidebar_cli.demandas')]],
        ]));
    }

    public function criar(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../../Views/cliente/demandas-criar.php', []);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'cliente',
            'pageTitle' => I18n::t('nav.abrir_demanda'),
            'breadcrumbs' => [['label' => I18n::t('sidebar_cli.demandas'), 'url' => '/cliente/demandas'], ['label' => I18n::t('geral.criar')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        $dados['cliente_id'] = Auth::clienteId();
        $dados['origin'] = 'cliente';
        $dados['status'] = 'novo';
        $id = DemandasService::criar($dados);
        TimelineService::registrar($id, 'demanda_criada', 'Demanda criada pelo cliente', 'cliente', Auth::clienteId());

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

        // E-mail de confirmação
        try {
            $demanda = DemandasService::obterPorId($id);
            $clienteEmail = Auth::clienteEmail();
            $clienteNome  = Auth::clienteNome();
            if ($demanda && $clienteEmail) {
                \LEX\App\Services\Email\EmailService::novaDemanda(
                    $clienteEmail, $clienteNome ?? '', $demanda['code'] ?? '', $demanda['title'] ?? ''
                );
            }
        } catch (\Throwable $e) { /* silenciar */ }

        // Webhook
        try {
            $demanda = $demanda ?? DemandasService::obterPorId($id);
            \LEX\App\Services\Webhooks\WebhookService::disparar('nova_demanda', [
                'cliente_nome'    => Auth::clienteNome() ?? '',
                'cliente_email'   => Auth::clienteEmail() ?? '',
                'demanda_id'      => $id,
                'demanda_codigo'  => $demanda['code'] ?? '',
                'demanda_titulo'  => $demanda['title'] ?? '',
                'cidade'          => $demanda['city'] ?? '',
                'estado'          => $demanda['state'] ?? '',
            ]);
        } catch (\Throwable $e) { /* silenciar */ }

        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('demanda.sucesso')];
        return Resposta::redirecionar('/cliente/demandas/' . $id);
    }

    public function detalhe(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $demanda = DemandasService::obterPorId($id);
        if (!$demanda || (int)$demanda['cliente_id'] !== Auth::clienteId()) {
            return Resposta::redirecionar('/cliente/demandas');
        }
        $propostas = PropostasService::listarPorDemanda($id);
        $timeline = TimelineService::listarPorDemanda($id);
        $arquivos = ArquivosService::listarPorDemanda($id);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/cliente/demandas-detalhe.php', [
            'demanda' => $demanda, 'propostas' => $propostas, 'timeline' => $timeline, 'arquivos' => $arquivos,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'cliente',
            'pageTitle' => $demanda['code'],
            'breadcrumbs' => [['label' => I18n::t('sidebar_cli.demandas'), 'url' => '/cliente/demandas'], ['label' => $demanda['code']]],
        ]));
    }

    public function editar(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $demanda = DemandasService::obterPorId($id);
        if (!$demanda || (int)$demanda['cliente_id'] !== Auth::clienteId()) {
            return Resposta::redirecionar('/cliente/demandas');
        }
        $arquivos = ArquivosService::listarPorDemanda($id);
        $conteudo = View::renderizar(__DIR__ . '/../../Views/cliente/demandas-editar.php', [
            'demanda' => $demanda, 'arquivos' => $arquivos,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'cliente',
            'pageTitle' => 'Editar ' . $demanda['code'],
            'breadcrumbs' => [
                ['label' => I18n::t('sidebar_cli.demandas'), 'url' => '/cliente/demandas'],
                ['label' => $demanda['code'], 'url' => '/cliente/demandas/' . $id],
                ['label' => 'Editar']
            ],
        ]));
    }

    public function atualizar(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $demanda = DemandasService::obterPorId($id);
        if (!$demanda || (int)$demanda['cliente_id'] !== Auth::clienteId()) {
            return Resposta::redirecionar('/cliente/demandas');
        }

        $dados = $req->todosPost();
        unset($dados['_csrf_token']);

        // Detectar mudanças
        $mudancas = [];
        $camposEditaveis = ['title', 'description', 'category', 'subcategory', 'work_type', 
                            'city', 'state', 'address', 'area_sqm', 'current_phase',
                            'desired_deadline', 'budget_min', 'budget_max', 
                            'has_project', 'has_architect', 'wants_multiple_proposals', 'notes'];
        
        foreach ($camposEditaveis as $campo) {
            if (isset($dados[$campo]) && $dados[$campo] != ($demanda[$campo] ?? '')) {
                $mudancas[$campo] = [
                    'antes' => $demanda[$campo] ?? '',
                    'depois' => $dados[$campo]
                ];
            }
        }

        // Se houver mudanças, marcar para revisão
        if (!empty($mudancas)) {
            $dados['pending_review'] = 1;
            $dados['changes_log'] = json_encode([
                'timestamp' => date('Y-m-d H:i:s'),
                'cliente_id' => Auth::clienteId(),
                'cliente_nome' => Auth::clienteNome(),
                'mudancas' => $mudancas
            ], JSON_UNESCAPED_UNICODE);
        }

        DemandasService::atualizar($id, $dados);

        // Processar novos arquivos
        $filesRaw = $_FILES['files'] ?? [];
        $legendas = $req->post('captions') ?? [];
        if (!empty($filesRaw['name']) && is_array($filesRaw['name'])) {
            foreach ($filesRaw['name'] as $i => $nome) {
                if (empty($nome) || empty($filesRaw['tmp_name'][$i])) continue;
                $arq = [
                    'name' => $nome,
                    'type' => $filesRaw['type'][$i],
                    'tmp_name' => $filesRaw['tmp_name'][$i],
                    'error' => $filesRaw['error'][$i],
                    'size' => $filesRaw['size'][$i]
                ];
                if ($arq['error'] === UPLOAD_ERR_OK) {
                    try {
                        $legenda = $legendas[$i] ?? '';
                        ArquivosService::uploadComLegenda($arq, 'demanda', $id, $legenda);
                    } catch (\Throwable $e) {
                        error_log("Erro ao fazer upload: " . $e->getMessage());
                    }
                }
            }
        }

        // Registrar na timeline
        if (!empty($mudancas)) {
            $descricaoMudancas = [];
            foreach ($mudancas as $campo => $valores) {
                $descricaoMudancas[] = ucfirst($campo) . ' alterado';
            }
            TimelineService::registrar(
                $id,
                'demanda_editada_cliente',
                'Cliente editou a demanda: ' . implode(', ', $descricaoMudancas),
                'cliente',
                Auth::clienteId()
            );

            // Notificar parceiros vinculados
            $parceiros = DemandasService::obterParceirosVinculados($id);
            foreach ($parceiros as $parceiro) {
                \LEX\App\Services\Notificacoes\EventosService::dispararEvento('demanda_alterada_cliente', [
                    'parceiro_id' => $parceiro['id'],
                    'demanda_id' => $id,
                    'demanda_codigo' => $demanda['code'],
                    'demanda_titulo' => $demanda['title'],
                    'cliente_nome' => Auth::clienteNome(),
                ]);
            }
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Demanda atualizada com sucesso!'];
        return Resposta::redirecionar('/cliente/demandas/' . $id);
    }
}
