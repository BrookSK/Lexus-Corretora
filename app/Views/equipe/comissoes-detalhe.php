<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
$badge = match($comissao['status'] ?? '') {
    'recebida' => 'badge-green',
    'cancelada', 'atrasada' => 'badge-red',
    'confirmada', 'faturada' => 'badge-blue',
    default => 'badge-gold',
};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('comissoes.detalhe')); ?> #<?php echo (int)$comissao['id']; ?></h1>
    <p class="section-subtitle"><span class="badge <?php echo $badge; ?>"><?php echo View::e($comissao['status']); ?></span></p>
  </div>
  <a href="/equipe/comissoes" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>
<?php
$tipo = $comissao['tipo'] ?? 'recebimento';
$tipoBadge = $tipo === 'recebimento' ? 'badge-green' : 'badge-red';
$tipoLabel = $tipo === 'recebimento' ? 'Recebimento — parceiro aprovado paga Lexus' : 'Pagamento — Lexus repassa ao parceiro de origem';
?>
<div class="card" style="margin-bottom:20px;display:flex;align-items:center;gap:12px">
  <span class="badge <?php echo $tipoBadge; ?>"><?php echo $tipoLabel; ?></span>
</div>

<div class="cards-grid">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar.demandas')); ?></div>
    <div class="card-title"><a href="/equipe/demandas/<?php echo (int)$comissao['demanda_id']; ?>"><?php echo View::e($comissao['demanda_code'] ?? '#' . $comissao['demanda_id']); ?></a></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo $tipo === 'recebimento' ? 'Parceiro (pagador)' : 'Parceiro de Origem (recebedor)'; ?></div>
    <div class="card-title"><?php echo View::e($comissao['parceiro_nome'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('comissoes.valor_base')); ?></div>
    <div class="card-value"><?php echo I18n::formatarMoeda($comissao['base_amount']); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('comissoes.percentual')); ?></div>
    <div class="card-value"><?php echo number_format((float)$comissao['commission_pct'], 2); ?>%</div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('comissoes.valor_comissao')); ?></div>
    <div class="card-value"><?php echo I18n::formatarMoeda($comissao['commission_amount']); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('comissoes.data_prevista')); ?></div>
    <div class="card-title"><?php echo View::e($comissao['expected_date'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('comissoes.data_recebimento')); ?></div>
    <div class="card-title"><?php echo View::e($comissao['received_date'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.criado_em')); ?></div>
    <div class="card-title"><?php echo View::e($comissao['created_at']); ?></div>
  </div>
</div>

<?php if (!empty($comissao['notes'])): ?>
<div class="card" style="margin-bottom:24px">
  <div class="card-label" style="margin-bottom:8px"><?php echo View::e(I18n::t('demandas.observacoes')); ?></div>
  <p style="font-size:.88rem;line-height:1.6"><?php echo nl2br(View::e($comissao['notes'])); ?></p>
</div>
<?php endif; ?>

<!-- Alterar status -->
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('geral.acoes')); ?></h2></div>
</div>
<div class="card">
  <form method="POST" action="/equipe/comissoes/<?php echo (int)$comissao['id']; ?>/status" style="display:flex;gap:12px;align-items:flex-end">
    <?php echo Csrf::campo(); ?>
    <div class="form-group" style="margin:0;flex:1">
      <label><?php echo View::e(I18n::t('geral.status')); ?></label>
      <select name="status">
        <?php foreach (['prevista','aguardando_confirmacao','confirmada','faturada','recebida','atrasada','cancelada'] as $s): ?>
        <option value="<?php echo $s; ?>" <?php echo ($comissao['status'] ?? '') === $s ? 'selected' : ''; ?>><?php echo ucfirst(str_replace('_', ' ', $s)); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary btn-sm"><?php echo View::e(I18n::t('geral.atualizar')); ?></button>
  </form>
</div>
