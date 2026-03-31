<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.logs')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('logs.subtitulo')); ?></p>
  </div>
</div>

<div class="cards-grid" style="grid-template-columns:repeat(3,1fr)">
  <a href="/equipe/logs/erros" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('logs.erros')); ?></div>
    <div class="card-value"><?php echo (int)($metricas['total_erros'] ?? 0); ?></div>
    <div class="card-title" style="margin-top:8px"><?php echo View::e(I18n::t('logs.erros_desc')); ?></div>
  </a>
  <a href="/equipe/logs/auditoria" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('logs.auditoria')); ?></div>
    <div class="card-value"><?php echo (int)($metricas['total_audit'] ?? 0); ?></div>
    <div class="card-title" style="margin-top:8px"><?php echo View::e(I18n::t('logs.auditoria_desc')); ?></div>
  </a>
  <a href="/equipe/logs/auth" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('logs.auth')); ?></div>
    <div class="card-value"><?php echo (int)($metricas['total_auth'] ?? 0); ?></div>
    <div class="card-title" style="margin-top:8px"><?php echo View::e(I18n::t('logs.auth_desc')); ?></div>
  </a>
</div>
