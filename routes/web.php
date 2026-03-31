<?php
declare(strict_types=1);

use LEX\Core\Roteador;
use LEX\Core\Middlewares;

// Controllers públicos
use LEX\App\Controllers\InicialController;
use LEX\App\Controllers\LegalController;
use LEX\App\Controllers\SeoController;
use LEX\App\Controllers\StatusController;
use LEX\App\Controllers\ChangelogController;
use LEX\App\Controllers\ContatoController;
use LEX\App\Controllers\CookieConsentController;

// Controllers de autenticação
use LEX\App\Controllers\Cliente\AuthController as ClienteAuth;
use LEX\App\Controllers\Parceiro\AuthController as ParceiroAuth;
use LEX\App\Controllers\Equipe\AuthController as EquipeAuth;

// Controllers do cliente
use LEX\App\Controllers\Cliente\DashboardController as ClienteDashboard;
use LEX\App\Controllers\Cliente\DemandasController as ClienteDemandas;
use LEX\App\Controllers\Cliente\PropostasController as ClientePropostas;
use LEX\App\Controllers\Cliente\MensagensController as ClienteMensagens;
use LEX\App\Controllers\Cliente\ContaController as ClienteConta;

// Controllers do parceiro
use LEX\App\Controllers\Parceiro\DashboardController as ParceiroDashboard;
use LEX\App\Controllers\Parceiro\OportunidadesController as ParceiroOportunidades;
use LEX\App\Controllers\Parceiro\PropostasController as ParceiroPropostas;
use LEX\App\Controllers\Parceiro\ComissoesController as ParceiroComissoes;
use LEX\App\Controllers\Parceiro\PerfilController as ParceiroPerfil;
use LEX\App\Controllers\Parceiro\MensagensController as ParceiroMensagens;
use LEX\App\Controllers\Parceiro\ContaController as ParceiroConta;

// Controllers da equipe
use LEX\App\Controllers\Equipe\DashboardController as EquipeDashboard;
use LEX\App\Controllers\Equipe\ClientesController as EquipeClientes;
use LEX\App\Controllers\Equipe\ParceirosController as EquipeParceiros;
use LEX\App\Controllers\Equipe\DemandasController as EquipeDemandas;
use LEX\App\Controllers\Equipe\DistribuicaoController;
use LEX\App\Controllers\Equipe\PropostasController as EquipePropostas;
use LEX\App\Controllers\Equipe\ContratosController;
use LEX\App\Controllers\Equipe\ComissoesController;
use LEX\App\Controllers\Equipe\QualificacaoController;
use LEX\App\Controllers\Equipe\CrmController;
use LEX\App\Controllers\Equipe\TarefasController;
use LEX\App\Controllers\Equipe\RelatoriosController;
use LEX\App\Controllers\Equipe\ConfigController;
use LEX\App\Controllers\Equipe\UsuariosController;
use LEX\App\Controllers\Equipe\PermissoesController;
use LEX\App\Controllers\Equipe\LogsController;
use LEX\App\Controllers\Equipe\JobsController;
use LEX\App\Controllers\Equipe\MensagensController as EquipeMensagens;
use LEX\App\Controllers\Equipe\InicializacaoController;

// ═══════════════════════════════════════════════════════════════
// ROTAS PÚBLICAS
// ═══════════════════════════════════════════════════════════════

Roteador::get('/', [InicialController::class, 'index']);
Roteador::get('/como-funciona', [InicialController::class, 'comoFunciona']);
Roteador::get('/para-clientes', [InicialController::class, 'paraClientes']);
Roteador::get('/para-parceiros', [InicialController::class, 'paraParceiros']);
Roteador::get('/vetriks', [InicialController::class, 'vetriks']);
Roteador::get('/sobre', [InicialController::class, 'sobre']);
Roteador::get('/contato', [ContatoController::class, 'index']);
Roteador::post('/contato', [ContatoController::class, 'enviar'], [
    Middlewares::rateLimitIp('contato', 5, 300),
]);

// Formulários públicos
Roteador::get('/abrir-demanda', [InicialController::class, 'abrirDemanda']);
Roteador::post('/abrir-demanda', [InicialController::class, 'salvarDemanda'], [
    Middlewares::rateLimitIp('demanda', 3, 600),
]);
Roteador::get('/seja-parceiro', [InicialController::class, 'sejaParceiro']);
Roteador::post('/seja-parceiro', [InicialController::class, 'salvarParceiro'], [
    Middlewares::rateLimitIp('parceiro', 3, 600),
]);

// Legal
Roteador::get('/termos', [LegalController::class, 'termos']);
Roteador::get('/privacidade', [LegalController::class, 'privacidade']);

// SEO
Roteador::get('/robots.txt', [SeoController::class, 'robots']);
Roteador::get('/sitemap.xml', [SeoController::class, 'sitemap']);

