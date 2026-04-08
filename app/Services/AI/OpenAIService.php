<?php
declare(strict_types=1);
namespace LEX\App\Services\AI;

/**
 * OpenAI Service - Geração de Descrições Formais
 * 
 * ATENÇÃO CRÍTICA: Este serviço foi configurado para NUNCA inventar dados.
 * A IA apenas reformata e organiza informações existentes.
 * 
 * Configurações de segurança:
 * - Temperature: 0.2 (muito baixa para evitar criatividade)
 * - System prompt: instruções explícitas para não inventar
 * - User prompt: lista completa de campos com avisos
 * - Fallback: apenas concatena dados existentes
 * 
 * @version 2.0 - Atualizado para garantir fidelidade aos dados originais
 */
final class OpenAIService
{
    private const API_URL = 'https://api.openai.com/v1/chat/completions';
    
    /**
     * Obtém a API key das configurações
     */
    private static function getApiKey(): ?string
    {
        return \LEX\Core\Settings::obter('gpt.api_key');
    }
    
    /**
     * Obtém o modelo configurado
     */
    private static function getModel(): string
    {
        return \LEX\Core\Settings::obter('gpt.model', 'gpt-4');
    }
    
    /**
     * Obtém a temperature configurada
     */
    private static function getTemperature(): float
    {
        return (float)\LEX\Core\Settings::obter('gpt.temperature', '0.2');
    }
    
    /**
     * Obtém o max_tokens configurado
     */
    private static function getMaxTokens(): int
    {
        return (int)\LEX\Core\Settings::obter('gpt.max_tokens', '1500');
    }
    
    /**
     * Gera descrição formal da demanda usando GPT
     */
    public static function gerarDescricaoFormal(array $demanda, array $arquivos = []): string
    {
        $apiKey = self::getApiKey();
        
        // Se não houver API key configurada, usar fallback
        if (empty($apiKey)) {
            error_log('[OpenAI] API key não configurada. Configure em Configurações > GPT.');
            return self::gerarDescricaoFallback($demanda);
        }
        
        $prompt = self::construirPrompt($demanda, $arquivos);
        
        $data = [
            'model' => self::getModel(),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Você é um engenheiro civil experiente que prepara apresentações técnicas de projetos. Sua função é organizar informações de forma profissional, clara e estruturada, como um engenheiro apresentaria para clientes e parceiros.

REGRAS CRÍTICAS:
1. NUNCA invente dados técnicos, medidas ou especificações
2. Use APENAS as informações fornecidas
3. Organize o conteúdo de forma lógica e profissional
4. Incorpore as legendas das fotos como observações técnicas relevantes
5. Corrija erros de português e gramática
6. Use linguagem técnica apropriada mas acessível
7. Estruture em seções claras: Resumo, Características, Requisitos, Observações
8. Mantenha o significado EXATO das informações originais
9. Se uma informação não existe, NÃO a mencione
10. Apresente como um memorial descritivo profissional'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => self::getTemperature(),
            'max_tokens' => self::getMaxTokens()
        ];
        
        $ch = curl_init(self::API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            error_log("OpenAI API Error: HTTP {$httpCode} - {$response}");
            return self::gerarDescricaoFallback($demanda);
        }
        
        $result = json_decode($response, true);
        
        if (isset($result['choices'][0]['message']['content'])) {
            return trim($result['choices'][0]['message']['content']);
        }
        
        return self::gerarDescricaoFallback($demanda);
    }
    
