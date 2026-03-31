<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Settings, Auth, SistemaConfig};
use LEX\App\Services\Audit\AuditService;

final class ConfigController
{
    public function index(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/configuracoes.php', []);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.configuracoes'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.configuracoes')]],
        ]));
    }

    public function salvar(Requisicao $req): Resposta
    {
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        foreach ($dados as $chave => $valor) {
            Settings::definir($chave, $valor);
        }
        AuditService::registrar('equipe', Auth::equipeId(), 'config.salvar', 'settings', null, array_keys($dados));
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/configuracoes');
    }

    public function secao(Requisicao $req): Resposta
    {
        $secao = $req->param('secao', 'branding');
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/configuracoes-secao.php', ['secao' => $secao]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe',
            'pageTitle' => I18n::t('sidebar.configuracoes'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.configuracoes'), 'url' => '/equipe/configuracoes'], ['label' => ucfirst($secao)]],
        ]));
    }

    public function salvarSecao(Requisicao $req): Resposta
    {
        $secao = $req->param('secao', 'branding');
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        foreach ($dados as $chave => $valor) {
            Settings::definir($chave, $valor);
        }
        AuditService::registrar('equipe', Auth::equipeId(), 'config.secao.' . $secao, 'settings', null);
        $_SESSION['flash'] = ['type' => 'success', 'message' => I18n::t('geral.sucesso')];
        return Resposta::redirecionar('/equipe/configuracoes/' . $secao);
    }
}
