-- Tabela de configurações de webhooks por evento
CREATE TABLE IF NOT EXISTS `webhook_configs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `evento` VARCHAR(80) NOT NULL,
    `url` VARCHAR(500) NOT NULL,
    `ativo` TINYINT(1) NOT NULL DEFAULT 1,
    `secret` VARCHAR(255),
    `descricao` VARCHAR(255),
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_wh_evento` (`evento`),
    INDEX `idx_wh_ativo` (`ativo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de logs de disparos de webhook
CREATE TABLE IF NOT EXISTS `webhook_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `webhook_config_id` INT,
    `evento` VARCHAR(80) NOT NULL,
    `url` VARCHAR(500) NOT NULL,
    `payload` JSON,
    `status_code` INT,
    `response` TEXT,
    `sucesso` TINYINT(1) DEFAULT 0,
    `erro` TEXT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`webhook_config_id`) REFERENCES `webhook_configs`(`id`) ON DELETE SET NULL,
    INDEX `idx_whl_evento` (`evento`),
    INDEX `idx_whl_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
