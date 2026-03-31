<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('relatorios.resultado')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('relatorios.tipo_' . ($tipo ?? 'geral'))); ?></p>
  </div>
  <a href="/equipe/relatorios" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<!-- Filtros de período -->
<div class="card" style="margin-bottom:20px;padding:16px 20px">
  <form method="GET" action="/equipe/relatorios/gerar" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
    <input type="hidden" name="tipo" value="<?php echo View::e($tipo ?? ''); ?>"/>
    <div class="form-group" style="margin:0">
      <label><?php echo View::e(I18n::t('relatorios.data_inicio')); ?></label>
      <input type="date" name="data_inicio" value="<?php echo View::e($data_inicio ?? ''); ?>"/>
    </div>
    <div class="form-group" style="margin:0">
      <label><?php echo View::e(I18n::t('relatorios.data_fim')); ?></label>
      <input type="date" name="data_fim" value="<?php echo View::e($data_fim ?? ''); ?>"/>
    </div>
    <button type="submit" class="btn btn-primary btn-sm"><?php echo View::e(I18n::t('relatorios.gerar')); ?></button>
  </form>
</div>

<!-- Métricas resumo -->
<?php if (!empty($metricas)): ?>
<div class="cards-grid" style="margin-bottom:24px">
  <?php foreach ($metricas as $label => $valor): ?>
  <div class="card">
    <div class="card-label"><?php echo View::e($label); ?></div>
    <div class="card-value"><?php echo View::e((string)$valor); ?></div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Tabela de resultados -->
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <?php if (!empty($colunas)): foreach ($colunas as $col): ?>
        <th><?php echo View::e($col); ?></th>
        <?php endforeach; endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($resultados)): ?>
      <tr><td colspan="<?php echo count($colunas ?? []); ?>"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($resultados as $row): ?>
      <tr>
        <?php foreach ($colunas ?? [] as $col): ?>
        <td><?php echo View::e((string)($row[$col] ?? '—')); ?></td>
        <?php endforeach; ?>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
