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

    public function app(Requisicao $req): Resposta
    {
        $logFile = dirname(__DIR__, 3) . '/storage/logs/app-' . date('Y-m-d') . '.log';
        $linhas = [];
        if (file_exists($logFile)) {
            $conteudo = file_get_contents($logFile);
            $linhas = array_reverse(array_filter(explode("\n", $conteudo)));
            $linhas = array_slice($linhas, 0, 200);
        }
        $html = '<div class="section-header"><div><h1 class="section-title">Log da Aplicação</h1><p class="section-subtitle">Hoje — ' . date('d/m/Y') . '</p></div></div>';
        $html .= '<div class="card" style="padding:24px">';
        if (empty($linhas)) {
            $html .= '<p style="color:var(--text-muted)">Nenhum log encontrado para hoje.</p>';
        } else {
            $html .= '<pre style="font-size:.75rem;line-height:1.6;overflow-x:auto;white-space:pre-wrap;word-break:break-all">';
            foreach ($linhas as $linha) {
                $cor = str_contains($linha, '[ERROR]') ? '#dc2626' : (str_contains($linha, '[INFO]') ? 'inherit' : '#d97706');
                $html .= '<span style="color:' . $cor . '">' . htmlspecialchars($linha) . '</span>' . "\n";
            }
            $html .= '</pre>';
        }
        $html .= '</div>';
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $html, 'painelTipo' => 'equipe', 'pageTitle' => 'Log App',
            'breadcrumbs' => [['label' => I18n::t('sidebar.logs'), 'url' => '/equipe/logs'], ['label' => 'App']],
        ]));
    }
}