// Status e Changelog
Roteador::get('/status', [StatusController::class, 'index']);
Roteador::get('/changelog', [ChangelogController::class, 'index']);

// Cookies LGPD
Roteador::post('/cookies/consent', [CookieConsentController::class, 'salvar']);

// Idioma / Moeda
Roteador::post('/idioma', [InicialController::class, 'trocarIdioma']);
Roteador::post('/moeda', [InicialController::class, 'trocarMoeda']);

// ═══════════════════════════════════════════════════════════════
// ROTAS CLIENTE
// ═══════════════════════════════════════════════════════════════

Roteador::get('/cliente/entrar', [ClienteAuth::class, 'loginForm']);
Roteador::post('/cliente/entrar', [ClienteAuth::class, 'login'], [
    Middlewares::rateLimitIp('cli_login', 10, 1800),
]);
Roteador::get('/cliente/criar-conta', [ClienteAuth::class, 'registroForm']);
Roteador::post('/cliente/criar-conta', [ClienteAuth::class, 'registro'], [
    Middlewares::rateLimitIp('cli_registro', 5, 600),
]);
Roteador::get('/cliente/esqueci-senha', [ClienteAuth::class, 'esqueciSenhaForm']);
Roteador::post('/cliente/esqueci-senha', [ClienteAuth::class, 'esqueciSenha']);
Roteador::get('/cliente/redefinir-senha/{token}', [ClienteAuth::class, 'redefinirSenhaForm']);
Roteador::post('/cliente/redefinir-senha', [ClienteAuth::class, 'redefinirSenha']);
Roteador::get('/cliente/sair', [ClienteAuth::class, 'logout']);

$cliMw = [Middlewares::exigirLoginCliente()];
Roteador::get('/cliente/dashboard', [ClienteDashboard::class, 'index'], $cliMw);
Roteador::get('/cliente/demandas', [ClienteDemandas::class, 'index'], $cliMw);
Roteador::get('/cliente/demandas/nova', [ClienteDemandas::class, 'criar'], $cliMw);
Roteador::post('/cliente/demandas/nova', [ClienteDemandas::class, 'salvar'], $cliMw);
Roteador::get('/cliente/demandas/{id}', [ClienteDemandas::class, 'detalhe'], $cliMw);
Roteador::get('/cliente/propostas', [ClientePropostas::class, 'index'], $cliMw);
Roteador::get('/cliente/mensagens', [ClienteMensagens::class, 'index'], $cliMw);
Roteador::post('/cliente/mensagens/enviar', [ClienteMensagens::class, 'enviar'], $cliMw);
Roteador::get('/cliente/minha-conta', [ClienteConta::class, 'index'], $cliMw);
Roteador::post('/cliente/minha-conta', [ClienteConta::class, 'salvar'], $cliMw);

// ═══════════════════════════════════════════════════════════════
// ROTAS PARCEIRO
// ═══════════════════════════════════════════════════════════════

Roteador::get('/parceiro/entrar', [ParceiroAuth::class, 'loginForm']);
Roteador::post('/parceiro/entrar', [ParceiroAuth::class, 'login'], [
    Middlewares::rateLimitIp('par_login', 10, 1800),
]);
Roteador::get('/parceiro/criar-conta', [ParceiroAuth::class, 'registroForm']);
Roteador::post('/parceiro/criar-conta', [ParceiroAuth::class, 'registro']);
Roteador::get('/parceiro/esqueci-senha', [ParceiroAuth::class, 'esqueciSenhaForm']);
Roteador::post('/parceiro/esqueci-senha', [ParceiroAuth::class, 'esqueciSenha']);
Roteador::get('/parceiro/sair', [ParceiroAuth::class, 'logout']);

$parMw = [Middlewares::exigirLoginParceiro()];
Roteador::get('/parceiro/dashboard', [ParceiroDashboard::class, 'index'], $parMw);
Roteador::get('/parceiro/oportunidades', [ParceiroOportunidades::class, 'index'], $parMw);
Roteador::get('/parceiro/oportunidades/{id}', [ParceiroOportunidades::class, 'detalhe'], $parMw);
Roteador::post('/parceiro/oportunidades/{id}/interesse', [ParceiroOportunidades::class, 'interesse'], $parMw);
Roteador::get('/parceiro/propostas', [ParceiroPropostas::class, 'index'], $parMw);
Roteador::get('/parceiro/propostas/nova/{demandaId}', [ParceiroPropostas::class, 'criar'], $parMw);
Roteador::post('/parceiro/propostas/nova', [ParceiroPropostas::class, 'salvar'], $parMw);
Roteador::get('/parceiro/comissoes', [ParceiroComissoes::class, 'index'], $parMw);
Roteador::get('/parceiro/perfil', [ParceiroPerfil::class, 'index'], $parMw);
Roteador::post('/parceiro/perfil', [ParceiroPerfil::class, 'salvar'], $parMw);
Roteador::get('/parceiro/mensagens', [ParceiroMensagens::class, 'index'], $parMw);
Roteador::post('/parceiro/mensagens/enviar', [ParceiroMensagens::class, 'enviar'], $parMw);
Roteador::get('/parceiro/minha-conta', [ParceiroConta::class, 'index'], $parMw);
Roteador::post('/parceiro/minha-conta', [ParceiroConta::class, 'salvar'], $parMw);

