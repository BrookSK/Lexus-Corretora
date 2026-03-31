<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, BancoDeDados};

final class JobsController
{
    public function index(Requisicao $req): Resposta
    {
        $pdo = BancoDeDados::obter();
        $jobs = $pdo->query("SELECT * FROM jobs ORDER BY created_at DESC LIMIT 100")->fetchAll();
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/jobs.php', ['jobs' => $jobs]);
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo, 'painelTipo' => 'equipe', 'pageTitle' => I18n::t('sidebar.jobs'),
            'breadcrumbs' => [['label' => I18n::t('sidebar.jobs')]],
        ]));
    }

    public function retry(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $pdo = BancoDeDados::obter();
        $pdo->prepare("UPDATE jobs SET status = 'pending', error = NULL, started_at = NULL, finished_at = NULL WHERE id = :id")->execute(['id' => $id]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Job reagendado.'];
        return Resposta::redirecionar('/equipe/jobs');
    }
}
