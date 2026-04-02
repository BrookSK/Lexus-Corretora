-- Migration 004: Add avatar column to users, clientes and parceiros
ALTER TABLE `users`     ADD COLUMN IF NOT EXISTS `avatar` VARCHAR(255) NULL DEFAULT NULL AFTER `email`;
ALTER TABLE `clientes`  ADD COLUMN IF NOT EXISTS `avatar` VARCHAR(255) NULL DEFAULT NULL AFTER `email`;
ALTER TABLE `parceiros` ADD COLUMN IF NOT EXISTS `avatar` VARCHAR(255) NULL DEFAULT NULL AFTER `email`;
