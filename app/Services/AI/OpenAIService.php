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
    private const API_KEY = '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';
    private const API_URL = 'https://api.openai.com/v1/chat/completions';
    
    /**
     * Gera descrição formal da demanda usando GPT
     */
    public static function gerarDescricaoFormal(array $demanda): string
    {
        $prompt = self::construirPrompt($demanda);
        
        $data = [
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Você é um assistente de formatação de documentos. REGRAS CRÍTICAS E INVIOLÁVEIS:

1. NUNCA invente, adicione, suponha ou deduza informações
2. NUNCA mencione informações que não foram explicitamente fornecidas
3. APENAS reformate e organize o texto fornecido
4. Corrija erros de português e gramática
5. Melhore a clareza e fluidez do texto
6. Use linguagem formal e profissional
7. Mantenha o significado EXATO do original
8. Se uma informação não existe, NÃO a mencione de forma alguma
9. Legendas de fotos devem ser mantidas EXATAMENTE como fornecidas
10. NÃO adicione detalhes técnicos, características ou especificações que não foram mencionadas

Sua função é APENAS melhorar a apresentação do texto existente, não criar conteúdo novo.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.2,
            'max_tokens' => 1500
        ];
        
        $ch = curl_init(self::API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . self::API_KEY
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
    private static function construirPrompt(array $demanda): string
    {
        $prompt = "⚠️ ATENÇÃO CRÍTICA: Você deve APENAS reformatar o texto abaixo. NÃO invente, NÃO adicione, NÃO suponha NENHUMA informação nova.\n\n";
        
        $prompt .= "TAREFA: Reorganize as informações fornecidas de forma profissional e clara, mantendo EXATAMENTE o mesmo conteúdo.\n\n";
        
        $prompt .= "=== INFORMAÇÕES DA DEMANDA (USE APENAS ESTAS) ===\n\n";
        
        $prompt .= "CÓDIGO: {$demanda['code']}\n";
        $prompt .= "TÍTULO: {$demanda['title']}\n";
        
        if (!empty($demanda['cliente_nome'])) {
            $prompt .= "CLIENTE: {$demanda['cliente_nome']}\n";
        }
        
        if (!empty($demanda['work_type'])) {
            $prompt .= "TIPO DE OBRA: {$demanda['work_type']}\n";
        }
        
        if (!empty($demanda['category'])) {
            $prompt .= "CATEGORIA: {$demanda['category']}\n";
        }
        
        if (!empty($demanda['subcategory'])) {
            $prompt .= "SUBCATEGORIA: {$demanda['subcategory']}\n";
        }
        
        if (!empty($demanda['city']) && !empty($demanda['state'])) {
            $prompt .= "LOCALIZAÇÃO: {$demanda['city']}, {$demanda['state']}\n";
        }
        
        if (!empty($demanda['address'])) {
            $prompt .= "ENDEREÇO: {$demanda['address']}\n";
        }
        
        if (!empty($demanda['area_sqm'])) {
            $prompt .= "ÁREA: {$demanda['area_sqm']} m²\n";
        }
        
        if (!empty($demanda['current_phase'])) {
            $prompt .= "FASE ATUAL: {$demanda['current_phase']}\n";
        }
        
        if (!empty($demanda['budget_min']) || !empty($demanda['budget_max'])) {
            $budget = 'ORÇAMENTO: ';
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
            $prompt .= "PRAZO DESEJADO: {$demanda['desired_deadline']}\n";
        }
        
        if (!empty($demanda['urgency'])) {
            $prompt .= "URGÊNCIA: {$demanda['urgency']}\n";
        }
        
        if (!empty($demanda['complexity'])) {
            $prompt .= "COMPLEXIDADE: {$demanda['complexity']}\n";
        }
        
        if (!empty($demanda['hiring_type'])) {
            $prompt .= "TIPO DE CONTRATAÇÃO: {$demanda['hiring_type']}\n";
        }
        
        if (!empty($demanda['has_project'])) {
            $prompt .= "POSSUI PROJETO: Sim\n";
        }
        
        if (!empty($demanda['has_architect'])) {
            $prompt .= "POSSUI ARQUITETO: Sim\n";
        }
        
        if (!empty($demanda['wants_multiple_proposals'])) {
            $prompt .= "ACEITA MÚLTIPLAS PROPOSTAS: Sim\n";
        }
        
        if (!empty($demanda['description'])) {
            $prompt .= "\nDESCRIÇÃO FORNECIDA PELO CLIENTE:\n{$demanda['description']}\n";
        }
        
        if (!empty($demanda['notes'])) {
            $prompt .= "\nOBSERVAÇÕES ADICIONAIS:\n{$demanda['notes']}\n";
        }
        
        $prompt .= "\n=== INSTRUÇÕES DE FORMATAÇÃO ===\n\n";
        $prompt .= "1. Organize as informações acima em parágrafos claros\n";
        $prompt .= "2. Corrija erros de português se houver\n";
        $prompt .= "3. Melhore a clareza e fluidez\n";
        $prompt .= "4. Use linguagem formal e profissional\n";
        $prompt .= "5. Mantenha o significado EXATO\n\n";
        $prompt .= "⛔ PROIBIDO:\n";
        $prompt .= "- Inventar informações\n";
        $prompt .= "- Adicionar dados não fornecidos\n";
        $prompt .= "- Supor características ou detalhes\n";
        $prompt .= "- Mencionar informações ausentes\n";
        $prompt .= "- Adicionar especificações técnicas\n";
        $prompt .= "- Deduzir ou inferir qualquer coisa\n\n";
        $prompt .= "✅ PERMITIDO:\n";
        $prompt .= "- Reorganizar o texto\n";
        $prompt .= "- Corrigir gramática e ortografia\n";
        $prompt .= "- Melhorar clareza\n";
        $prompt .= "- Usar linguagem mais formal\n";
        
        return $prompt;
    }
    
    /**
     * Descrição fallback caso a API falhe
     */
    private static function gerarDescricaoFallback(array $demanda): string
    {
        $texto = "";
        
        // Apenas incluir informações que existem
        if (!empty($demanda['work_type'])) {
            $texto .= "Projeto de {$demanda['work_type']}";
            
            if (!empty($demanda['city']) && !empty($demanda['state'])) {
                $texto .= " localizado em {$demanda['city']}, {$demanda['state']}";
            }
            
            $texto .= ". ";
        }
        
        if (!empty($demanda['area_sqm'])) {
            $texto .= "Área de {$demanda['area_sqm']} m². ";
        }
        
        if (!empty($demanda['category'])) {
            $texto .= "Categoria: {$demanda['category']}. ";
        }
        
        if (!empty($demanda['description'])) {
            $texto .= "\n\n" . $demanda['description'];
        }
        
        if (!empty($demanda['notes'])) {
            $texto .= "\n\nObservações: " . $demanda['notes'];
        }
        
        // Se não houver nenhuma informação, retornar mensagem genérica
        if (empty($texto)) {
            $texto = "Informações detalhadas do projeto conforme especificado.";
        }
        
        return trim($texto);
    }
}
