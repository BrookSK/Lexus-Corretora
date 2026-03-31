<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
$badge = match($lead['status'] ?? '') {
    'convertido' => 'badge-green',
    'perdido' => 'badge-red',
    'qualificado' => 'badge-gold',
    default => 'badge-blue',
};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e($lead['name']); ?></h1>
    <p class="section-subtitle"><span class="badge <?php echo $badge; ?>"><?php echo View::e($lead['status']); ?></span></p>
  </div>
  <div style="display:flex;gap:8px">
    <?php if (($lead['status'] ?? '') !== 'convertido'): ?>
    <form method="POST" action="/equipe/crm/<?php echo (int)$lead['id']; ?>/converter" style="display:inline">
      <?php echo Csrf::campo(); ?>
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('crm.converter_cliente')); ?></button>
    </form>
    <?php endif; ?>
    <a href="/equipe/crm" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
  </div>
</div>

<div class="cards-grid">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('auth.email')); ?></div>
    <div class="card-title"><?php echo View::e($lead['email'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.telefone')); ?></div>
    <div class="card-title"><?php echo View::e($lead['phone'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.empresa')); ?></div>
    <div class="card-title"><?php echo View::e($lead['company'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('crm.origem')); ?></div>
    <div class="card-title"><?php echo View::e($lead['origin'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('crm.responsavel')); ?></div>
    <div class="card-title"><?php echo View::e($lead['assigned_nome'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.criado_em')); ?></div>
    <div class="card-title"><?php echo View::e($lead['created_at']); ?></div>
  </div>
</div>

<?php if (!empty($lead['notes'])): ?>
<div class="card" style="margin-bottom:24px">
  <div class="card-label" style="margin-bottom:8px"><?php echo View::e(I18n::t('demandas.observacoes')); ?></div>
  <p style="font-size:.88rem;line-height:1.6"><?php echo nl2br(View::e($lead['notes'])); ?></p>
</div>
<?php endif; ?>

<?php if (!empty($lead['converted_to_cliente_id'])): ?>
<div class="card" style="border-left:3px solid #22c55e">
  <div class="card-label" style="margin-bottom:8px"><?php echo View::e(I18n::t('crm.convertido_para')); ?></div>
  <a href="/equipe/clientes/<?php echo (int)$lead['converted_to_cliente_id']; ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('crm.ver_cliente')); ?></a>
</div>
<?php endif; ?>

<!-- Alterar status -->
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('geral.acoes')); ?></h2></div>
</div>
<div class="card">
  <form method="POST" action="/equipe/crm/<?php echo (int)$lead['id']; ?>/status" style="display:flex;gap:12px;align-items:flex-end">
    <?php echo Csrf::campo(); ?>
    <div class="form-group" style="margin:0;flex:1">
      <label><?php echo View::e(I18n::t('geral.status')); ?></label>
      <select name="status">
        <?php foreach (['novo','contatado','qualificado','convertido','perdido'] as $s): ?>
        <option value="<?php echo $s; ?>" <?php echo ($lead['status'] ?? '') === $s ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary btn-sm"><?php echo View::e(I18n::t('geral.atualizar')); ?></button>
  </form>
</div>
