-- Adicionar coluna caption na tabela demanda_arquivos
ALTER TABLE demanda_arquivos ADD COLUMN caption TEXT NULL AFTER file_path;
