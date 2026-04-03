<?php
declare(strict_types=1);
namespace LEX\App\Services\Notificacoes;

use LEX\Core\BancoDeDados;
use PDO;

final class EventosService
{
    public static function listarEventos(): array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->query(
            "SELECT id, slug, name, description, is_active, destinatarios, 
                    template_message, available_variables
             FROM notificacao_eventos
             ORDER BY name ASC"
        );
        $eventos = $stmt->fetchAll();
        
        foreach ($eventos as &$evento) {
            $evento['destinatarios'] = json_decode($evento['destinatarios'] ?? '[]', true);
            $evento['available_variables'] = json_decode($evento['available_variables'] ?? '[]', true);
        }
        
        return $eventos;
    }

    public static function obterEvento(string $slug): ?array
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT id, slug, name, description, is_active, destinatarios,
                    template_message, available_variables
             FROM notificacao_eventos
             WHERE slug = :slug"
        );
        $stmt->execute(['slug' => $slug]);
        $evento = $stmt->fetch();
        
        if ($evento) {
            $evento['destinatarios'] = json_decode($evento['destinatarios'] ?? '[]', true);
            $evento['available_variables'] = json_decode($evento['available_variables'] ?? '[]', true);
        }
        
        return $evento ?: null;
    }

    public static function atualizarEvento(int $id, array $dados): bool
    {
        $pdo = BancoDeDados::obter();
        
        $set = [];
        $params = ['id' => $id];
        
        if (isset($dados['is_active'])) {
            $set[] = 'is_active = :is_active';
            $params['is_active'] = (int)$dados['is_active'];
        }
        
        if (isset($dados['destinatarios'])) {
            $set[] = 'destinatarios = :destinatarios';
            $params['destinatarios'] = json_encode($dados['destinatarios'], JSON_UNESCAPED_UNICODE);
        }
        
        if (isset($dados['template_message'])) {
            $set[] = 'template_message = :template_message';
            $params['template_message'] = $dados['template_message'];
        }
        
        if (empty($set)) {
            return false;
        }
        
        $setSql = implode(', ', $set);
        $stmt = $pdo->prepare("UPDATE notificacao_eventos SET {$setSql} WHERE id = :id");
        $stmt->execute($params);
        
        return $stmt->rowCount() > 0;
    }

    public static function dispararEvento(string $slug, array $variaveis = []): void
    {
        $evento = self::obterEvento($slug);
        
        if (!$evento || !$evento['is_active']) {
            return;
        }
        
        $mensagem = self::processarTemplate($evento['template_message'], $variaveis);
        $destinatarios = $evento['destinatarios'] ?? [];
        
        // Enviar notificação para cada tipo de destinatário
        foreach ($destinatarios as $tipo) {
            self::enviarNotificacao($tipo, $evento['name'], $mensagem, $variaveis);
        }
    }

    private static function processarTemplate(string $template, array $variaveis): string
    {
        $mensagem = $template;
        
        foreach ($variaveis as $chave => $valor) {
            $mensagem = str_replace('{{' . $chave . '}}', (string)$valor, $mensagem);
        }
        
        return $mensagem;
    }

    private static function enviarNotificacao(string $tipo, string $titulo, string $mensagem, array $variaveis): void
    {
        $pdo = BancoDeDados::obter();
        
        switch ($tipo) {
            case 'admin':
                // Enviar para todos os usuários admin
                $stmt = $pdo->query(
                    "SELECT id FROM users WHERE is_active = 1"
                );
                $admins = $stmt->fetchAll();
                
                foreach ($admins as $admin) {
                    NotificacoesService::criar('equipe', (int)$admin['id'], 'sistema', $titulo, $mensagem);
                    self::incrementarContador('users', (int)$admin['id']);
                }
                break;
                
            case 'cliente':
                if (!empty($variaveis['cliente_id'])) {
                    NotificacoesService::criar('cliente', (int)$variaveis['cliente_id'], 'sistema', $titulo, $mensagem);
                    self::incrementarContador('clientes', (int)$variaveis['cliente_id']);
                }
                break;
                
            case 'parceiro':
                if (!empty($variaveis['parceiro_id'])) {
                    NotificacoesService::criar('parceiro', (int)$variaveis['parceiro_id'], 'sistema', $titulo, $mensagem);
                    self::incrementarContador('parceiros', (int)$variaveis['parceiro_id']);
                }
                break;
        }
    }

    private static function incrementarContador(string $tabela, int $id): void
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "UPDATE {$tabela} SET unread_notifications = unread_notifications + 1 WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
    }

    public static function marcarComoLida(string $tipo, int $userId, int $notificacaoId): void
    {
        NotificacoesService::marcarLida($notificacaoId);
        
        $tabela = match($tipo) {
            'equipe' => 'users',
            'cliente' => 'clientes',
            'parceiro' => 'parceiros',
            default => null
        };
        
        if ($tabela) {
            $pdo = BancoDeDados::obter();
            $stmt = $pdo->prepare(
                "UPDATE {$tabela} SET unread_notifications = GREATEST(0, unread_notifications - 1) WHERE id = :id"
            );
            $stmt->execute(['id' => $userId]);
        }
    }

    public static function obterContadorNaoLidas(string $tipo, int $userId): int
    {
        $tabela = match($tipo) {
            'equipe' => 'users',
            'cliente' => 'clientes',
            'parceiro' => 'parceiros',
            default => null
        };
        
        if (!$tabela) {
            return 0;
        }
        
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "SELECT unread_notifications FROM {$tabela} WHERE id = :id"
        );
        $stmt->execute(['id' => $userId]);
        
        return (int)($stmt->fetchColumn() ?: 0);
    }
}
