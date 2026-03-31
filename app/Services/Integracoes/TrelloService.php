<?php
declare(strict_types=1);
namespace LEX\App\Services\Integracoes;

use LEX\Core\{Settings, AppLogger};

final class TrelloService
{
    private const API_URL = 'https://api.trello.com/1';

    public static function ativo(): bool
    {
        return !empty(self::apiKey()) && !empty(self::apiToken()) && !empty(self::listId());
    }

    public static function apiKey(): string
    {
        return (string)Settings::obter('trello.api_key', '');
    }

    public static function apiToken(): string
    {
        return (string)Settings::obter('trello.api_token', '');
    }

    public static function listId(): string
    {
        return (string)Settings::obter('trello.list_id', '');
    }

    /**
     * Cria um card no Trello
     */
    public static function criarCard(string $nome, string $descricao, ?string $listId = null, array $labels = []): ?array
    {
        if (!self::ativo()) {
            AppLogger::info('Trello: integração inativa, card não criado', ['nome' => $nome]);
            return null;
        }

        $params = [
            'key'    => self::apiKey(),
            'token'  => self::apiToken(),
            'idList' => $listId ?: self::listId(),
            'name'   => $nome,
            'desc'   => $descricao,
            'pos'    => 'top',
        ];

        if (!empty($labels)) {
            $params['idLabels'] = implode(',', $labels);
        }

        $response = self::request('POST', '/cards', $params);

        if ($response && isset($response['id'])) {
            AppLogger::info('Trello: card criado', ['id' => $response['id'], 'nome' => $nome]);
            return $response;
        }

        AppLogger::erro('Trello: falha ao criar card', ['nome' => $nome, 'response' => $response]);
        return null;
    }

    /**
     * Helpers para criar cards de tipos específicos
     */
    public static function cardContato(array $dados): ?array
    {
        $nome = '📩 Contato: ' . ($dados['name'] ?? 'Sem nome');
        $desc = self::formatarDescricao([
            'Tipo'       => 'Formulário de Contato',
            'Nome'       => $dados['name'] ?? '',
            'WhatsApp'   => $dados['whatsapp'] ?? '',
            'E-mail'     => $dados['email'] ?? '',
            'Cidade'     => $dados['city'] ?? '',
            'Tipo Obra'  => $dados['work_type'] ?? '',
            'Área (m²)'  => $dados['area_sqm'] ?? '',
            'Padrão'     => $dados['finish_level'] ?? '',
            'Material'   => $dados['material_supply'] ?? '',
            'Prazo'      => $dados['desired_timeline'] ?? '',
            'Investimento' => $dados['budget_range'] ?? '',
            'Serviços'   => is_array($dados['servicos'] ?? null) ? implode(', ', $dados['servicos']) : ($dados['servicos'] ?? ''),
            'Projeto/Planta' => $dados['has_project'] ?? '',
            'Fotos'      => $dados['has_photos'] ?? '',
            'Observações'=> $dados['message'] ?? '',
        ]);

        $listId = (string)Settings::obter('trello.list_contato', '') ?: null;
        return self::criarCard($nome, $desc, $listId);
    }

    public static function cardDemanda(array $dados): ?array
    {
        $nome = '🏗️ Demanda: ' . ($dados['title'] ?? ($dados['name'] ?? 'Nova demanda'));
        $desc = self::formatarDescricao([
            'Tipo'        => 'Abertura de Demanda',
            'Nome'        => $dados['name'] ?? '',
            'WhatsApp'    => $dados['whatsapp'] ?? $dados['phone'] ?? '',
            'E-mail'      => $dados['email'] ?? '',
            'Empresa'     => $dados['company'] ?? '',
            'Cidade'      => $dados['city'] ?? '',
            'Estado'      => $dados['state'] ?? '',
            'Título'      => $dados['title'] ?? '',
            'Tipo Obra'   => $dados['work_type'] ?? '',
            'Endereço'    => $dados['address'] ?? '',
            'Área (m²)'   => $dados['area_sqm'] ?? '',
            'Orçamento'   => ($dados['budget_min'] ?? '') . ' — ' . ($dados['budget_max'] ?? ''),
            'Prazo'       => $dados['desired_deadline'] ?? '',
            'Urgência'    => $dados['urgency'] ?? '',
            'Descrição'   => $dados['description'] ?? '',
            'Observações' => $dados['notes'] ?? '',
        ]);

        $listId = (string)Settings::obter('trello.list_demanda', '') ?: null;
        return self::criarCard($nome, $desc, $listId);
    }

    public static function cardParceiro(array $dados): ?array
    {
        $nome = '🤝 Parceiro: ' . ($dados['name'] ?? 'Novo parceiro');
        $desc = self::formatarDescricao([
            'Tipo'          => 'Cadastro de Parceiro',
            'Nome'          => $dados['name'] ?? '',
            'Nome Fantasia' => $dados['trade_name'] ?? '',
            'Tipo Parceiro' => $dados['type'] ?? '',
            'CPF/CNPJ'      => $dados['document'] ?? '',
            'E-mail'        => $dados['email'] ?? '',
            'WhatsApp'      => $dados['whatsapp'] ?? '',
            'Site'          => $dados['website'] ?? '',
            'Instagram'     => $dados['instagram'] ?? '',
            'Especialidades'=> $dados['specialties'] ?? '',
            'Cidades'       => $dados['cities'] ?? '',
            'Tempo Mercado' => $dados['years_in_market'] ?? '',
            'Descrição'     => $dados['description'] ?? '',
        ]);

        $listId = (string)Settings::obter('trello.list_parceiro', '') ?: null;
        return self::criarCard($nome, $desc, $listId);
    }

    private static function formatarDescricao(array $campos): string
    {
        $linhas = [];
        foreach ($campos as $label => $valor) {
            $valor = is_string($valor) ? trim($valor) : (string)$valor;
            if ($valor !== '' && $valor !== ' — ') {
                $linhas[] = "**{$label}:** {$valor}";
            }
        }
        $linhas[] = '';
        $linhas[] = '---';
        $linhas[] = '*Criado automaticamente pela Lexus Corretora em ' . date('d/m/Y H:i') . '*';
        return implode("\n", $linhas);
    }

    private static function request(string $method, string $endpoint, array $params = []): ?array
    {
        $url = self::API_URL . $endpoint;

        $ch = curl_init();
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        } else {
            $url .= '?' . http_build_query($params);
        }

        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            AppLogger::erro('Trello: curl error', ['error' => $error]);
            return null;
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true) ?: [];
        }

        AppLogger::erro('Trello: HTTP ' . $httpCode, ['response' => $response]);
        return null;
    }
}
