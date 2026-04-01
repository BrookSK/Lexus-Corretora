<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
$badge = match($proposta['status'] ?? '') {
    'selecionada', 'convertida' => 'badge-green',
    'descartada', 'perdida' => 'badge-red',
    'shortlist' => 'badge-gold',
    default => 'badge-blue',
};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('propostas.detalhe')); ?></h1>
    <p class="section-subtitle">
      <span class="badge <?php echo $badge; ?>"><?php echo View::e($proposta['status']); ?></span>
      <?php if ($proposta['is_shortlisted'] ?? false): ?>
        <span class="badge badge-gold">Shortlist</span>
      <?php endif; ?>
    </p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="/equipe/demandas/<?php echo (int)$proposta['demanda_id']; ?>" class="btn btn-secondary"><?php echo View::e(I18n::t('demandas.ver_demanda')); ?></a>
    <a href="/equipe/propostas" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
  </div>
</div>

<div class="cards-grid">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar.parceiros')); ?></div>
    <div class="card-title"><?php echo View::e($proposta['parceiro_nome'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('propostas.valor')); ?></div>
    <div class="card-value"><?php echo I18n::formatarMoeda($proposta['amount']); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('propostas.prazo_dias')); ?></div>
    <div class="card-value"><?php echo (int)($proposta['deadline_days'] ?? 0); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('propostas.validade')); ?></div>
    <div class="card-title"><?php echo View::e($proposta['valid_until'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label">Score Interno</div>
    <div class="card-value"><?php echo (int)($proposta['internal_score'] ?? 0); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.criado_em')); ?></div>
    <div class="card-title"><?php echo View::e($proposta['created_at']); ?></div>
  </div>
</div>

<div class="card" style="margin-bottom:24px">
  <div class="card-label" style="margin-bottom:8px"><?php echo View::e(I18n::t('geral.descricao')); ?></div>
  <p style="font-size:.88rem;line-height:1.6"><?php echo nl2br(View::e($proposta['description'] ?? '')); ?></p>
</div>

<?php if (!empty($proposta['differentials'])): ?>
<div class="card" style="margin-bottom:24px">
  <div class="card-label" style="margin-bottom:8px"><?php echo View::e(I18n::t('propostas.diferenciais')); ?></div>
  <p style="font-size:.88rem;line-height:1.6"><?php echo nl2br(View::e($proposta['differentials'])); ?></p>
</div>
<?php endif; ?>

<?php if (!empty($proposta['conditions'])): ?>
<div class="card" style="margin-bottom:24px">
  <div class="card-label" style="margin-bottom:8px"><?php echo View::e(I18n::t('propostas.condicoes')); ?></div>
  <p style="font-size:.88rem;line-height:1.6"><?php echo nl2br(View::e($proposta['conditions'])); ?></p>
</div>
<?php endif; ?>

<!-- Ações -->
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('geral.acoes')); ?></h2></div>
</div>
<div style="display:flex;gap:12px;flex-wrap:wrap">
  <form method="POST" action="/equipe/propostas/<?php echo (int)$proposta['id']; ?>/status" style="display:inline">
    <?php echo Csrf::campo(); ?>
    <input type="hidden" name="status" value="shortlist"/>
    <button type="submit" class="btn btn-primary btn-sm">Shortlist</button>
  </form>
  <form method="POST" action="/equipe/propostas/<?php echo (int)$proposta['id']; ?>/status" style="display:inline">
    <?php echo Csrf::campo(); ?>
    <input type="hidden" name="status" value="selecionada"/>
    <button type="submit" class="btn btn-primary btn-sm"><?php echo View::e(I18n::t('propostas.aprovar')); ?></button>
  </form>
  <form method="POST" action="/equipe/propostas/<?php echo (int)$proposta['id']; ?>/status" style="display:inline">
    <?php echo Csrf::campo(); ?>
    <input type="hidden" name="status" value="descartada"/>
    <button type="submit" class="btn btn-danger btn-sm"><?php echo View::e(I18n::t('propostas.rejeitar')); ?></button>
  </form>
</div>

<?php if (!empty($proposta['internal_notes'])): ?>
<div class="card" style="margin-top:24px;border-left:3px solid var(--gold)">
  <div class="card-label" style="margin-bottom:8px"><?php echo View::e(I18n::t('demandas.notas_internas')); ?></div>
  <p style="font-size:.88rem;line-height:1.6"><?php echo nl2br(View::e($proposta['internal_notes'])); ?></p>
</div>
<?php endif; ?>
