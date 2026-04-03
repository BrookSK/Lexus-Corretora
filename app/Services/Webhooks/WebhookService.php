<?php
declare(strict_types=1);
namespace LEX\App\Services\Webhooks;

use LEX\Core\BancoDeDados;
use LEX\Core\AppLogger;

final class WebhookService
{
    // Eventos disponíveis com descrição
    public static function eventosDisponiveis(): array
    {
        return [
            'nova_demanda'           => 'Nova demanda criada (cliente)',
            'nova_oportunidade'      => 'Oportunidade distribuída para parceiro',
            'nova_proposta'          => 'Nova proposta enviada por parceiro',
            'proposta_selecionada'   => 'Proposta selecionada pela equipe',
            'proposta_recusada'      => 'Proposta descartada/recusada',
            'contrato_formalizado'   => 'Contrato formalizado',
            'qualificacao_resultado' => 'Resultado de qualificação de parceiro',
            'novo_parceiro'          => 'Novo parceiro cadastrado',
            'novo_cliente'           => 'Novo cliente cadastrado',
            'novo_contato'           => 'Novo contato recebido pelo formulário',
        ];
    }

    /** Dispara webhooks configurados para um evento */
    public static function disparar(string $evento, array $payload): void
    {
        try {
            $pdo = BancoDeDados::obter();
            $stmt = $pdo->prepare(
                "SELECT * FROM webhook_configs WHERE evento = :evento AND ativo = 1"
            );
            $stmt->execute(['evento' => $evento]);
            $configs = $stmt->fetchAll();

            foreach ($configs as $config) {
                self::enviar($config, $evento, $payload);
            }
        } catch (\Throwable $e) {
            AppLogger::erro('[WebhookService] Erro ao disparar evento ' . $evento . ': ' . $e->getMessage());
        }
    }

    private static function enviar(array $config, string $evento, array $payload): void
    {
        $url = $config['url'];
        $body = json_encode(array_merge($payload, [
            'evento'     => $evento,
            'timestamp'  => date('c'),
            'sistema'    => 'Lexus Corretora',
        ]), JSON_UNESCAPED_UNICODE);

        $headers = [
            'Content-Type: application/json',
            'X-Lexus-Event: ' . $evento,
            'X-Lexus-Timestamp: ' . time(),
        ];

        // Assinatura HMAC se tiver secret configurado
        if (!empty($config['secret'])) {
            $sig = hash_hmac('sha256', $body, $config['secret']);
            $headers[] = 'X-Lexus-Signature: sha256=' . $sig;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response   = curl_exec($ch);
        $statusCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $erro       = curl_error($ch);
        curl_close($ch);

        $sucesso = $statusCode >= 200 && $statusCode < 300;

        // Log do disparo
        try {
            $pdo = BancoDeDados::obter();
            $pdo->prepare(
                "INSERT INTO webhook_logs (webhook_config_id, evento, url, payload, status_code, response, sucesso, erro)
                 VALUES (:wid, :evento, :url, :payload, :status, :response, :sucesso, :erro)"
            )->execute([
                'wid'      => $config['id'],
                'evento'   => $evento,
                'url'      => $url,
                'payload'  => $body,
                'status'   => $statusCode ?: null,
                'response' => $response ? substr($response, 0, 1000) : null,
                'sucesso'  => $sucesso ? 1 : 0,
                'erro'     => $erro ?: null,
            ]);
        } catch (\Throwable $e) { /* silenciar */ }

        if (!$sucesso) {
            AppLogger::aviso('[WebhookService] Falha ao enviar para ' . $url . ' (HTTP ' . $statusCode . '): ' . $erro);
        }
    }

    public static function listar(): array
    {
        $pdo = BancoDeDados::obter();
        return $pdo->query("SELECT * FROM webhook_configs ORDER BY evento, id")->fetchAll();
    }

    public static function criar(array $dados): int
    {
        $pdo = BancoDeDados::obter();
        $stmt = $pdo->prepare(
            "INSERT INTO webhook_configs (evento, url, ativo, secret, descricao)
             VALUES (:evento, :url, :ativo, :secret, :descricao)"
        );
        $stmt->execute([
            'evento'    => $dados['evento'],
            'url'       => $dados['url'],
            'ativo'     => (int)($dados['ativo'] ?? 1),
            'secret'    => $dados['secret'] ?: null,
            'descricao' => $dados['descricao'] ?: null,
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function atualizar(int $id, array $dados): void
    {
        $pdo = BancoDeDados::obter();
        $pdo->prepare(
            "UPDATE webhook_configs SET evento=:evento, url=:url, ativo=:ativo, secret=:secret, descricao=:descricao WHERE id=:id"
        )->execute([
            'id'        => $id,
            'evento'    => $dados['evento'],
            'url'       => $dados['url'],
            'ativo'     => (int)($dados['ativo'] ?? 1),
            'secret'    => $dados['secret'] ?: null,
            'descricao' => $dados['descricao'] ?: null,
        ]);
    }

    public static function excluir(int $id): void
    {
        BancoDeDados::obter()->prepare("DELETE FROM webhook_configs WHERE id=:id")->execute(['id' => $id]);
    }

    public static function listarLogs(int $limit = 50): array
    {
        $pdo = BancoDeDados::obter();
        return $pdo->query("SELECT * FROM webhook_logs ORDER BY created_at DESC LIMIT {$limit}")->fetchAll();
    }
}
