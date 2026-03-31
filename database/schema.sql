-- ============================================================
-- LEXUS CORRETORA — Schema Inicial
-- Banco: MySQL 8.0+ / MariaDB 10.6+
-- Charset: utf8mb4_unicode_ci
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ─── CORE ────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `migrations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `file_name` VARCHAR(255) NOT NULL UNIQUE,
    `executed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(190) NOT NULL UNIQUE,
    `value` LONGTEXT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_settings_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `permissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `name` VARCHAR(150) NOT NULL,
    `group_name` VARCHAR(80),
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `role_permissions` (
    `role_id` INT NOT NULL,
    `permission_id` INT NOT NULL,
    PRIMARY KEY (`role_id`, `permission_id`),
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── USERS (Equipe Interna) ──────────────────────────────────

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(190) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `avatar` VARCHAR(255),
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `two_factor_secret` VARCHAR(255),
    `two_factor_enabled` TINYINT(1) NOT NULL DEFAULT 0,
    `last_login_at` DATETIME,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME,
    INDEX `idx_users_email` (`email`),
    INDEX `idx_users_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_roles` (
    `user_id` INT NOT NULL,
    `role_id` INT NOT NULL,
    PRIMARY KEY (`user_id`, `role_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── CLIENTES ────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `clientes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(190) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(30),
    `whatsapp` VARCHAR(30),
    `company` VARCHAR(150),
    `document` VARCHAR(20),
    `avatar` VARCHAR(255),
    `city` VARCHAR(100),
    `state` VARCHAR(50),
    `country` VARCHAR(50) DEFAULT 'Brasil',
    `address` TEXT,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `two_factor_secret` VARCHAR(255),
    `two_factor_enabled` TINYINT(1) NOT NULL DEFAULT 0,
    `language` VARCHAR(5) DEFAULT 'pt-BR',
    `currency` VARCHAR(3) DEFAULT 'BRL',
    `last_login_at` DATETIME,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME,
    INDEX `idx_clientes_email` (`email`),
    INDEX `idx_clientes_active` (`is_active`),
    INDEX `idx_clientes_city` (`city`),
    INDEX `idx_clientes_state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── EMPRESAS PARCEIRAS ──────────────────────────────────────

CREATE TABLE IF NOT EXISTS `empresas_parceiras` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `razao_social` VARCHAR(200),
    `nome_fantasia` VARCHAR(200) NOT NULL,
    `cnpj` VARCHAR(20) UNIQUE,
    `email` VARCHAR(190),
    `phone` VARCHAR(30),
    `whatsapp` VARCHAR(30),
    `website` VARCHAR(255),
    `instagram` VARCHAR(150),
    `linkedin` VARCHAR(150),
    `logo` VARCHAR(255),
    `description` TEXT,
    `city` VARCHAR(100),
    `state` VARCHAR(50),
    `country` VARCHAR(50) DEFAULT 'Brasil',
    `address` TEXT,
    `team_size` VARCHAR(30),
    `years_in_market` INT,
    `has_own_team` TINYINT(1) DEFAULT 0,
    `average_ticket_min` DECIMAL(15,2),
    `average_ticket_max` DECIMAL(15,2),
    `currency_code` VARCHAR(3) DEFAULT 'BRL',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME,
    INDEX `idx_emp_parceiras_cnpj` (`cnpj`),
    INDEX `idx_emp_parceiras_city` (`city`),
    INDEX `idx_emp_parceiras_state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── PARCEIROS ───────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `parceiros` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `empresa_id` INT,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(190) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(30),
    `whatsapp` VARCHAR(30),
    `document` VARCHAR(20),
    `type` ENUM('arquiteto','construtora','engenheiro','empreiteira','prestador','fornecedor') NOT NULL DEFAULT 'prestador',
    `avatar` VARCHAR(255),
    `crea_cau` VARCHAR(50),
    `specialties` JSON,
    `service_areas` JSON,
    `service_cities` JSON,
    `service_states` JSON,
    `portfolio_url` VARCHAR(255),
    `bio` TEXT,
    `status` ENUM('cadastrado','pendente_analise','em_qualificacao','aprovado','vetriks_ativo','reprovado','suspenso','inativo') NOT NULL DEFAULT 'cadastrado',
    `score` INT DEFAULT 0,
    `is_vetriks` TINYINT(1) NOT NULL DEFAULT 0,
    `vetriks_since` DATE,
    `accepts_referral` TINYINT(1) DEFAULT 1,
    `referral_commission_pct` DECIMAL(5,2),
    `availability` ENUM('disponivel','parcial','indisponivel') DEFAULT 'disponivel',
    `response_rate` DECIMAL(5,2) DEFAULT 0,
    `close_rate` DECIMAL(5,2) DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `two_factor_secret` VARCHAR(255),
    `two_factor_enabled` TINYINT(1) NOT NULL DEFAULT 0,
    `language` VARCHAR(5) DEFAULT 'pt-BR',
    `currency` VARCHAR(3) DEFAULT 'BRL',
    `last_login_at` DATETIME,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME,
    FOREIGN KEY (`empresa_id`) REFERENCES `empresas_parceiras`(`id`) ON DELETE SET NULL,
    INDEX `idx_parceiros_email` (`email`),
    INDEX `idx_parceiros_type` (`type`),
    INDEX `idx_parceiros_status` (`status`),
    INDEX `idx_parceiros_vetriks` (`is_vetriks`),
    INDEX `idx_parceiros_score` (`score`),
    INDEX `idx_parceiros_empresa` (`empresa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── QUALIFICAÇÃO / VETRIKS ──────────────────────────────────

