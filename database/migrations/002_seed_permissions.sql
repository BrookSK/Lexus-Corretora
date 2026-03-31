-- Migration 002: Seed de permissões iniciais

INSERT IGNORE INTO `permissions` (`slug`, `name`, `group_name`) VALUES
-- Dashboard
('dashboard.view', 'Ver Dashboard', 'Dashboard'),
-- Clientes
('clientes.view', 'Ver Clientes', 'Clientes'),
('clientes.create', 'Criar Clientes', 'Clientes'),
('clientes.edit', 'Editar Clientes', 'Clientes'),
('clientes.delete', 'Excluir Clientes', 'Clientes'),
('clientes.impersonate', 'Impersonar Clientes', 'Clientes'),
-- Parceiros
('parceiros.view', 'Ver Parceiros', 'Parceiros'),
('parceiros.create', 'Criar Parceiros', 'Parceiros'),
('parceiros.edit', 'Editar Parceiros', 'Parceiros'),
('parceiros.delete', 'Excluir Parceiros', 'Parceiros'),
('parceiros.qualify', 'Qualificar Parceiros', 'Parceiros'),
-- Demandas
('demandas.view', 'Ver Demandas', 'Demandas'),
('demandas.create', 'Criar Demandas', 'Demandas'),
('demandas.edit', 'Editar Demandas', 'Demandas'),
('demandas.delete', 'Excluir Demandas', 'Demandas'),
('demandas.distribute', 'Distribuir Demandas', 'Demandas'),
-- Propostas
('propostas.view', 'Ver Propostas', 'Propostas'),
('propostas.manage', 'Gerenciar Propostas', 'Propostas'),
-- Contratos
('contratos.view', 'Ver Contratos', 'Contratos'),
('contratos.create', 'Criar Contratos', 'Contratos'),
('contratos.edit', 'Editar Contratos', 'Contratos'),
-- Comissões
('comissoes.view', 'Ver Comissões', 'Comissões'),
('comissoes.create', 'Criar Comissões', 'Comissões'),
('comissoes.edit', 'Editar Comissões', 'Comissões'),
-- Qualificação
('qualificacao.view', 'Ver Qualificação', 'Qualificação'),
('qualificacao.manage', 'Gerenciar Qualificação', 'Qualificação'),
-- CRM
('crm.view', 'Ver CRM', 'CRM'),
('crm.manage', 'Gerenciar CRM', 'CRM'),
-- Relatórios
('relatorios.view', 'Ver Relatórios', 'Relatórios'),
-- Configurações
('config.view', 'Ver Configurações', 'Configurações'),
('config.edit', 'Editar Configurações', 'Configurações'),
-- Usuários
('usuarios.view', 'Ver Usuários', 'Usuários'),
('usuarios.create', 'Criar Usuários', 'Usuários'),
('usuarios.edit', 'Editar Usuários', 'Usuários'),
('usuarios.delete', 'Excluir Usuários', 'Usuários'),
-- Permissões
('permissoes.view', 'Ver Permissões', 'Permissões'),
('permissoes.edit', 'Editar Permissões', 'Permissões'),
-- Logs
('logs.view', 'Ver Logs', 'Logs'),
-- Jobs
('jobs.view', 'Ver Jobs', 'Jobs'),
('jobs.manage', 'Gerenciar Jobs', 'Jobs'),
-- Mensagens
('mensagens.view', 'Ver Mensagens', 'Mensagens'),
('mensagens.manage', 'Gerenciar Mensagens', 'Mensagens'),
-- Tarefas
('tarefas.view', 'Ver Tarefas', 'Tarefas'),
('tarefas.manage', 'Gerenciar Tarefas', 'Tarefas');

-- Atribuir todas as permissões ao superadmin
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM roles r CROSS JOIN permissions p WHERE r.slug = 'superadmin';
