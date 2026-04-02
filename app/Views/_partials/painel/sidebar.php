<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Auth, SistemaConfig};

$painelTipo = $painelTipo ?? 'equipe';
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// SVG icons inline (24x24 viewBox, stroke-based)
$icons = [
    'dashboard'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',
    'people'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    'handshake'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 6L9 17l-5-5"/><path d="M4 12l5 5 11-11"/></svg>',
    'assignment'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>',
    'description' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="8" y2="18"/><line x1="16" y1="14" x2="8" y2="14"/></svg>',
    'gavel'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M9 15l2 2 4-4"/><polyline points="14 2 14 8 20 8"/></svg>',
    'payments'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
    'verified'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>',
    'contacts'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    'task'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>',
    'chat'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
    'analytics'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
    'settings'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
    'admin'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>',
    'bug'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
    'schedule'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
    'person'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    'badge'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="1" width="18" height="22" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="12" y2="14"/></svg>',
    'logout'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
];

$menuItems = [];
if ($painelTipo === 'equipe') {
    $menuItems = [
        ['url'=>'/equipe/dashboard','icon'=>'dashboard','label'=>I18n::t('sidebar.dashboard')],
        ['url'=>'/equipe/clientes','icon'=>'people','label'=>I18n::t('sidebar.clientes')],
        ['url'=>'/equipe/parceiros','icon'=>'handshake','label'=>I18n::t('sidebar.parceiros')],
        ['url'=>'/equipe/demandas','icon'=>'assignment','label'=>I18n::t('sidebar.demandas')],
        ['url'=>'/equipe/propostas','icon'=>'description','label'=>I18n::t('sidebar.propostas')],
        ['url'=>'/equipe/contratos','icon'=>'gavel','label'=>I18n::t('sidebar.contratos')],
        ['url'=>'/equipe/comissoes','icon'=>'payments','label'=>I18n::t('sidebar.comissoes')],
        ['url'=>'/equipe/qualificacao','icon'=>'verified','label'=>I18n::t('sidebar.qualificacao')],
        ['url'=>'/equipe/crm','icon'=>'contacts','label'=>I18n::t('sidebar.crm')],
        ['url'=>'/equipe/tarefas','icon'=>'task','label'=>I18n::t('sidebar.tarefas')],
        ['url'=>'/equipe/mensagens','icon'=>'chat','label'=>I18n::t('sidebar.mensagens')],
        ['url'=>'/equipe/relatorios','icon'=>'analytics','label'=>I18n::t('sidebar.relatorios')],
        ['url'=>'/equipe/configuracoes','icon'=>'settings','label'=>I18n::t('sidebar.configuracoes')],
        ['url'=>'/equipe/usuarios','icon'=>'admin','label'=>I18n::t('sidebar.usuarios')],
        ['url'=>'/equipe/logs','icon'=>'bug','label'=>I18n::t('sidebar.logs')],
        ['url'=>'/equipe/jobs','icon'=>'schedule','label'=>I18n::t('sidebar.jobs')],
    ];
} elseif ($painelTipo === 'cliente') {
    $menuItems = [
        ['url'=>'/cliente/dashboard','icon'=>'dashboard','label'=>I18n::t('sidebar_cli.dashboard')],
        ['url'=>'/cliente/demandas','icon'=>'assignment','label'=>I18n::t('sidebar_cli.demandas')],
        ['url'=>'/cliente/propostas','icon'=>'description','label'=>I18n::t('sidebar_cli.propostas')],
        ['url'=>'/cliente/mensagens','icon'=>'chat','label'=>I18n::t('sidebar_cli.mensagens')],
        ['url'=>'/cliente/minha-conta','icon'=>'person','label'=>I18n::t('sidebar_cli.minha_conta')],
    ];
} elseif ($painelTipo === 'parceiro') {
    $menuItems = [
        ['url'=>'/parceiro/dashboard','icon'=>'dashboard','label'=>I18n::t('sidebar_par.dashboard')],
        ['url'=>'/parceiro/oportunidades','icon'=>'assignment','label'=>I18n::t('sidebar_par.oportunidades')],
        ['url'=>'/parceiro/propostas','icon'=>'description','label'=>I18n::t('sidebar_par.propostas')],
        ['url'=>'/parceiro/repasse','icon'=>'admin','label'=>I18n::t('sidebar_par.repasse')],
        ['url'=>'/parceiro/contratos','icon'=>'gavel','label'=>I18n::t('sidebar_par.contratos')],
        ['url'=>'/parceiro/comissoes','icon'=>'payments','label'=>I18n::t('sidebar_par.comissoes')],
        ['url'=>'/parceiro/perfil','icon'=>'badge','label'=>I18n::t('sidebar_par.perfil')],
        ['url'=>'/parceiro/mensagens','icon'=>'chat','label'=>I18n::t('sidebar_par.mensagens')],
        ['url'=>'/parceiro/minha-conta','icon'=>'person','label'=>I18n::t('sidebar_par.minha_conta')],
    ];
}
$logoutUrl = "/sair";
?>
<aside id="sidebar" class="sidebar">
  <div class="sidebar-header">
    <a href="/<?php echo View::e($painelTipo); ?>/dashboard" class="sidebar-logo">
      <?php $logoUrl = SistemaConfig::logo(); $faviconUrl = SistemaConfig::favicon(); ?>
      <img src="<?php echo View::e($logoUrl); ?>" alt="<?php echo View::e(SistemaConfig::nome()); ?>" class="logo-full"/>
      <img src="<?php echo View::e($faviconUrl); ?>" alt="<?php echo View::e(SistemaConfig::nome()); ?>" class="logo-mini"/>
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
      <span class="sidebar-icon"><?php echo $icons[$item['icon']] ?? ''; ?></span>
      <span class="sidebar-label"><?php echo View::e($item['label']); ?></span>
    </a>
    <?php endforeach; ?>
  </nav>
  <div class="sidebar-footer">
    <a href="<?php echo View::e($logoutUrl); ?>" class="sidebar-item" title="<?php echo View::e(I18n::t('auth.sair')); ?>">
      <span class="sidebar-icon"><?php echo $icons['logout']; ?></span>
      <span class="sidebar-label"><?php echo View::e(I18n::t('auth.sair')); ?></span>
    </a>
  </div>
</aside>
<script>
(function(){
  var sb=document.getElementById('sidebar'),tg=document.getElementById('sidebarToggle');
  if(localStorage.getItem('sidebar_collapsed')==='1')sb.classList.add('collapsed');
  tg.addEventListener('click',function(){
    sb.classList.toggle('collapsed');
    localStorage.setItem('sidebar_collapsed',sb.classList.contains('collapsed')?'1':'0');
  });
})();
</script>
