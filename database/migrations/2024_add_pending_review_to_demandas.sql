-- Adiciona campos para sistema de revisão de edições do cliente
-- Executar: mysql -u [user] -p [database] < 2024_add_pending_review_to_demandas.sql

ALTER TABLE demandas 
ADD COLUMN pending_review TINYINT(1) DEFAULT 0 COMMENT 'Indica se há alterações pendentes de revisão',
ADD COLUMN changes_log TEXT NULL COMMENT 'Log JSON das alterações feitas pelo cliente';
