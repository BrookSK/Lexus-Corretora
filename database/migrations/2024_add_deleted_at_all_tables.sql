-- Migration Consolidada: Adicionar coluna deleted_at em múltiplas tabelas
-- Data: 2024-04-08
-- Descrição: Adiciona suporte para soft delete nas tabelas comissoes e contratos

-- ══════════════════════════════════════════════════════════════════════════
-- TABELA: comissoes
-- ══════════════════════════════════════════════════════════════════════════

-- Adicionar coluna deleted_at se não existir
SET @dbname = DATABASE();
SET @tablename = 'comissoes';
SET @columnname = 'deleted_at';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' DATETIME NULL DEFAULT NULL AFTER updated_at')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Criar índice se não existir
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (index_name = 'idx_comissoes_deleted_at')
  ) > 0,
  'SELECT 1',
  CONCAT('CREATE INDEX idx_comissoes_deleted_at ON ', @tablename, '(deleted_at)')
));
PREPARE createIndexIfNotExists FROM @preparedStatement;
EXECUTE createIndexIfNotExists;
DEALLOCATE PREPARE createIndexIfNotExists;

-- ══════════════════════════════════════════════════════════════════════════
-- TABELA: contratos
-- ══════════════════════════════════════════════════════════════════════════

-- Adicionar coluna deleted_at se não existir
SET @tablename = 'contratos';
SET @columnname = 'deleted_at';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' DATETIME NULL DEFAULT NULL AFTER updated_at')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Criar índice se não existir
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (index_name = 'idx_contratos_deleted_at')
  ) > 0,
  'SELECT 1',
  CONCAT('CREATE INDEX idx_contratos_deleted_at ON ', @tablename, '(deleted_at)')
));
PREPARE createIndexIfNotExists FROM @preparedStatement;
EXECUTE createIndexIfNotExists;
DEALLOCATE PREPARE createIndexIfNotExists;

-- ══════════════════════════════════════════════════════════════════════════
-- FIM DA MIGRATION
-- ══════════════════════════════════════════════════════════════════════════
