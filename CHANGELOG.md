# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Versionamento Semântico](https://semver.org/lang/pt-BR/).

## [1.0.0] - 2026-03-31

### Adicionado
- Estrutura completa do sistema Lexus Corretora
- Core MVC customizado (PHP 8.1+, namespace LEX\)
- Sistema de autenticação tripla (equipe, cliente, parceiro)
- RBAC com roles e permissions
- Sistema de settings key-value no banco
- Internacionalização (pt-BR, en-US, es-ES) com suporte a moedas (BRL, USD)
- Home institucional baseada no design premium Lexus
- Páginas públicas: Como Funciona, Para Clientes, Para Parceiros, Vetriks, Sobre, Contato
- Formulário público "Abrir Demanda" com upload de arquivos
- Formulário público "Seja Parceiro" com cadastro completo
- Painel do Cliente com dashboard, demandas, propostas e mensagens
- Painel do Parceiro com dashboard, oportunidades, propostas, comissões e perfil
- Painel da Equipe/Admin com dashboard executivo e módulos completos
- Módulo de Demandas/Oportunidades com workflow de status
- Módulo de Distribuição/Repasse com matching inteligente
- Módulo de Propostas com comparador lado a lado
- Módulo de Contratos com rastreio de formalização
- Módulo de Comissões com controle completo de ciclo de vida
- Módulo de Qualificação/Vetriks com checklist e scoring
- Módulo CRM com leads e follow-ups
- Módulo de Tarefas e Agenda
- Sistema de Mensagens/Chat entre participantes
- Sistema de Notificações multi-canal
- Timeline/Histórico por entidade
- Central de Documentos/Uploads com ACL
- Sistema de Relatórios operacionais
- Configurações administráveis via superadmin
- Billing preparado (Stripe + Asaas) com sandbox/produção separados
- Webhooks com informações visíveis no painel
- CSRF em todos os POST
- Rate limiting por rota
- Audit logs e auth logs
- Sistema de Jobs assíncronos
- Error handling com log em banco e arquivo
- SEO técnico (robots.txt, sitemap.xml, meta tags, Open Graph, Schema.org)
- Cookie consent LGPD com categorias
- Chatbot/Widget flutuante institucional
- Sidebar recolhível com estado persistente
- Design premium coerente com identidade Lexus
- Responsividade completa (mobile, tablet, desktop)