// ═══════════════════════════════════════════════════════════════
// ROTAS EQUIPE / ADMIN
// ═══════════════════════════════════════════════════════════════

Roteador::get('/equipe/entrar', [EquipeAuth::class, 'loginForm']);
Roteador::post('/equipe/entrar', [EquipeAuth::class, 'login'], [
    Middlewares::rateLimitIp('eq_login', 10, 1800),
]);
Roteador::get('/equipe/primeiro-acesso', [EquipeAuth::class, 'primeiroAcessoForm']);
Roteador::post('/equipe/primeiro-acesso', [EquipeAuth::class, 'primeiroAcesso']);
Roteador::get('/equipe/sair', [EquipeAuth::class, 'logout']);

$eqMw = [Middlewares::exigirLoginEquipe()];

Roteador::get('/equipe/inicializacao', [InicializacaoController::class, 'index'], $eqMw);
Roteador::post('/equipe/inicializacao', [InicializacaoController::class, 'executar'], $eqMw);

Roteador::get('/equipe/dashboard', [EquipeDashboard::class, 'index'], $eqMw);

// Clientes
Roteador::get('/equipe/clientes', [EquipeClientes::class, 'index'], $eqMw);
Roteador::get('/equipe/clientes/novo', [EquipeClientes::class, 'criar'], $eqMw);
Roteador::post('/equipe/clientes/novo', [EquipeClientes::class, 'salvar'], $eqMw);
Roteador::get('/equipe/clientes/{id}', [EquipeClientes::class, 'detalhe'], $eqMw);
Roteador::get('/equipe/clientes/{id}/editar', [EquipeClientes::class, 'editar'], $eqMw);
Roteador::post('/equipe/clientes/{id}/editar', [EquipeClientes::class, 'atualizar'], $eqMw);

// Parceiros
Roteador::get('/equipe/parceiros', [EquipeParceiros::class, 'index'], $eqMw);
Roteador::get('/equipe/parceiros/novo', [EquipeParceiros::class, 'criar'], $eqMw);
Roteador::post('/equipe/parceiros/novo', [EquipeParceiros::class, 'salvar'], $eqMw);
Roteador::get('/equipe/parceiros/{id}', [EquipeParceiros::class, 'detalhe'], $eqMw);
Roteador::get('/equipe/parceiros/{id}/editar', [EquipeParceiros::class, 'editar'], $eqMw);
Roteador::post('/equipe/parceiros/{id}/editar', [EquipeParceiros::class, 'atualizar'], $eqMw);

// Demandas
Roteador::get('/equipe/demandas', [EquipeDemandas::class, 'index'], $eqMw);
Roteador::get('/equipe/demandas/nova', [EquipeDemandas::class, 'criar'], $eqMw);
Roteador::post('/equipe/demandas/nova', [EquipeDemandas::class, 'salvar'], $eqMw);
Roteador::get('/equipe/demandas/{id}', [EquipeDemandas::class, 'detalhe'], $eqMw);
Roteador::get('/equipe/demandas/{id}/editar', [EquipeDemandas::class, 'editar'], $eqMw);
Roteador::post('/equipe/demandas/{id}/editar', [EquipeDemandas::class, 'atualizar'], $eqMw);
Roteador::post('/equipe/demandas/{id}/status', [EquipeDemandas::class, 'alterarStatus'], $eqMw);

// Distribuição
Roteador::get('/equipe/distribuicao/{demandaId}', [DistribuicaoController::class, 'index'], $eqMw);
Roteador::post('/equipe/distribuicao/{demandaId}', [DistribuicaoController::class, 'distribuir'], $eqMw);

// Propostas
Roteador::get('/equipe/propostas', [EquipePropostas::class, 'index'], $eqMw);
Roteador::get('/equipe/propostas/{id}', [EquipePropostas::class, 'detalhe'], $eqMw);
Roteador::get('/equipe/propostas/comparar/{demandaId}', [EquipePropostas::class, 'comparar'], $eqMw);
Roteador::post('/equipe/propostas/{id}/status', [EquipePropostas::class, 'alterarStatus'], $eqMw);

