<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.dashboard')); ?></h1>
    <p class="section-subtitle">Visão geral operacional da Lexus Corretora</p>
  </div>
</div>

<div class="cards-grid">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('dash.novas_demandas')); ?></div>
    <div class="card-value"><?php echo $metricas['novas_demandas']; ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('dash.oportunidades_ativas')); ?></div>
    <div class="card-value"><?php echo $metricas['oportunidades_ativas']; ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('dash.propostas_andamento')); ?></div>
    <div class="card-value"><?php echo $metricas['propostas_andamento']; ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('dash.fechamentos')); ?></div>
    <div class="card-value"><?php echo $metricas['fechamentos']; ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('dash.parceiros_ativos')); ?></div>
    <div class="card-value"><?php echo $metricas['parceiros_ativos']; ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('dash.clientes_ativos')); ?></div>
    <div class="card-value"><?php echo $metricas['clientes_ativos']; ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('dash.comissoes_previstas')); ?></div>
    <div class="card-value"><?php echo I18n::formatarMoeda($metricas['comissoes_previstas']); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('dash.comissoes_recebidas')); ?></div>
    <div class="card-value"><?php echo I18n::formatarMoeda($metricas['comissoes_recebidas']); ?></div>
  </div>
</div>
