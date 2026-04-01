<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('distribuicao.titulo')); ?></h1>
    <p class="section-subtitle">
      <?php if (!empty($demanda)): ?>
        <?php echo View::e($demanda['code']); ?> — <?php echo View::e($demanda['title']); ?>
      <?php else: ?>
        <?php echo View::e(I18n::t('distribuicao.subtitulo')); ?>
      <?php endif; ?>
    </p>
  </div>
  <?php if (!empty($demanda)): ?>
  <a href="/equipe/demandas/<?php echo (int)$demanda['id']; ?>" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
  <?php endif; ?>
</div>

<?php if (!empty($demanda)): ?>
<!-- Info da demanda -->
<div class="cards-grid" style="margin-bottom:24px">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar.clientes')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['cliente_nome'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.cidade')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['city'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('demandas.categoria')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['category'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('demandas.orcamento')); ?></div>
    <div class="card-title">
      <?php if (!empty($demanda['budget_min']) || !empty($demanda['budget_max'])): ?>
        <?php echo I18n::formatarMoeda($demanda['budget_min'] ?? 0); ?> — <?php echo I18n::formatarMoeda($demanda['budget_max'] ?? 0); ?>
      <?php else: ?>—<?php endif; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Sugestões de matching -->
<?php if (!empty($sugestoes)): ?>
<div class="section-header">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('distribuicao.sugestoes')); ?></h2></div>
</div>
<div class="cards-grid" style="margin-bottom:24px">
  <?php foreach ($sugestoes as $sug): ?>
  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
      <div class="card-title"><?php echo View::e($sug['name']); ?></div>
      <?php if ($sug['is_vetriks'] ?? false): ?>
        <span class="badge badge-gold">Vetriks</span>
      <?php endif; ?>
    </div>
    <div class="card-label"><?php echo View::e($sug['type'] ?? ''); ?> — Score: <?php echo (int)($sug['score'] ?? 0); ?></div>
    <div style="font-size:.82rem;color:var(--text-muted);margin-top:8px">
      <?php echo View::e(($sug['city'] ?? '') . ' / ' . ($sug['state'] ?? '')); ?>
    </div>
    <?php if (!empty($sug['match_score'])): ?>
    <div style="margin-top:8px">
      <span class="badge badge-green"><?php echo View::e(I18n::t('distribuicao.match')); ?>: <?php echo (int)$sug['match_score']; ?>%</span>
    </div>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Formulário de distribuição -->
<div class="section-header">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('distribuicao.selecionar_parceiros')); ?></h2></div>
</div>
<div class="card">
  <form method="POST" action="/equipe/distribuicao/<?php echo (int)($demanda['id'] ?? 0); ?>">
    <?php echo Csrf::campo(); ?>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('distribuicao.tipo')); ?></label>
      <select name="distribution_type">
        <option value="manual"><?php echo View::e(I18n::t('distribuicao.manual')); ?></option>
        <option value="automatica"><?php echo View::e(I18n::t('distribuicao.automatica')); ?></option>
      </select>
    </div>

    <div class="table-wrap" style="margin-bottom:20px">
      <table>
        <thead>
          <tr>
            <th style="width:40px"><input type="checkbox" id="selectAll"/></th>
            <th><?php echo View::e(I18n::t('geral.nome')); ?></th>
            <th><?php echo View::e(I18n::t('parceiros.tipo')); ?></th>
            <th>Score</th>
            <th><?php echo View::e(I18n::t('geral.cidade')); ?></th>
            <th>Vetriks</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($parceiros_disponiveis)): ?>
          <tr><td colspan="6"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
          <?php else: foreach ($parceiros_disponiveis as $pa): ?>
          <tr>
            <td><input type="checkbox" name="parceiros[]" value="<?php echo (int)$pa['id']; ?>"/></td>
            <td><?php echo View::e($pa['name']); ?></td>
            <td><?php echo View::e($pa['type']); ?></td>
            <td><?php echo (int)($pa['score'] ?? 0); ?></td>
            <td><?php echo View::e($pa['city'] ?? '—'); ?></td>
            <td>
              <?php if ($pa['is_vetriks'] ?? false): ?>
                <span class="badge badge-gold">✓</span>
              <?php else: ?>—<?php endif; ?>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('distribuicao.enviar')); ?></button>
    </div>
  </form>
</div>

<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
  document.querySelectorAll('input[name="parceiros[]"]').forEach(cb => cb.checked = this.checked);
});
</script>
