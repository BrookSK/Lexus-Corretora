<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Auth};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_par.dashboard')); ?></h1>
    <p class="section-subtitle">Olá, <?php echo View::e(Auth::parceiroNome()); ?>!</p>
  </div>
</div>

<div class="cards-grid">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar_par.oportunidades')); ?></div>
    <div class="card-value"><?php echo $oportunidadesRecebidas; ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar_par.propostas')); ?></div>
    <div class="card-value"><?php echo $propostasEnviadas; ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar_par.comissoes')); ?></div>
    <div class="card-value"><?php echo I18n::formatarMoeda($comissoesRecebidas); ?></div>
  </div>
</div>
