<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Auth};

// Determinar tipo de painel
$painelTipo = $painelTipo ?? 'equipe';
$currentPath = $_SERVER['REQUEST_URI'] ?? '/';

// Definir itens do menu por tipo
$menuItems = [];
if ($painelTipo === 'equipe') {
    $menuItems = [
        ['url' => '/equipe/dashboard',      'icon' => 'dashboard',    'label' => I18n::t('sidebar.dashboard')],
        ['url' => '/equipe/clientes',        'icon' => 'people',      'label' => I18n::t('sidebar.clientes')],
        ['url' => '/equipe/parceiros',       'icon' => 'handshake',   'label' => I18n::t('sidebar.parceiros')],
        ['url' => '/equipe/demandas',        'icon' => 'assignment',  'label' => I18n::t('sidebar.demandas')],
        ['url' => '/equipe/propostas',       'icon' => 'description', 'label' => I18n::t('sidebar.propostas')],
        ['url' => '/equipe/contratos',       'icon' => 'gavel',       'label' => I18n::t('sidebar.contratos')],
        ['url' => '/equipe/comissoes',       'icon' => 'payments',    'label' => I18n::t('sidebar.comissoes')],
        ['url' => '/equipe/qualificacao',    'icon' => 'verified',    'label' => I18n::t('sidebar.qualificacao')],
        ['url' => '/equipe/crm',             'icon' => 'contacts',    'label' => I18n::t('sidebar.crm')],
        ['url' => '/equipe/tarefas',         'icon' => 'task',        'label' => I18n::t('sidebar.tarefas')],
        ['url' => '/equipe/mensagens',       'icon' => 'chat',        'label' => I18n::t('sidebar.mensagens')],
        ['url' => '/equipe/relatorios',      'icon' => 'analytics',   'label' => I18n::t('sidebar.relatorios')],
        ['url' => '/equipe/configuracoes',   'icon' => 'settings',    'label' => I18n::t('sidebar.configuracoes')],
        ['url' => '/equipe/usuarios',        'icon' => 'admin',       'label' => I18n::t('sidebar.usuarios')],
        ['url' => '/equipe/logs',            'icon' => 'bug',         'label' => I18n::t('sidebar.logs')],
        ['url' => '/equipe/jobs',            'icon' => 'schedule',    'label' => I18n::t('sidebar.jobs')],
    ];
} elseif ($painelTipo === 'cliente') {
    $menuItems = [
        ['url' => '/cliente/dashboard',  'icon' => 'dashboard',    'label' => I18n::t('sidebar_cli.dashboard')],
        ['url' => '/cliente/demandas',   'icon' => 'assignment',   'label' => I18n::t('sidebar_cli.demandas')],
        ['url' => '/cliente/propostas',  'icon' => 'description',  'label' => I18n::t('sidebar_cli.propostas')],
        ['url' => '/cliente/mensagens',  'icon' => 'chat',         'label' => I18n::t('sidebar_cli.mensagens')],
        ['url' => '/cliente/minha-conta','icon' => 'person',       'label' => I18n::t('sidebar_cli.minha_conta')],
    ];
} elseif ($painelTipo === 'parceiro') {
    $menuItems = [
        ['url' => '/parceiro/dashboard',      'icon' => 'dashboard',    'label' => I18n::t('sidebar_par.dashboard')],
        ['url' => '/parceiro/oportunidades',  'icon' => 'assignment',   'label' => I18n::t('sidebar_par.oportunidades')],
        ['url' => '/parceiro/propostas',      'icon' => 'description',  'label' => I18n::t('sidebar_par.propostas')],
        ['url' => '/parceiro/comissoes',      'icon' => 'payments',     'label' => I18n::t('sidebar_par.comissoes')],
        ['url' => '/parceiro/perfil',         'icon' => 'badge',        'label' => I18n::t('sidebar_par.perfil')],
        ['url' => '/parceiro/mensagens',      'icon' => 'chat',         'label' => I18n::t('sidebar_par.mensagens')],
        ['url' => '/parceiro/minha-conta',    'icon' => 'person',       'label' => I18n::t('sidebar_par.minha_conta')],
    ];
}

$logoutUrl = "/{$painelTipo}/sair";
?>
<aside id="sidebar" class="sidebar">
  <div class="sidebar-header">
    <a href="/<?php echo View::e($painelTipo); ?>/dashboard" class="sidebar-logo">
      <span class="logo-full">Lexus</span>
      <span class="logo-mini">L</span>
    </a>
    <button id="sidebarToggle" class="sidebar-toggle" aria-label="Toggle menu">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
    </button>
  </div>

  <nav class="sidebar-nav">
    <?php foreach ($menuItems as $item): ?>
    <a href="<?php echo View::e($item['url']); ?>"
       class="sidebar-item<?php echo str_starts_with($currentPath, $item['url']) ? ' active' : ''; ?>"
       title="<?php echo View::e($item['label']); ?>">
      <span class="sidebar-icon" data-icon="<?php echo View::e($item['icon']); ?>"></span>
      <span class="sidebar-label"><?php echo View::e($item['label']); ?></span>
    </a>
    <?php endforeach; ?>
  </nav>

  <div class="sidebar-footer">
    <a href="<?php echo View::e($logoutUrl); ?>" class="sidebar-item" title="<?php echo View::e(I18n::t('auth.sair')); ?>">
      <span class="sidebar-icon" data-icon="logout"></span>
      <span class="sidebar-label"><?php echo View::e(I18n::t('auth.sair')); ?></span>
    </a>
  </div>
</aside>

<script>
(function(){
  const sb=document.getElementById('sidebar'),tg=document.getElementById('sidebarToggle');
  const collapsed=localStorage.getItem('sidebar_collapsed')==='1';
  if(collapsed)sb.classList.add('collapsed');
  tg.addEventListener('click',()=>{
    sb.classList.toggle('collapsed');
    localStorage.setItem('sidebar_collapsed',sb.classList.contains('collapsed')?'1':'0');
  });
})();
</script>
