# Lexus Corretora

Plataforma de estruturação, conexão e repasse de oportunidades de obras e reformas.

## Stack

- PHP 8.1+ (MVC customizado, sem framework)
- MySQL 8.0+ / MariaDB 10.6+
- Composer (dependências pontuais)
- Namespace: `LEX\`

## Requisitos

- PHP 8.1+ com extensões: pdo_mysql, mbstring, json, openssl, fileinfo
- MySQL 8.0+ ou MariaDB 10.6+
- Composer 2.x
- Apache com mod_rewrite ou Nginx equivalente

## Instalação

```bash
# 1. Clonar repositório
git clone <repo-url> lexus-corretora
cd lexus-corretora

# 2. Instalar dependências
composer install

# 3. Configurar banco de dados
cp config/instalacao.exemplo.php config/instalacao.php
# Editar config/instalacao.php com credenciais do banco

# 4. Criar banco de dados
mysql -u root -p -e "CREATE DATABASE lexus_corretora CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"

# 5. Executar schema inicial
mysql -u root -p lexus_corretora < database/schema.sql

# 6. Executar migrations
mysql -u root -p lexus_corretora < database/migrations/002_seed_permissions.sql

# 7. Acessar o sistema
# Abrir no navegador e acessar /equipe/primeiro-acesso para criar o superadmin
```

## Estrutura de Pastas

```
index.php                    # Entry point
.htaccess                    # Rewrite rules
config/instalacao.php        # Credenciais (NÃO versionado)
core/                        # Core do sistema (Auth, Router, DB, RBAC, i18n, etc.)
app/
  Controllers/               # Controllers por domínio (Cliente/, Parceiro/, Equipe/, Api/, Webhooks/)
  Services/                  # Services por domínio
  Views/                     # Views PHP puro
    _layouts/                # Layouts reutilizáveis (public.php, painel.php)
    _partials/               # Partials globais (header, footer, sidebar, etc.)
  Idiomas/                   # Arquivos de tradução (pt-BR, en-US, es-ES)
  Jobs/                      # Handlers de jobs assíncronos
database/
  schema.sql                 # Schema inicial
  migrations/                # Migrations SQL incrementais
routes/
  web.php                    # Rotas web
  api.php                    # Rotas API e webhooks
storage/                     # Logs, backups, uploads, exports
public/                      # Assets estáticos (CSS, JS, imagens)
worker.php                   # Worker de jobs assíncronos
```

## Módulos do Sistema

- **Dashboard Executivo** — Métricas e visão geral operacional
- **Clientes** — CRUD completo com impersonação
- **Parceiros** — CRUD com qualificação e Selo Vetriks
- **Demandas/Oportunidades** — Workflow completo de status
- **Distribuição/Repasse** — Matching inteligente por critérios
- **Propostas** — Gestão com comparador lado a lado
- **Contratos** — Rastreio de formalização
- **Comissões** — Controle completo do ciclo de vida
- **CRM/Leads** — Gestão de leads com follow-ups
- **Tarefas** — Agenda operacional interna
- **Mensagens** — Chat entre participantes
- **Relatórios** — Relatórios operacionais
- **Configurações** — Branding, SEO, SMTP, Billing, Matching, Vetriks

## Credenciais Iniciais

Acesse `/equipe/primeiro-acesso` para criar o primeiro superadmin.
Depois acesse `/equipe/inicializacao` para executar seeds e migrations.

## Worker de Jobs

```bash
# Modo contínuo
php worker.php

# Executar uma vez
php worker.php --once
```

## Configuração SMTP

Editar via painel em `/equipe/configuracoes/smtp` ou diretamente em `config/instalacao.php`.

## Billing (Stripe + Asaas)

Configurar via `/equipe/configuracoes/billing`. Cada gateway tem campos separados para sandbox e produção.

## Idiomas

O sistema suporta pt-BR, en-US e es-ES. Arquivos em `app/Idiomas/`.

## Licença

Proprietário — Lexus Corretora. Todos os direitos reservados.
