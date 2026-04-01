<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('crm.novo_lead')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('crm.subtitulo_criar')); ?></p>
  </div>
  <a href="/equipe/crm" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/crm/novo">
    <?php echo Csrf::campo(); ?>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.nome')); ?> *</label>
        <input type="text" name="name" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.email')); ?></label>
        <input type="email" name="email"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.telefone')); ?></label>
        <input type="text" name="phone"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.empresa')); ?></label>
        <input type="text" name="company"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('crm.origem')); ?></label>
        <input type="text" name="origin" placeholder="site, indicação, evento..."/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('crm.responsavel')); ?></label>
        <select name="assigned_to">
          <option value="">— <?php echo View::e(I18n::t('geral.selecione')); ?> —</option>
          <?php if (!empty($usuarios)): foreach ($usuarios as $u): ?>
          <option value="<?php echo (int)$u['id']; ?>"><?php echo View::e($u['name']); ?></option>
          <?php endforeach; endif; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('demandas.observacoes')); ?></label>
      <textarea name="notes" rows="3"></textarea>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
