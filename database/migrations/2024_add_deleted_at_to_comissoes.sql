-- Migration: Adicionar coluna deleted_at na tabela comissoes
-- Data: 2024-04-08
-- Descrição: Adiciona suporte para soft delete na tabela de comissões

ALTER TABLE comissoes 
ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at;

-- Criar índice para melhorar performance de queries que filtram por deleted_at
CREATE INDEX idx_comissoes_deleted_at ON comissoes(deleted_at);
