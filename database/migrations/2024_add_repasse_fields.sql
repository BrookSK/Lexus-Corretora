-- Adicionar campos para sistema de repasse e notificações

-- Adicionar campo para indicar se demanda é de repasse
ALTER TABLE `demandas` 
ADD COLUMN `is_repasse` TINYINT(1) DEFAULT 0 AFTER `parceiro_originador_id`,
ADD INDEX `idx_demandas_repasse` (`is_repasse`);

-- Adicionar campo de status de revisão para demandas repassadas editadas
ALTER TABLE `demandas`
ADD COLUMN `repasse_status` ENUM('pendente','em_revisao','aprovado','rejeitado') DEFAULT NULL AFTER `is_repasse`;

-- Criar tabela de eventos de notificação
CREATE TABLE IF NOT EXISTS `notificacao_eventos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT,
    `is_active` TINYINT(1) DEFAULT 1,
    `destinatarios` JSON COMMENT 'Array de tipos: cliente, admin, parceiro',
    `template_message` TEXT,
    `available_variables` JSON COMMENT 'Variáveis disponíveis para o template',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_ne_slug` (`slug`),
    INDEX `idx_ne_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir eventos padrão do sistema
INSERT INTO `notificacao_eventos` (`slug`, `name`, `description`, `destinatarios`, `template_message`, `available_variables`) VALUES
('nova_demanda', 'Nova Demanda Recebida', 'Quando uma nova demanda é criada no sistema', '["admin"]', 'Nova demanda recebida: {{codigo}} - {{titulo}}', '["codigo","titulo","cliente","urgencia","categoria"]'),
('demanda_repassada', 'Demanda Repassada por Parceiro', 'Quando um parceiro indica uma demanda', '["admin"]', 'Nova indicação recebida de {{parceiro}}: {{codigo}} - {{titulo}}', '["codigo","titulo","parceiro","categoria","cidade"]'),
('proposta_recebida', 'Proposta Recebida', 'Quando uma proposta é enviada para uma demanda', '["admin","cliente"]', 'Nova proposta recebida para {{codigo}}: R$ {{valor}}', '["codigo","valor","parceiro","prazo"]'),
('status_alterado', 'Status da Demanda Alterado', 'Quando o status de uma demanda muda', '["cliente"]', 'Status da demanda {{codigo}} alterado para: {{status}}', '["codigo","status","titulo"]'),
('repasse_aprovado', 'Repasse Aprovado', 'Quando uma demanda repassada é aprovada', '["parceiro"]', 'Sua indicação {{codigo}} foi aprovada pela equipe!', '["codigo","titulo"]'),
('repasse_em_revisao', 'Repasse em Revisão', 'Quando uma demanda repassada editada precisa de revisão', '["admin"]', 'Demanda repassada {{codigo}} editada e aguarda revisão', '["codigo","titulo","parceiro"]');

-- Adicionar campo para armazenar contagem de notificações não lidas
ALTER TABLE `users` 
ADD COLUMN `unread_notifications` INT DEFAULT 0 AFTER `last_login_at`;

ALTER TABLE `clientes` 
ADD COLUMN `unread_notifications` INT DEFAULT 0 AFTER `last_login_at`;

ALTER TABLE `parceiros` 
ADD COLUMN `unread_notifications` INT DEFAULT 0 AFTER `last_login_at`;
