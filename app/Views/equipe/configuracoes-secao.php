<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('config.secao_' . ($secao ?? 'geral'))); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('sidebar.configuracoes')); ?></p>
  </div>
  <a href="/equipe/configuracoes" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/configuracoes/<?php echo View::e($secao ?? 'geral'); ?>">
    <?php echo Csrf::campo(); ?>

    <?php if (($secao ?? '') === 'branding'): ?>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('config.nome_empresa')); ?></label>
        <input type="text" name="company_name" value="<?php echo View::e($settings['company_name'] ?? 'Lexus Corretora'); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('config.slogan')); ?></label>
        <input type="text" name="slogan" value="<?php echo View::e($settings['slogan'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('config.logo_url')); ?></label>
        <input type="text" name="logo_url" value="<?php echo View::e($settings['logo_url'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('config.cor_primaria')); ?></label>
        <input type="color" name="primary_color" value="<?php echo View::e($settings['primary_color'] ?? '#B8945A'); ?>"/>
      </div>

    <?php elseif (($secao ?? '') === 'smtp'): ?>
      <div class="form-row">
        <div class="form-group">
          <label>SMTP Host</label>
          <input type="text" name="smtp_host" value="<?php echo View::e($settings['smtp_host'] ?? ''); ?>"/>
        </div>
        <div class="form-group">
          <label>SMTP Port</label>
          <input type="number" name="smtp_port" value="<?php echo View::e($settings['smtp_port'] ?? '587'); ?>"/>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>SMTP User</label>
          <input type="text" name="smtp_user" value="<?php echo View::e($settings['smtp_user'] ?? ''); ?>"/>
        </div>
        <div class="form-group">
          <label>SMTP Password</label>
          <input type="password" name="smtp_password" value=""/>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label><?php echo View::e(I18n::t('config.email_remetente')); ?></label>
          <input type="email" name="from_email" value="<?php echo View::e($settings['from_email'] ?? ''); ?>"/>
        </div>
        <div class="form-group">
          <label><?php echo View::e(I18n::t('config.nome_remetente')); ?></label>
          <input type="text" name="from_name" value="<?php echo View::e($settings['from_name'] ?? ''); ?>"/>
        </div>
      </div>

    <?php elseif (($secao ?? '') === 'seo'): ?>
      <div class="form-group">
        <label>Meta Title</label>
        <input type="text" name="meta_title" value="<?php echo View::e($settings['meta_title'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label>Meta Description</label>
        <textarea name="meta_description" rows="3"><?php echo View::e($settings['meta_description'] ?? ''); ?></textarea>
      </div>
      <div class="form-group">
        <label>OG Image URL</label>
        <input type="text" name="og_image" value="<?php echo View::e($settings['og_image'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label>Google Analytics ID</label>
        <input type="text" name="ga_id" value="<?php echo View::e($settings['ga_id'] ?? ''); ?>"/>
      </div>

    <?php elseif (($secao ?? '') === 'cobranca'): ?>
      <div class="form-row">
        <div class="form-group">
          <label>Gateway</label>
          <select name="payment_gateway">
            <option value="asaas" <?php echo ($settings['payment_gateway'] ?? '') === 'asaas' ? 'selected' : ''; ?>>Asaas</option>
            <option value="stripe" <?php echo ($settings['payment_gateway'] ?? '') === 'stripe' ? 'selected' : ''; ?>>Stripe</option>
          </select>
        </div>
        <div class="form-group">
          <label>API Key</label>
          <input type="password" name="payment_api_key" value=""/>
        </div>
      </div>
      <div class="form-group">
        <label>Webhook Secret</label>
        <input type="password" name="webhook_secret" value=""/>
      </div>

    <?php else: ?>
      <!-- Seção genérica -->
      <?php if (!empty($settings)): foreach ($settings as $key => $value): ?>
      <div class="form-group">
        <label><?php echo View::e($key); ?></label>
        <input type="text" name="<?php echo View::e($key); ?>" value="<?php echo View::e((string)$value); ?>"/>
      </div>
      <?php endforeach; endif; ?>
    <?php endif; ?>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
