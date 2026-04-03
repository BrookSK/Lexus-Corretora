<?php
declare(strict_types=1);
namespace LEX\App\Controllers\Equipe;

use LEX\Core\Http\{Requisicao, Resposta};
use LEX\Core\{View, I18n, Auth};
use LEX\App\Services\Notificacoes\EventosService;
use LEX\App\Services\Audit\AuditService;

final class NotificacoesController
{
    public function index(Requisicao $req): Resposta
    {
        $eventos = EventosService::listarEventos();
        
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/notificacoes-eventos.php', [
            'eventos' => $eventos,
        ]);
        
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo,
            'painelTipo' => 'equipe',
            'pageTitle' => 'Configurar Notificações',
            'breadcrumbs' => [
                ['label' => 'Configurações', 'url' => '/equipe/configuracoes'],
                ['label' => 'Notificações'],
            ],
        ]));
    }

    public function editar(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $eventos = EventosService::listarEventos();
        $evento = null;
        
        foreach ($eventos as $e) {
            if ($e['id'] === $id) {
                $evento = $e;
                break;
            }
        }
        
        if (!$evento) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Evento não encontrado'];
            return Resposta::redirecionar('/equipe/notificacoes');
        }
        
        $conteudo = View::renderizar(__DIR__ . '/../../Views/equipe/notificacoes-editar.php', [
            'evento' => $evento,
        ]);
        
        return Resposta::html(View::renderizar(__DIR__ . '/../../Views/_layouts/painel.php', [
            'conteudo' => $conteudo,
            'painelTipo' => 'equipe',
            'pageTitle' => 'Editar Evento de Notificação',
            'breadcrumbs' => [
                ['label' => 'Configurações', 'url' => '/equipe/configuracoes'],
                ['label' => 'Notificações', 'url' => '/equipe/notificacoes'],
                ['label' => 'Editar'],
            ],
        ]));
    }

    public function atualizar(Requisicao $req): Resposta
    {
        $id = (int)$req->param('id');
        $dados = $req->todosPost();
        unset($dados['_csrf_token']);
        
        // Processar destinatários
        $destinatarios = [];
        if (!empty($dados['dest_admin'])) $destinatarios[] = 'admin';
        if (!empty($dados['dest_cliente'])) $destinatarios[] = 'cliente';
        if (!empty($dados['dest_parceiro'])) $destinatarios[] = 'parceiro';
        
        $dadosEvento = [
            'is_active' => !empty($dados['is_active']) ? 1 : 0,
            'destinatarios' => $destinatarios,
            'template_message' => $dados['template_message'] ?? '',
        ];
        
        EventosService::atualizarEvento($id, $dadosEvento);
        AuditService::registrar('equipe', Auth::equipeId(), 'notificacao.atualizar_evento', 'notificacao_eventos', $id);
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Evento atualizado com sucesso!'];
        return Resposta::redirecionar('/equipe/notificacoes');
    }
}
