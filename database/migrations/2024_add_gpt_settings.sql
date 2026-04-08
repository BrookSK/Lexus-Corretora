-- Migration: Adicionar configurações do GPT
-- Data: 2024-04-08
-- Descrição: Adiciona configurações padrão para integração com OpenAI GPT

-- Inserir configurações padrão do GPT (se não existirem)
INSERT IGNORE INTO settings (`key`, `value`, `created_at`, `updated_at`)
VALUES
('gpt.api_key', '', NOW(), NOW()),
('gpt.model', 'gpt-4', NOW(), NOW()),
('gpt.temperature', '0.2', NOW(), NOW()),
('gpt.max_tokens', '1500', NOW(), NOW());
