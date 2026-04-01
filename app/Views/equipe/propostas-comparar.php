<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('propostas.comparar')); ?></h1>
    <p class="section-subtitle"><?php echo View::e($demanda['code'] ?? ''); ?> — <?php echo View::e($demanda['title'] ?? ''); ?></p>
  </div>
  <a href="/equipe/demandas/<?php echo (int)($demanda['id'] ?? 0); ?>" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<?php if (empty($propostas)): ?>
<div class="card">
  <p style="color:var(--text-muted);font-size:.88rem"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></p>
</div>
<?php else: ?>
<div class="cards-grid" style="grid-template-columns:repeat(auto-fill,minmax(300px,1fr))">
  <?php foreach ($propostas as $p): ?>
  <div class="card" style="<?php echo ($p['is_shortlisted'] ?? false) ? 'border-color:var(--gold);border-width:2px' : ''; ?>">
    <?php if ($p['is_shortlisted'] ?? false): ?>
      <span class="badge badge-gold" style="margin-bottom:12px">Shortlist</span>
    <?php endif; ?>

    <div class="card-label"><?php echo View::e(I18n::t('sidebar.parceiros')); ?></div>
    <div class="card-title" style="margin-bottom:16px"><?php echo View::e($p['parceiro_nome'] ?? '—'); ?></div>

    <div class="card-label"><?php echo View::e(I18n::t('propostas.valor')); ?></div>
    <div class="card-value" style="margin-bottom:16px"><?php echo I18n::formatarMoeda($p['amount']); ?></div>

    <div class="card-label"><?php echo View::e(I18n::t('propostas.prazo_dias')); ?></div>
    <div class="card-title" style="margin-bottom:16px"><?php echo (int)($p['deadline_days'] ?? 0); ?> <?php echo View::e(I18n::t('geral.dias')); ?></div>

    <div class="card-label"><?php echo View::e(I18n::t('geral.status')); ?></div>
    <div style="margin-bottom:16px">
      <?php
      $badge = match($p['status'] ?? '') {
          'selecionada', 'convertida' => 'badge-green',
          'descartada', 'perdida' => 'badge-red',
          'shortlist' => 'badge-gold',
          default => 'badge-blue',
      };
      ?>
      <span class="badge <?php echo $badge; ?>"><?php echo View::e($p['status']); ?></span>
    </div>

    <div class="card-label">Score</div>
    <div class="card-title" style="margin-bottom:16px"><?php echo (int)($p['internal_score'] ?? 0); ?></div>

    <div class="card-label"><?php echo View::e(I18n::t('geral.descricao')); ?></div>
    <p style="font-size:.82rem;line-height:1.5;margin-bottom:16px;color:var(--text-muted)"><?php echo View::e(mb_substr($p['description'] ?? '', 0, 200)); ?>...</p>

    <?php if (!empty($p['differentials'])): ?>
    <div class="card-label"><?php echo View::e(I18n::t('propostas.diferenciais')); ?></div>
    <p style="font-size:.82rem;line-height:1.5;margin-bottom:16px;color:var(--text-muted)"><?php echo View::e(mb_substr($p['differentials'], 0, 150)); ?>...</p>
    <?php endif; ?>

    <div style="margin-top:auto;padding-top:16px;border-top:1px solid var(--border);display:flex;gap:8px">
      <a href="/equipe/propostas/<?php echo (int)$p['id']; ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.ver')); ?></a>
      <form method="POST" action="/equipe/propostas/<?php echo (int)$p['id']; ?>/status" style="display:inline">
        <?php echo Csrf::campo(); ?>
        <input type="hidden" name="status" value="shortlist"/>
        <button type="submit" class="btn btn-primary btn-sm">Shortlist</button>
      </form>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
