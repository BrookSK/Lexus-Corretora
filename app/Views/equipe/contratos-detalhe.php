<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
$badge = match($contrato['status'] ?? '') {
    'formalizado' => 'badge-green',
    'cancelado' => 'badge-red',
    default => 'badge-gold',
};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('contratos.detalhe')); ?> #<?php echo (int)$contrato['id']; ?></h1>
    <p class="section-subtitle"><span class="badge <?php echo $badge; ?>"><?php echo View::e($contrato['status']); ?></span></p>
  </div>
  <a href="/equipe/contratos" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="cards-grid">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar.demandas')); ?></div>
    <div class="card-title"><a href="/equipe/demandas/<?php echo (int)$contrato['demanda_id']; ?>"><?php echo View::e($contrato['demanda_code'] ?? '#' . $contrato['demanda_id']); ?></a></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar.clientes')); ?></div>
    <div class="card-title"><?php echo View::e($contrato['cliente_nome'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar.parceiros')); ?></div>
    <div class="card-title"><?php echo View::e($contrato['parceiro_nome'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('propostas.valor')); ?></div>
    <div class="card-value"><?php echo I18n::formatarMoeda($contrato['amount']); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('contratos.data_formalizacao')); ?></div>
    <div class="card-title"><?php echo View::e($contrato['formalized_at'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.criado_em')); ?></div>
    <div class="card-title"><?php echo View::e($contrato['created_at']); ?></div>
  </div>
</div>

<?php if (!empty($contrato['notes'])): ?>
<div class="card" style="margin-bottom:24px">
  <div class="card-label" style="margin-bottom:8px"><?php echo View::e(I18n::t('demandas.observacoes')); ?></div>
  <p style="font-size:.88rem;line-height:1.6"><?php echo nl2br(View::e($contrato['notes'])); ?></p>
</div>
<?php endif; ?>

<?php if (!empty($contrato['internal_notes'])): ?>
<div class="card" style="margin-bottom:24px;border-left:3px solid var(--gold)">
  <div class="card-label" style="margin-bottom:8px"><?php echo View::e(I18n::t('demandas.notas_internas')); ?></div>
  <p style="font-size:.88rem;line-height:1.6"><?php echo nl2br(View::e($contrato['internal_notes'])); ?></p>
</div>
<?php endif; ?>

<!-- Alterar status -->
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('geral.acoes')); ?></h2></div>
</div>
<div class="card">
  <form method="POST" action="/equipe/contratos/<?php echo (int)$contrato['id']; ?>/status" style="display:flex;gap:12px;align-items:flex-end">
    <?php echo Csrf::campo(); ?>
    <div class="form-group" style="margin:0;flex:1">
      <label><?php echo View::e(I18n::t('geral.status')); ?></label>
      <select name="status">
        <?php foreach (['em_formalizacao','formalizado','pendente_confirmacao','cancelado'] as $s): ?>
        <option value="<?php echo $s; ?>" <?php echo ($contrato['status'] ?? '') === $s ? 'selected' : ''; ?>><?php echo ucfirst(str_replace('_', ' ', $s)); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary btn-sm"><?php echo View::e(I18n::t('geral.atualizar')); ?></button>
  </form>
</div>
