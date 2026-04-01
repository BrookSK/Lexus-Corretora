-- Migration 003: Adiciona tipo e torna parceiro_id nullable em comissoes

ALTER TABLE `comissoes`
  MODIFY COLUMN `parceiro_id` INT NULL,
  ADD COLUMN `tipo` ENUM('recebimento','pagamento') NOT NULL DEFAULT 'recebimento' AFTER `parceiro_id`;
