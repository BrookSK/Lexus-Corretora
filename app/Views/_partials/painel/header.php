<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Auth};
$painelTipo = $painelTipo ?? 'equipe';
$userName = match($painelTipo) {
    'equipe'   => Auth::equipeNome(),
    'cliente'  => Auth::clienteNome(),
    'parceiro' => Auth::parceiroNome(),
    default    => '',
};
$contaUrl = match($painelTipo) {
    'cliente'  => '/cliente/minha-conta',
    'parceiro' => '/parceiro/minha-conta',
    default    => '/equipe/minha-conta',
};
$logoutUrl = "/{$painelTipo}/sair";
?>
<header class="painel-header">
  <div class="ph-left">
    <button id="sidebarMobileToggle" class="ph-burger" aria-label="Menu">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
    </button>
    <?php if (!empty($breadcrumbs)): ?>
    <nav class="breadcrumbs">
      <?php foreach ($breadcrumbs as $i => $bc): ?>
        <?php if ($i > 0): ?><span class="bc-sep">/</span><?php endif; ?>
        <?php if (!empty($bc['url'])): ?>
          <a href="<?php echo View::e($bc['url']); ?>"><?php echo View::e($bc['label']); ?></a>
        <?php else: ?>
          <span class="bc-current"><?php echo View::e($bc['label']); ?></span>
        <?php endif; ?>
      <?php endforeach; ?>
    </nav>
    <?php endif; ?>
  </div>
  <div class="ph-right">
    <div class="ph-user-wrap">
      <button class="ph-user-btn" id="userMenuBtn" type="button">
        <span class="ph-user-name"><?php echo View::e($userName ?? ''); ?></span>
        <div class="ph-avatar"><?php echo View::e(mb_substr($userName ?? 'U', 0, 1)); ?></div>
      </button>
      <div class="ph-dropdown" id="userDropdown">
        <a href="<?php echo View::e($contaUrl); ?>" class="ph-dropdown-item">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <?php echo View::e(I18n::t('sidebar.minha_conta')); ?>
        </a>
        <?php if ($painelTipo === 'equipe'): ?>
        <a href="/equipe/configuracoes" class="ph-dropdown-item">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
          <?php echo View::e(I18n::t('sidebar.configuracoes')); ?>
        </a>
        <?php endif; ?>
        <div class="ph-dropdown-sep"></div>
        <a href="<?php echo View::e($logoutUrl); ?>" class="ph-dropdown-item ph-dropdown-logout">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          <?php echo View::e(I18n::t('auth.sair')); ?>
        </a>
      </div>
    </div>
  </div>
</header>
<script>
(function(){
  var btn=document.getElementById('userMenuBtn'),dd=document.getElementById('userDropdown');
  btn.addEventListener('click',function(e){e.stopPropagation();dd.classList.toggle('open')});
  document.addEventListener('click',function(){dd.classList.remove('open')});
})();
</script>
