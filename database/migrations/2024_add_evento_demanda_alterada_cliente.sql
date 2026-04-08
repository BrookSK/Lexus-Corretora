-- Adiciona evento de notificação para quando cliente altera demanda
-- Executar: mysql -u [user] -p [database] < 2024_add_evento_demanda_alterada_cliente.sql

INSERT INTO notificacao_eventos (slug, name, description, is_active, destinatarios, template_message, available_variables)
VALUES (
  'demanda_alterada_cliente',
  'Demanda Alterada pelo Cliente',
  'Notifica parceiros quando um cliente edita uma demanda',
  1,
  '["parceiro"]',
  'O cliente {{cliente_nome}} alterou a demanda {{demanda_codigo}} - {{demanda_titulo}}. Revise as alterações.',
  '["demanda_id","demanda_codigo","demanda_titulo","cliente_nome","parceiro_id"]'
)
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  description = VALUES(description),
  template_message = VALUES(template_message),
  available_variables = VALUES(available_variables);
