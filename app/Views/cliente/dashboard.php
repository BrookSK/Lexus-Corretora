<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Auth};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_cli.dashboard')); ?></h1>
    <p class="section-subtitle">Olá, <?php echo View::e(Auth::clienteNome()); ?>!</p>
  </div>
  <a href="/cliente/demandas/nova" class="btn btn-primary"><?php echo View::e(I18n::t('nav.abrir_demanda')); ?></a>
</div>

<div class="cards-grid">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar_cli.demandas')); ?></div>
    <div class="card-value"><?php echo $totalDemandas; ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar_cli.propostas')); ?></div>
    <div class="card-value"><?php echo $totalPropostas; ?></div>
  </div>
</div>
