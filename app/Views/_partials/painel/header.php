<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Auth, SistemaConfig};
$painelTipo = $painelTipo ?? 'equipe';
$userName = match($painelTipo) {
    'equipe'   => Auth::equipeNome(),
    'cliente'  => Auth::clienteNome(),
    'parceiro' => Auth::parceiroNome(),
    default    => '',
};
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
    <div class="ph-user">
      <span class="ph-user-name"><?php echo View::e($userName ?? ''); ?></span>
      <div class="ph-avatar"><?php echo View::e(mb_substr($userName ?? 'U', 0, 1)); ?></div>
    </div>
  </div>
</header>
