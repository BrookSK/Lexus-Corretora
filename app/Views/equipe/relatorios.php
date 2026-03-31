<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.relatorios')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('relatorios.subtitulo')); ?></p>
  </div>
</div>

<div class="cards-grid">
  <a href="/equipe/relatorios/gerar?tipo=demandas" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('relatorios.demandas')); ?></div>
    <div class="card-title"><?php echo View::e(I18n::t('relatorios.demandas_desc')); ?></div>
  </a>
  <a href="/equipe/relatorios/gerar?tipo=propostas" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('relatorios.propostas')); ?></div>
    <div class="card-title"><?php echo View::e(I18n::t('relatorios.propostas_desc')); ?></div>
  </a>
  <a href="/equipe/relatorios/gerar?tipo=contratos" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('relatorios.contratos')); ?></div>
    <div class="card-title"><?php echo View::e(I18n::t('relatorios.contratos_desc')); ?></div>
  </a>
  <a href="/equipe/relatorios/gerar?tipo=comissoes" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('relatorios.comissoes')); ?></div>
    <div class="card-title"><?php echo View::e(I18n::t('relatorios.comissoes_desc')); ?></div>
  </a>
  <a href="/equipe/relatorios/gerar?tipo=parceiros" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('relatorios.parceiros')); ?></div>
    <div class="card-title"><?php echo View::e(I18n::t('relatorios.parceiros_desc')); ?></div>
  </a>
  <a href="/equipe/relatorios/gerar?tipo=clientes" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('relatorios.clientes')); ?></div>
    <div class="card-title"><?php echo View::e(I18n::t('relatorios.clientes_desc')); ?></div>
  </a>
  <a href="/equipe/relatorios/gerar?tipo=crm" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('relatorios.crm')); ?></div>
    <div class="card-title"><?php echo View::e(I18n::t('relatorios.crm_desc')); ?></div>
  </a>
  <a href="/equipe/relatorios/gerar?tipo=financeiro" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('relatorios.financeiro')); ?></div>
    <div class="card-title"><?php echo View::e(I18n::t('relatorios.financeiro_desc')); ?></div>
  </a>
</div>
