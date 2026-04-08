-- Verificar e adicionar coluna caption se não existir
ALTER TABLE demanda_arquivos 
ADD COLUMN IF NOT EXISTS caption TEXT NULL AFTER file_path;

-- Verificar estrutura atual (as colunas devem ser):
-- id, demanda_id, type, name, file_path, caption, file_size, mime_type, uploaded_by_type, uploaded_by_id, created_at
