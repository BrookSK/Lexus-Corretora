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
        $conteudo = View::renderizar(__DIR__ . '/../../Views/cliente/demandas-detalhe.php', [
            'demanda' => $demanda, 'propostas' => $propostas, 'timeline' => $timeline,
        ]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'cliente',
            'pageTitle' => $demanda['code'],
            'breadcrumbs' => [['label' => I18n::t('sidebar_cli.demandas'), 'url' => '/cliente/demandas'], ['label' => $demanda['code']]],
        ]));
    }
}