CREATE TABLE IF NOT EXISTS `parceiro_qualificacoes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `parceiro_id` INT NOT NULL,
    `evaluator_id` INT,
    `overall_score` INT DEFAULT 0,
    `status` ENUM('pendente','em_analise','aprovado','reprovado','revisao') NOT NULL DEFAULT 'pendente',
    `parecer` TEXT,
    `vetriks_granted` TINYINT(1) DEFAULT 0,
    `evaluated_at` DATETIME,
    `next_review_at` DATETIME,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`parceiro_id`) REFERENCES `parceiros`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`evaluator_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_pq_parceiro` (`parceiro_id`),
    INDEX `idx_pq_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `parceiro_qualificacao_itens` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `qualificacao_id` INT NOT NULL,
    `criterio` VARCHAR(150) NOT NULL,
    `score` INT DEFAULT 0,
    `max_score` INT DEFAULT 10,
    `notes` TEXT,
    FOREIGN KEY (`qualificacao_id`) REFERENCES `parceiro_qualificacoes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `parceiro_certificacoes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `parceiro_id` INT NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `issuer` VARCHAR(200),
    `issued_at` DATE,
    `expires_at` DATE,
    `file_path` VARCHAR(500),
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`parceiro_id`) REFERENCES `parceiros`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `parceiro_documentos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `parceiro_id` INT NOT NULL,
    `type` VARCHAR(80) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_size` INT,
    `mime_type` VARCHAR(100),
    `is_verified` TINYINT(1) DEFAULT 0,
    `verified_by` INT,
    `verified_at` DATETIME,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`parceiro_id`) REFERENCES `parceiros`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`verified_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `parceiro_areas_atuacao` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `parceiro_id` INT NOT NULL,
    `area` VARCHAR(100) NOT NULL,
    FOREIGN KEY (`parceiro_id`) REFERENCES `parceiros`(`id`) ON DELETE CASCADE,
    INDEX `idx_paa_parceiro` (`parceiro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `parceiro_regioes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `parceiro_id` INT NOT NULL,
    `city` VARCHAR(100),
    `state` VARCHAR(50),
    `region` VARCHAR(100),
    FOREIGN KEY (`parceiro_id`) REFERENCES `parceiros`(`id`) ON DELETE CASCADE,
    INDEX `idx_pr_parceiro` (`parceiro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── TAGS ────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `tags` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(80) NOT NULL UNIQUE,
    `color` VARCHAR(7) DEFAULT '#B8945A',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `taggables` (
    `tag_id` INT NOT NULL,
    `taggable_id` INT NOT NULL,
    `taggable_type` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`tag_id`, `taggable_id`, `taggable_type`),
    FOREIGN KEY (`tag_id`) REFERENCES `tags`(`id`) ON DELETE CASCADE,
    INDEX `idx_taggables_type` (`taggable_type`, `taggable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── DEMANDAS / OPORTUNIDADES ────────────────────────────────

CREATE TABLE IF NOT EXISTS `demandas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(20) NOT NULL UNIQUE,
    `origin` ENUM('cliente','parceiro','arquiteto','equipe','lead','importacao') NOT NULL DEFAULT 'cliente',
    `cliente_id` INT,
    `parceiro_originador_id` INT,
    `assigned_to` INT,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `category` VARCHAR(100),
    `subcategory` VARCHAR(100),
    `work_type` VARCHAR(100),
    `city` VARCHAR(100),
    `state` VARCHAR(50),
    `country` VARCHAR(50) DEFAULT 'Brasil',
    `address` TEXT,
    `area_sqm` DECIMAL(10,2),
    `current_phase` VARCHAR(80),
    `desired_deadline` DATE,
    `budget_min` DECIMAL(15,2),
    `budget_max` DECIMAL(15,2),
    `currency_code` VARCHAR(3) DEFAULT 'BRL',
    `urgency` ENUM('baixa','media','alta','critica') DEFAULT 'media',
    `complexity` ENUM('simples','moderada','complexa','muito_complexa') DEFAULT 'moderada',
    `has_project` TINYINT(1) DEFAULT 0,
    `has_architect` TINYINT(1) DEFAULT 0,
    `wants_multiple_proposals` TINYINT(1) DEFAULT 1,
    `hiring_type` VARCHAR(80),
    `notes` TEXT,
    `internal_notes` TEXT,
    `status` ENUM('novo','em_triagem','em_estruturacao','pronto_repasse','distribuido','aguardando_respostas','recebendo_propostas','em_curadoria','apresentado_cliente','em_negociacao','contrato_formalizacao','fechado_ganho','fechado_perda','pausado','cancelado') NOT NULL DEFAULT 'novo',
    `priority` ENUM('baixa','normal','alta','urgente') DEFAULT 'normal',
    `score` INT DEFAULT 0,
    `ideal_partner_profile` TEXT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME,
    FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`parceiro_originador_id`) REFERENCES `parceiros`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_demandas_code` (`code`),
    INDEX `idx_demandas_status` (`status`),
    INDEX `idx_demandas_cliente` (`cliente_id`),
    INDEX `idx_demandas_origin` (`origin`),
    INDEX `idx_demandas_city` (`city`),
    INDEX `idx_demandas_state` (`state`),
    INDEX `idx_demandas_urgency` (`urgency`),
    INDEX `idx_demandas_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `demanda_arquivos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `demanda_id` INT NOT NULL,
    `type` VARCHAR(50),
    `name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_size` INT,
    `mime_type` VARCHAR(100),
    `uploaded_by_type` ENUM('cliente','parceiro','equipe') DEFAULT 'cliente',
    `uploaded_by_id` INT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`demanda_id`) REFERENCES `demandas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `demanda_tags` (
    `demanda_id` INT NOT NULL,
    `tag_id` INT NOT NULL,
    PRIMARY KEY (`demanda_id`, `tag_id`),
    FOREIGN KEY (`demanda_id`) REFERENCES `demandas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`tag_id`) REFERENCES `tags`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── DISTRIBUIÇÃO / REPASSE ──────────────────────────────────

CREATE TABLE IF NOT EXISTS `oportunidade_distribuicoes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `demanda_id` INT NOT NULL,
    `distributed_by` INT,
    `distribution_type` ENUM('manual','automatica') DEFAULT 'manual',
    `criteria_snapshot` JSON,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`demanda_id`) REFERENCES `demandas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`distributed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `oportunidade_destinatarios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `distribuicao_id` INT NOT NULL,
    `parceiro_id` INT NOT NULL,
    `status` ENUM('enviado','visualizado','interessado','recusado','sem_resposta','proposta_enviada') NOT NULL DEFAULT 'enviado',
    `sent_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `viewed_at` DATETIME,
    `responded_at` DATETIME,
    `sla_deadline` DATETIME,
    `notes` TEXT,
    FOREIGN KEY (`distribuicao_id`) REFERENCES `oportunidade_distribuicoes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`parceiro_id`) REFERENCES `parceiros`(`id`) ON DELETE CASCADE,
    INDEX `idx_od_parceiro` (`parceiro_id`),
    INDEX `idx_od_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `oportunidade_interesses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `demanda_id` INT NOT NULL,
    `parceiro_id` INT NOT NULL,
    `type` ENUM('aceitar','recusar','mais_info') NOT NULL,
    `message` TEXT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`demanda_id`) REFERENCES `demandas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`parceiro_id`) REFERENCES `parceiros`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `oportunidade_timeline` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `demanda_id` INT NOT NULL,
    `event_type` VARCHAR(80) NOT NULL,
    `description` TEXT,
    `actor_type` ENUM('sistema','equipe','cliente','parceiro') DEFAULT 'sistema',
    `actor_id` INT,
    `metadata` JSON,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`demanda_id`) REFERENCES `demandas`(`id`) ON DELETE CASCADE,
    INDEX `idx_ot_demanda` (`demanda_id`),
    INDEX `idx_ot_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── PROPOSTAS ───────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `propostas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `demanda_id` INT NOT NULL,
    `parceiro_id` INT NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `currency_code` VARCHAR(3) DEFAULT 'BRL',
    `deadline_days` INT,
    `deadline_date` DATE,
    `description` TEXT NOT NULL,
    `differentials` TEXT,
    `conditions` TEXT,
    `validity_days` INT DEFAULT 30,
    `valid_until` DATE,
    `status` ENUM('rascunho','enviada','em_analise','shortlist','descartada','selecionada','convertida','perdida') NOT NULL DEFAULT 'rascunho',
    `internal_score` INT DEFAULT 0,
    `internal_notes` TEXT,
    `is_shortlisted` TINYINT(1) DEFAULT 0,
    `is_recommended` TINYINT(1) DEFAULT 0,
    `presented_to_client` TINYINT(1) DEFAULT 0,
    `presented_at` DATETIME,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`demanda_id`) REFERENCES `demandas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`parceiro_id`) REFERENCES `parceiros`(`id`) ON DELETE CASCADE,
    INDEX `idx_propostas_demanda` (`demanda_id`),
    INDEX `idx_propostas_parceiro` (`parceiro_id`),
    INDEX `idx_propostas_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `proposta_arquivos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `proposta_id` INT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_size` INT,
    `mime_type` VARCHAR(100),
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`proposta_id`) REFERENCES `propostas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `proposta_notas_internas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `proposta_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `note` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`proposta_id`) REFERENCES `propostas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── CONTRATOS ───────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `contratos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `demanda_id` INT NOT NULL,
    `proposta_id` INT,
    `cliente_id` INT NOT NULL,
    `parceiro_id` INT NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `currency_code` VARCHAR(3) DEFAULT 'BRL',
    `status` ENUM('em_formalizacao','formalizado','pendente_confirmacao','cancelado') NOT NULL DEFAULT 'em_formalizacao',
    `formalized_at` DATE,
    `notes` TEXT,
    `internal_notes` TEXT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`demanda_id`) REFERENCES `demandas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`proposta_id`) REFERENCES `propostas`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`parceiro_id`) REFERENCES `parceiros`(`id`) ON DELETE CASCADE,
    INDEX `idx_contratos_demanda` (`demanda_id`),
    INDEX `idx_contratos_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `contrato_arquivos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `contrato_id` INT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_size` INT,
    `mime_type` VARCHAR(100),
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`contrato_id`) REFERENCES `contratos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── COMISSÕES ───────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `comissoes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `demanda_id` INT NOT NULL,
    `contrato_id` INT,
    `parceiro_id` INT NOT NULL,
    `cliente_id` INT,
    `base_amount` DECIMAL(15,2) NOT NULL,
    `commission_pct` DECIMAL(5,2) NOT NULL,
    `commission_amount` DECIMAL(15,2) NOT NULL,
    `currency_code` VARCHAR(3) DEFAULT 'BRL',
    `status` ENUM('prevista','aguardando_confirmacao','confirmada','faturada','recebida','atrasada','cancelada') NOT NULL DEFAULT 'prevista',
    `expected_date` DATE,
    `received_date` DATE,
    `notes` TEXT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`demanda_id`) REFERENCES `demandas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`contrato_id`) REFERENCES `contratos`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`parceiro_id`) REFERENCES `parceiros`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE SET NULL,
    INDEX `idx_comissoes_status` (`status`),
    INDEX `idx_comissoes_parceiro` (`parceiro_id`),
    INDEX `idx_comissoes_demanda` (`demanda_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `comissao_eventos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `comissao_id` INT NOT NULL,
    `event_type` VARCHAR(80) NOT NULL,
    `description` TEXT,
    `user_id` INT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`comissao_id`) REFERENCES `comissoes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── COMUNICAÇÃO ─────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `conversas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `subject` VARCHAR(255),
    `demanda_id` INT,
    `type` ENUM('cliente_lexus','parceiro_lexus','interna') NOT NULL DEFAULT 'cliente_lexus',
    `status` ENUM('aberta','fechada','arquivada') DEFAULT 'aberta',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`demanda_id`) REFERENCES `demandas`(`id`) ON DELETE SET NULL,
    INDEX `idx_conversas_type` (`type`),
    INDEX `idx_conversas_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `conversa_participantes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `conversa_id` INT NOT NULL,
    `participant_type` ENUM('equipe','cliente','parceiro') NOT NULL,
    `participant_id` INT NOT NULL,
    `last_read_at` DATETIME,
    FOREIGN KEY (`conversa_id`) REFERENCES `conversas`(`id`) ON DELETE CASCADE,
    INDEX `idx_cp_conversa` (`conversa_id`),
    INDEX `idx_cp_participant` (`participant_type`, `participant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mensagens` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `conversa_id` INT NOT NULL,
    `sender_type` ENUM('equipe','cliente','parceiro','sistema') NOT NULL,
    `sender_id` INT,
    `body` TEXT NOT NULL,
    `is_system` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`conversa_id`) REFERENCES `conversas`(`id`) ON DELETE CASCADE,
    INDEX `idx_mensagens_conversa` (`conversa_id`),
    INDEX `idx_mensagens_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mensagem_arquivos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `mensagem_id` INT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_size` INT,
    `mime_type` VARCHAR(100),
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`mensagem_id`) REFERENCES `mensagens`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── NOTIFICAÇÕES ────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `notificacoes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `recipient_type` ENUM('equipe','cliente','parceiro') NOT NULL,
    `recipient_id` INT NOT NULL,
    `type` VARCHAR(80) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `body` TEXT,
    `link` VARCHAR(500),
    `is_read` TINYINT(1) DEFAULT 0,
    `read_at` DATETIME,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_notif_recipient` (`recipient_type`, `recipient_id`),
    INDEX `idx_notif_read` (`is_read`),
    INDEX `idx_notif_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `notificacao_entregas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `notificacao_id` INT NOT NULL,
    `channel` ENUM('painel','email','whatsapp') NOT NULL,
    `status` ENUM('pendente','enviado','falha') DEFAULT 'pendente',
    `sent_at` DATETIME,
    `error` TEXT,
    FOREIGN KEY (`notificacao_id`) REFERENCES `notificacoes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── CRM / LEADS ────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `leads` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(190),
    `phone` VARCHAR(30),
    `company` VARCHAR(150),
    `origin` VARCHAR(80),
    `assigned_to` INT,
    `status` ENUM('novo','contatado','qualificado','convertido','perdido') DEFAULT 'novo',
    `notes` TEXT,
    `converted_to_cliente_id` INT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`converted_to_cliente_id`) REFERENCES `clientes`(`id`) ON DELETE SET NULL,
    INDEX `idx_leads_status` (`status`),
    INDEX `idx_leads_origin` (`origin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lead_origens` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── TAREFAS / FOLLOW-UPS ───────────────────────────────────

CREATE TABLE IF NOT EXISTS `tarefas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `assigned_to` INT,
    `related_type` VARCHAR(50),
    `related_id` INT,
    `priority` ENUM('baixa','normal','alta','urgente') DEFAULT 'normal',
    `status` ENUM('pendente','em_andamento','concluida','cancelada') DEFAULT 'pendente',
    `due_date` DATETIME,
    `completed_at` DATETIME,
    `created_by` INT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_tarefas_assigned` (`assigned_to`),
    INDEX `idx_tarefas_status` (`status`),
    INDEX `idx_tarefas_due` (`due_date`),
    INDEX `idx_tarefas_related` (`related_type`, `related_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `followups` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `related_type` VARCHAR(50) NOT NULL,
    `related_id` INT NOT NULL,
    `user_id` INT,
    `type` ENUM('nota','ligacao','email','reuniao','outro') DEFAULT 'nota',
    `description` TEXT NOT NULL,
    `scheduled_at` DATETIME,
    `completed_at` DATETIME,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_followups_related` (`related_type`, `related_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `atividades` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `entity_type` VARCHAR(50) NOT NULL,
    `entity_id` INT NOT NULL,
    `event` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `actor_type` ENUM('sistema','equipe','cliente','parceiro') DEFAULT 'sistema',
    `actor_id` INT,
    `metadata` JSON,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_atividades_entity` (`entity_type`, `entity_id`),
    INDEX `idx_atividades_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `observacoes_internas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `entity_type` VARCHAR(50) NOT NULL,
    `entity_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `note` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_obs_entity` (`entity_type`, `entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── SISTEMA / LOGS ──────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `system_errors` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `error_id` VARCHAR(20) NOT NULL UNIQUE,
    `http_code` INT,
    `type` VARCHAR(150),
    `message` TEXT,
    `stack_trace` LONGTEXT,
    `url` VARCHAR(500),
    `ip` VARCHAR(45),
    `user_agent` TEXT,
    `user_id` INT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_se_error_id` (`error_id`),
    INDEX `idx_se_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `actor_type` ENUM('equipe','cliente','parceiro','sistema') NOT NULL,
    `actor_id` INT,
    `action` VARCHAR(100) NOT NULL,
    `entity_type` VARCHAR(50),
    `entity_id` INT,
    `payload` JSON,
    `ip` VARCHAR(45),
    `user_agent` TEXT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_al_actor` (`actor_type`, `actor_id`),
    INDEX `idx_al_entity` (`entity_type`, `entity_id`),
    INDEX `idx_al_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `auth_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_type` ENUM('equipe','cliente','parceiro') NOT NULL,
    `user_id` INT,
    `email` VARCHAR(190),
    `action` ENUM('login','logout','login_failed','password_reset','2fa_enabled','2fa_disabled') NOT NULL,
    `ip` VARCHAR(45),
    `user_agent` TEXT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_authl_user` (`user_type`, `user_id`),
    INDEX `idx_authl_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_resets` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_type` ENUM('equipe','cliente','parceiro') NOT NULL,
    `email` VARCHAR(190) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `expires_at` DATETIME NOT NULL,
    `used_at` DATETIME,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_pr_token` (`token`),
    INDEX `idx_pr_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `type` VARCHAR(100) NOT NULL,
    `payload` JSON,
    `status` ENUM('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
    `attempts` INT DEFAULT 0,
    `error` TEXT,
    `run_at` DATETIME,
    `started_at` DATETIME,
    `finished_at` DATETIME,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_jobs_status` (`status`),
    INDEX `idx_jobs_run_at` (`run_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── CONTEÚDO / SITE ────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `paginas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `title_pt` VARCHAR(255),
    `title_en` VARCHAR(255),
    `title_es` VARCHAR(255),
    `content_pt` LONGTEXT,
    `content_en` LONGTEXT,
    `content_es` LONGTEXT,
    `is_published` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `seo_meta` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `page_slug` VARCHAR(100) NOT NULL UNIQUE,
    `meta_title` VARCHAR(255),
    `meta_description` TEXT,
    `og_title` VARCHAR(255),
    `og_description` TEXT,
    `og_image` VARCHAR(500),
    `canonical_url` VARCHAR(500),
    `no_index` TINYINT(1) DEFAULT 0,
    `schema_json` JSON,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `faq_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category` VARCHAR(80),
    `question_pt` TEXT NOT NULL,
    `question_en` TEXT,
    `question_es` TEXT,
    `answer_pt` TEXT NOT NULL,
    `answer_en` TEXT,
    `answer_es` TEXT,
    `sort_order` INT DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cookie_consents` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_type` ENUM('equipe','cliente','parceiro','visitante') DEFAULT 'visitante',
    `user_id` INT,
    `ip` VARCHAR(45),
    `necessary` TINYINT(1) DEFAULT 1,
    `analytics` TINYINT(1) DEFAULT 0,
    `marketing` TINYINT(1) DEFAULT 0,
    `preferences` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── ANEXOS GERAIS ───────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `anexos_gerais` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `entity_type` VARCHAR(50) NOT NULL,
    `entity_id` INT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_size` INT,
    `mime_type` VARCHAR(100),
    `uploaded_by_type` ENUM('equipe','cliente','parceiro') DEFAULT 'equipe',
    `uploaded_by_id` INT,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_ag_entity` (`entity_type`, `entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