    /**
     * Constrói o prompt para o GPT
     */
    private static function construirPrompt(array $demanda, array $arquivos = []): string
    {
        $prompt = "TAREFA: Organize as informações abaixo como um MEMORIAL DESCRITIVO profissional de engenharia.\n\n";
        
        $prompt .= "Estruture em seções lógicas (Resumo Executivo, Características do Projeto, Requisitos Técnicos, Observações de Campo).\n";
        $prompt .= "Incorpore as legendas das fotos como observações técnicas relevantes no texto.\n";
        $prompt .= "Use linguagem técnica mas acessível.\n";
        $prompt .= "NÃO invente medidas, especificações ou detalhes técnicos.\n\n";
        
        $prompt .= "=== DADOS DO PROJETO ===\n\n";
        
        // Informações básicas
        $prompt .= "IDENTIFICAÇÃO:\n";
        $prompt .= "- Código: {$demanda['code']}\n";
        $prompt .= "- Título: {$demanda['title']}\n";
        
        if (!empty($demanda['cliente_nome'])) {
            $prompt .= "- Cliente: {$demanda['cliente_nome']}\n";
        }
        
        // Classificação
        $prompt .= "\nCLASSIFICAÇÃO:\n";
        if (!empty($demanda['work_type'])) {
            $prompt .= "- Tipo de Obra: {$demanda['work_type']}\n";
        }
        if (!empty($demanda['category'])) {
            $prompt .= "- Categoria: {$demanda['category']}\n";
        }
        if (!empty($demanda['subcategory'])) {
            $prompt .= "- Subcategoria: {$demanda['subcategory']}\n";
        }
        if (!empty($demanda['complexity'])) {
            $prompt .= "- Complexidade: {$demanda['complexity']}\n";
        }
        
        // Localização
        $prompt .= "\nLOCALIZAÇÃO:\n";
        if (!empty($demanda['city']) && !empty($demanda['state'])) {
            $prompt .= "- Município: {$demanda['city']}, {$demanda['state']}\n";
        }
        if (!empty($demanda['address'])) {
            $prompt .= "- Endereço: {$demanda['address']}\n";
        }
        
        // Características técnicas
        $prompt .= "\nCARACTERÍSTICAS TÉCNICAS:\n";
        if (!empty($demanda['area_sqm'])) {
            $prompt .= "- Área: {$demanda['area_sqm']} m²\n";
        }
        if (!empty($demanda['current_phase'])) {
            $prompt .= "- Fase Atual: {$demanda['current_phase']}\n";
        }
        
        // Orçamento e prazo
        $prompt .= "\nORÇAMENTO E PRAZO:\n";
        if (!empty($demanda['budget_min']) || !empty($demanda['budget_max'])) {
            $budget = '- Orçamento Estimado: ';
            if (!empty($demanda['budget_min'])) {
                $budget .= 'R$ ' . number_format((float)$demanda['budget_min'], 2, ',', '.');
            }
            if (!empty($demanda['budget_max'])) {
                if (!empty($demanda['budget_min'])) {
                    $budget .= ' a ';
                }
                $budget .= 'R$ ' . number_format((float)$demanda['budget_max'], 2, ',', '.');
            }
            $prompt .= $budget . "\n";
        }
        if (!empty($demanda['desired_deadline'])) {
            $prompt .= "- Prazo Desejado: {$demanda['desired_deadline']}\n";
        }
        if (!empty($demanda['urgency'])) {
            $prompt .= "- Urgência: {$demanda['urgency']}\n";
        }
        
        // Requisitos
        $prompt .= "\nREQUISITOS DO PROJETO:\n";
        if (!empty($demanda['hiring_type'])) {
            $prompt .= "- Tipo de Contratação: {$demanda['hiring_type']}\n";
        }
        if (!empty($demanda['has_project'])) {
            $prompt .= "- Possui Projeto Arquitetônico: Sim\n";
        }
        if (!empty($demanda['has_architect'])) {
            $prompt .= "- Possui Arquiteto Responsável: Sim\n";
        }
        if (!empty($demanda['wants_multiple_proposals'])) {
            $prompt .= "- Aceita Múltiplas Propostas: Sim\n";
        }
        
        // Descrição do cliente
        if (!empty($demanda['description'])) {
            $prompt .= "\nDESCRIÇÃO FORNECIDA PELO CLIENTE:\n{$demanda['description']}\n";
        }
        
        // Observações
        if (!empty($demanda['notes'])) {
            $prompt .= "\nOBSERVAÇÕES ADICIONAIS:\n{$demanda['notes']}\n";
        }
        
        // Legendas das fotos (IMPORTANTE)
        if (!empty($arquivos)) {
            $legendasComConteudo = array_filter($arquivos, fn($a) => !empty($a['caption']));
            if (!empty($legendasComConteudo)) {
                $prompt .= "\nOBSERVAÇÕES DE CAMPO (Legendas das Fotos):\n";
                foreach ($legendasComConteudo as $i => $arq) {
                    $prompt .= "- Foto " . ($i + 1) . ": {$arq['caption']}\n";
                }
            }
        }
        
        $prompt .= "\n=== INSTRUÇÕES DE FORMATAÇÃO ===\n\n";
        $prompt .= "Organize todas as informações acima em um memorial descritivo profissional.\n";
        $prompt .= "Estruture em seções claras com títulos apropriados.\n";
        $prompt .= "Incorpore as observações de campo (legendas) no texto de forma natural.\n";
        $prompt .= "Use linguagem técnica de engenharia mas acessível.\n";
        $prompt .= "Corrija erros de português.\n";
        $prompt .= "Mantenha todos os dados técnicos EXATOS.\n\n";
        $prompt .= "⛔ NÃO INVENTE: medidas, especificações, materiais, detalhes técnicos\n";
        $prompt .= "✅ PERMITIDO: reorganizar, melhorar redação, estruturar logicamente\n";
        
        return $prompt;
    }
    
    /**
     * Descrição fallback caso a API falhe
     */
    private static function gerarDescricaoFallback(array $demanda): string
    {
        $texto = "MEMORIAL DESCRITIVO\n\n";
        
        // Resumo Executivo
        if (!empty($demanda['work_type']) || !empty($demanda['city'])) {
            $texto .= "RESUMO EXECUTIVO\n\n";
            if (!empty($demanda['work_type'])) {
                $texto .= "Projeto de {$demanda['work_type']}";
                if (!empty($demanda['city']) && !empty($demanda['state'])) {
                    $texto .= " localizado em {$demanda['city']}, {$demanda['state']}";
                }
                $texto .= ".\n\n";
            }
        }
        
        // Características
        if (!empty($demanda['area_sqm']) || !empty($demanda['category'])) {
            $texto .= "CARACTERÍSTICAS DO PROJETO\n\n";
            if (!empty($demanda['area_sqm'])) {
                $texto .= "Área total: {$demanda['area_sqm']} m²\n";
            }
            if (!empty($demanda['category'])) {
                $texto .= "Categoria: {$demanda['category']}\n";
            }
            $texto .= "\n";
        }
        
        // Descrição
        if (!empty($demanda['description'])) {
            $texto .= "DESCRIÇÃO DO PROJETO\n\n";
            $texto .= $demanda['description'] . "\n\n";
        }
        
        // Observações
        if (!empty($demanda['notes'])) {
            $texto .= "OBSERVAÇÕES TÉCNICAS\n\n";
            $texto .= $demanda['notes'] . "\n\n";
        }
        
        // Se não houver nenhuma informação
        if (empty(trim($texto)) || $texto === "MEMORIAL DESCRITIVO\n\n") {
            $texto = "Memorial descritivo do projeto conforme informações fornecidas pelo cliente.";
        }
        
        return trim($texto);
    }
}
