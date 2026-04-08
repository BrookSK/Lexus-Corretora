-- Adicionar campos para armazenar rascunhos de PDF editáveis
-- Permite que a equipe edite o conteúdo do PDF antes de finalizar

ALTER TABLE `demandas` 
ADD COLUMN `pdf_draft_content` LONGTEXT COMMENT 'Conteúdo editável do PDF em JSON' AFTER `internal_notes`,
ADD COLUMN `pdf_draft_updated_at` DATETIME COMMENT 'Data da última edição do rascunho' AFTER `pdf_draft_content`,
ADD COLUMN `pdf_finalized_path` VARCHAR(255) COMMENT 'Caminho do PDF finalizado' AFTER `pdf_draft_updated_at`,
ADD COLUMN `pdf_finalized_at` DATETIME COMMENT 'Data de finalização do PDF' AFTER `pdf_finalized_path`,
ADD COLUMN `pdf_finalized_by` INT COMMENT 'ID do usuário que finalizou' AFTER `pdf_finalized_at`;

-- Índice para buscar PDFs finalizados
ALTER TABLE `demandas` 
ADD INDEX `idx_demandas_pdf_finalized` (`pdf_finalized_at`);
