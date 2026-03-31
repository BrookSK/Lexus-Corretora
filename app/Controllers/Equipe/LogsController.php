<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, BancoDeDados};

final class LogsController
{
    public function index(Requisicao $req): Resposta
    {
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/logs.php', []);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => I18n::t('sidebar.logs'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.logs')]],
        ]));
    }

    public function erros(Requisicao $req): Resposta
    {
        $pdo = BancoDeDados::obter();
        $logs = $pdo->query("SELECT * FROM system_errors ORDER BY created_at DESC LIMIT 100")->fetchAll();
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/logs-erros.php', ['logs' => $logs]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => 'Logs de Erros',
            'breadcrumbs' => [['label' => I18n::t('sidebar.logs'), 'url' => '/equipe/logs'], ['label' => 'Erros']],
        ]));
    }

    public function auditoria(Requisicao $req): Resposta
    {
        $pdo = BancoDeDados::obter();
        $logs = $pdo->query("SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 100")->fetchAll();
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/logs-auditoria.php', ['logs' => $logs]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => 'Auditoria',
            'breadcrumbs' => [['label' => I18n::t('sidebar.logs'), 'url' => '/equipe/logs'], ['label' => 'Auditoria']],
        ]));
    }

    public function auth(Requisicao $req): Resposta
    {
        $pdo = BancoDeDados::obter();
        $logs = $pdo->query("SELECT * FROM auth_logs ORDER BY created_at DESC LIMIT 100")->fetchAll();
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/logs-auth.php', ['logs' => $logs]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => 'Logs de Autenticação',
            'breadcrumbs' => [['label' => I18n::t('sidebar.logs'), 'url' => '/equipe/logs'], ['label' => 'Autenticação']],
        ]));
    }
}