// Contratos
Roteador::get('/equipe/contratos', [ContratosController::class, 'index'], $eqMw);
Roteador::get('/equipe/contratos/novo', [ContratosController::class, 'criar'], $eqMw);
Roteador::post('/equipe/contratos/novo', [ContratosController::class, 'salvar'], $eqMw);
Roteador::get('/equipe/contratos/{id}', [ContratosController::class, 'detalhe'], $eqMw);
Roteador::post('/equipe/contratos/{id}/status', [ContratosController::class, 'alterarStatus'], $eqMw);

// Comissões
Roteador::get('/equipe/comissoes', [ComissoesController::class, 'index'], $eqMw);
Roteador::get('/equipe/comissoes/nova', [ComissoesController::class, 'criar'], $eqMw);
Roteador::post('/equipe/comissoes/nova', [ComissoesController::class, 'salvar'], $eqMw);
Roteador::get('/equipe/comissoes/{id}', [ComissoesController::class, 'detalhe'], $eqMw);
Roteador::post('/equipe/comissoes/{id}/status', [ComissoesController::class, 'alterarStatus'], $eqMw);

// Qualificação / Vetriks
Roteador::get('/equipe/qualificacao', [QualificacaoController::class, 'index'], $eqMw);
Roteador::get('/equipe/qualificacao/{parceiroId}', [QualificacaoController::class, 'avaliar'], $eqMw);
Roteador::post('/equipe/qualificacao/{parceiroId}', [QualificacaoController::class, 'salvarAvaliacao'], $eqMw);

// CRM
Roteador::get('/equipe/crm', [CrmController::class, 'index'], $eqMw);
Roteador::get('/equipe/crm/novo', [CrmController::class, 'criar'], $eqMw);
Roteador::post('/equipe/crm/novo', [CrmController::class, 'salvar'], $eqMw);
Roteador::get('/equipe/crm/{id}', [CrmController::class, 'detalhe'], $eqMw);
Roteador::post('/equipe/crm/{id}/converter', [CrmController::class, 'converter'], $eqMw);

// Tarefas
Roteador::get('/equipe/tarefas', [TarefasController::class, 'index'], $eqMw);
Roteador::post('/equipe/tarefas/nova', [TarefasController::class, 'salvar'], $eqMw);
Roteador::post('/equipe/tarefas/{id}/status', [TarefasController::class, 'alterarStatus'], $eqMw);

// Mensagens
Roteador::get('/equipe/mensagens', [EquipeMensagens::class, 'index'], $eqMw);
Roteador::get('/equipe/mensagens/{conversaId}', [EquipeMensagens::class, 'conversa'], $eqMw);
Roteador::post('/equipe/mensagens/enviar', [EquipeMensagens::class, 'enviar'], $eqMw);

// Relatórios
Roteador::get('/equipe/relatorios', [RelatoriosController::class, 'index'], $eqMw);
Roteador::get('/equipe/relatorios/{tipo}', [RelatoriosController::class, 'gerar'], $eqMw);

// Configurações
Roteador::get('/equipe/configuracoes', [ConfigController::class, 'index'], $eqMw);
Roteador::post('/equipe/configuracoes', [ConfigController::class, 'salvar'], $eqMw);
Roteador::get('/equipe/configuracoes/{secao}', [ConfigController::class, 'secao'], $eqMw);
Roteador::post('/equipe/configuracoes/{secao}', [ConfigController::class, 'salvarSecao'], $eqMw);

// Usuários
Roteador::get('/equipe/usuarios', [UsuariosController::class, 'index'], $eqMw);
Roteador::get('/equipe/usuarios/novo', [UsuariosController::class, 'criar'], $eqMw);
Roteador::post('/equipe/usuarios/novo', [UsuariosController::class, 'salvar'], $eqMw);
Roteador::get('/equipe/usuarios/{id}/editar', [UsuariosController::class, 'editar'], $eqMw);
Roteador::post('/equipe/usuarios/{id}/editar', [UsuariosController::class, 'atualizar'], $eqMw);

// Permissões
Roteador::get('/equipe/permissoes', [PermissoesController::class, 'index'], $eqMw);
Roteador::post('/equipe/permissoes', [PermissoesController::class, 'salvar'], $eqMw);

// Logs
Roteador::get('/equipe/logs', [LogsController::class, 'index'], $eqMw);
Roteador::get('/equipe/logs/erros', [LogsController::class, 'erros'], $eqMw);
Roteador::get('/equipe/logs/auditoria', [LogsController::class, 'auditoria'], $eqMw);
Roteador::get('/equipe/logs/auth', [LogsController::class, 'auth'], $eqMw);

// Jobs
Roteador::get('/equipe/jobs', [JobsController::class, 'index'], $eqMw);
Roteador::post('/equipe/jobs/{id}/retry', [JobsController::class, 'retry'], $eqMw);
