-- Migration: Adicionar coluna deleted_at na tabela contratos
-- Data: 2024-04-08
-- Descrição: Adiciona suporte para soft delete na tabela de contratos

ALTER TABLE contratos 
ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at;

-- Criar índice para melhorar performance de queries que filtram por deleted_at
CREATE INDEX idx_contratos_deleted_at ON contratos(deleted_at);
