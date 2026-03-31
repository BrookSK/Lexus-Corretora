<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf, Settings};
$secao = $secao ?? 'geral';
$settings = $settings ?? [];
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(ucfirst($secao)); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('sidebar.configuracoes')); ?></p>
  </div>
  <a href="/equipe/configuracoes" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/configuracoes/<?php echo View::e($secao); ?>" enctype="multipart/form-data">
    <?php echo Csrf::campo(); ?>

    <?php if ($secao === 'branding'): ?>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('config.nome_empresa')); ?></label>
        <input type="text" name="sistema.nome" value="<?php echo View::e($settings['sistema.nome'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('config.slogan')); ?></label>
        <input type="text" name="sistema.slogan" value="<?php echo View::e($settings['sistema.slogan'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label>Logo</label>
        <?php if (!empty($settings['sistema.logo'])): ?>
          <div style="margin-bottom:10px"><img src="<?php echo View::e($settings['sistema.logo']); ?>" alt="Logo" style="max-height:60px;background:#0C0C0A;padding:8px 16px"></div>
        <?php endif; ?>
        <input type="file" name="logo" accept="image/*"/>
        <small style="font-size:.75rem;color:var(--text-muted)">PNG, SVG, JPG — máx. 5MB</small>
      </div>
      <div class="form-group">
        <label>Favicon</label>
        <?php if (!empty($settings['sistema.favicon'])): ?>
          <div style="margin-bottom:10px"><img src="<?php echo View::e($settings['sistema.favicon']); ?>" alt="Favicon" style="max-height:32px"></div>
        <?php endif; ?>
        <input type="file" name="favicon" accept="image/*,.ico"/>
        <small style="font-size:.75rem;color:var(--text-muted)">ICO, PNG — máx. 5MB</small>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('config.cor_primaria')); ?></label>
        <input type="color" name="sistema.cor_primaria" value="<?php echo View::e($settings['sistema.cor_primaria'] ?? '#B8945A'); ?>" style="width:80px;height:40px;padding:2px;cursor:pointer"/>
      </div>
      <div class="form-group">
        <label>Copyright</label>
        <input type="text" name="sistema.copyright" value="<?php echo View::e($settings['sistema.copyright'] ?? ''); ?>"/>
      </div>

    <?php elseif ($secao === 'smtp'): ?>
      <div class="form-row">
        <div class="form-group">
          <label>SMTP Host</label>
          <input type="text" name="smtp.host" value="<?php echo View::e($settings['smtp.host'] ?? ''); ?>" placeholder="smtp.gmail.com"/>
        </div>
        <div class="form-group">
          <label>Porta</label>
          <input type="number" name="smtp.porta" value="<?php echo View::e((string)($settings['smtp.porta'] ?? '587')); ?>"/>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Usuário</label>
          <input type="text" name="smtp.usuario" value="<?php echo View::e($settings['smtp.usuario'] ?? ''); ?>"/>
        </div>
        <div class="form-group">
          <label>Senha</label>
          <input type="password" name="smtp.senha" value="" placeholder="Deixe vazio para manter"/>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label><?php echo View::e(I18n::t('config.email_remetente')); ?></label>
          <input type="email" name="smtp.de_email" value="<?php echo View::e($settings['smtp.de_email'] ?? ''); ?>"/>
        </div>
        <div class="form-group">
          <label><?php echo View::e(I18n::t('config.nome_remetente')); ?></label>
          <input type="text" name="smtp.de_nome" value="<?php echo View::e($settings['smtp.de_nome'] ?? ''); ?>"/>
        </div>
      </div>

    <?php elseif ($secao === 'seo'): ?>
      <div class="form-group">
        <label>Meta Title</label>
        <input type="text" name="seo.meta_title" value="<?php echo View::e($settings['seo.meta_title'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label>Meta Description</label>
        <textarea name="seo.meta_description" rows="3"><?php echo View::e($settings['seo.meta_description'] ?? ''); ?></textarea>
      </div>
      <div class="form-group">
        <label>Imagem Open Graph</label>
        <?php if (!empty($settings['seo.og_image'])): ?>
          <div style="margin-bottom:10px"><img src="<?php echo View::e($settings['seo.og_image']); ?>" alt="OG Image" style="max-height:80px;border:1px solid var(--border)"></div>
        <?php endif; ?>
        <input type="file" name="og_image" accept="image/*"/>
        <small style="font-size:.75rem;color:var(--text-muted)">Imagem para compartilhamento em redes sociais — 1200x630px recomendado</small>
      </div>
      <div class="form-group">
        <label>Google Analytics ID</label>
        <input type="text" name="seo.ga_id" value="<?php echo View::e($settings['seo.ga_id'] ?? ''); ?>" placeholder="G-XXXXXXXXXX"/>
      </div>

    <?php elseif ($secao === 'cobranca'): ?>
      <h3 style="font-size:.95rem;font-weight:500;margin-bottom:20px;color:var(--gold)">Stripe</h3>
      <div class="form-group">
        <label>Ambiente</label>
        <select name="stripe.mode">
          <option value="sandbox" <?php echo ($settings['stripe.mode'] ?? '') === 'sandbox' ? 'selected' : ''; ?>>Sandbox / Test</option>
          <option value="production" <?php echo ($settings['stripe.mode'] ?? '') === 'production' ? 'selected' : ''; ?>>Produção / Live</option>
        </select>
      </div>
      <div style="background:rgba(184,148,90,.04);border:1px solid rgba(184,148,90,.15);padding:20px;margin-bottom:20px">
        <p style="font-size:.78rem;font-weight:500;text-transform:uppercase;letter-spacing:.1em;color:var(--gold);margin-bottom:12px">Sandbox / Test</p>
        <div class="form-group"><label>Publishable Key (test)</label><input type="text" name="stripe.test_publishable_key" value="<?php echo View::e($settings['stripe.test_publishable_key'] ?? ''); ?>" placeholder="pk_test_..."/></div>
        <div class="form-group"><label>Secret Key (test)</label><input type="password" name="stripe.test_secret_key" value="" placeholder="Deixe vazio para manter"/></div>
        <div class="form-group" style="margin-bottom:0"><label>Webhook Secret (test)</label><input type="password" name="stripe.test_webhook_secret" value="" placeholder="Deixe vazio para manter"/></div>
      </div>
      <div style="background:rgba(34,197,94,.04);border:1px solid rgba(34,197,94,.15);padding:20px;margin-bottom:24px">
        <p style="font-size:.78rem;font-weight:500;text-transform:uppercase;letter-spacing:.1em;color:#166534;margin-bottom:12px">Produção / Live</p>
        <div class="form-group"><label>Publishable Key (live)</label><input type="text" name="stripe.live_publishable_key" value="<?php echo View::e($settings['stripe.live_publishable_key'] ?? ''); ?>" placeholder="pk_live_..."/></div>
        <div class="form-group"><label>Secret Key (live)</label><input type="password" name="stripe.live_secret_key" value="" placeholder="Deixe vazio para manter"/></div>
        <div class="form-group" style="margin-bottom:0"><label>Webhook Secret (live)</label><input type="password" name="stripe.live_webhook_secret" value="" placeholder="Deixe vazio para manter"/></div>
      </div>

      <h3 style="font-size:.95rem;font-weight:500;margin-bottom:20px;color:var(--gold)">Asaas</h3>
      <div class="form-group">
        <label>Ambiente</label>
        <select name="asaas.mode">
          <option value="sandbox" <?php echo ($settings['asaas.mode'] ?? '') === 'sandbox' ? 'selected' : ''; ?>>Sandbox</option>
          <option value="production" <?php echo ($settings['asaas.mode'] ?? '') === 'production' ? 'selected' : ''; ?>>Produção</option>
        </select>
      </div>
      <div style="background:rgba(184,148,90,.04);border:1px solid rgba(184,148,90,.15);padding:20px;margin-bottom:20px">
        <p style="font-size:.78rem;font-weight:500;text-transform:uppercase;letter-spacing:.1em;color:var(--gold);margin-bottom:12px">Sandbox</p>
        <div class="form-group"><label>API Key (sandbox)</label><input type="password" name="asaas.sandbox_api_key" value="" placeholder="Deixe vazio para manter"/></div>
        <div class="form-group" style="margin-bottom:0"><label>Webhook Token (sandbox)</label><input type="password" name="asaas.sandbox_webhook_token" value="" placeholder="Deixe vazio para manter"/></div>
      </div>
      <div style="background:rgba(34,197,94,.04);border:1px solid rgba(34,197,94,.15);padding:20px;margin-bottom:24px">
        <p style="font-size:.78rem;font-weight:500;text-transform:uppercase;letter-spacing:.1em;color:#166534;margin-bottom:12px">Produção</p>
        <div class="form-group"><label>API Key (produção)</label><input type="password" name="asaas.production_api_key" value="" placeholder="Deixe vazio para manter"/></div>
        <div class="form-group" style="margin-bottom:0"><label>Webhook Token (produção)</label><input type="password" name="asaas.production_webhook_token" value="" placeholder="Deixe vazio para manter"/></div>
      </div>

      <div class="card" style="background:var(--bg);border-left:3px solid var(--gold);padding:20px;margin-bottom:0">
        <p style="font-size:.82rem;font-weight:500;margin-bottom:8px">URLs de Webhook</p>
        <p style="font-size:.82rem;color:var(--text-muted)">Stripe: <code style="background:rgba(0,0,0,.06);padding:2px 6px"><?php echo View::e(($_SERVER['REQUEST_SCHEME'] ?? 'https') . '://' . ($_SERVER['HTTP_HOST'] ?? '')); ?>/webhooks/stripe</code></p>
        <p style="font-size:.82rem;color:var(--text-muted);margin-top:4px">Asaas: <code style="background:rgba(0,0,0,.06);padding:2px 6px"><?php echo View::e(($_SERVER['REQUEST_SCHEME'] ?? 'https') . '://' . ($_SERVER['HTTP_HOST'] ?? '')); ?>/webhooks/asaas</code></p>
      </div>

    <?php elseif ($secao === 'legal'): ?>
      <div class="form-group">
        <label>Termos de Uso (HTML)</label>
        <textarea name="legal.termos" rows="20" style="font-family:monospace;font-size:.82rem"><?php echo View::e($settings['legal.termos'] ?? ''); ?></textarea>
        <small style="font-size:.75rem;color:var(--text-muted)">Aceita HTML. Editável diretamente.</small>
      </div>
      <div class="form-group">
        <label>Política de Privacidade (HTML)</label>
        <textarea name="legal.privacidade" rows="20" style="font-family:monospace;font-size:.82rem"><?php echo View::e($settings['legal.privacidade'] ?? ''); ?></textarea>
        <small style="font-size:.75rem;color:var(--text-muted)">Aceita HTML. Editável diretamente.</small>
      </div>

    <?php elseif ($secao === 'geral'): ?>
      <div class="form-row">
        <div class="form-group">
          <label>Idioma Padrão</label>
          <select name="sistema.idioma_padrao">
            <option value="pt-BR" <?php echo ($settings['sistema.idioma_padrao'] ?? '') === 'pt-BR' ? 'selected' : ''; ?>>Português (BR)</option>
            <option value="en-US" <?php echo ($settings['sistema.idioma_padrao'] ?? '') === 'en-US' ? 'selected' : ''; ?>>English (US)</option>
            <option value="es-ES" <?php echo ($settings['sistema.idioma_padrao'] ?? '') === 'es-ES' ? 'selected' : ''; ?>>Español (ES)</option>
          </select>
        </div>
        <div class="form-group">
          <label>Moeda Padrão</label>
          <select name="sistema.moeda_padrao">
            <option value="BRL" <?php echo ($settings['sistema.moeda_padrao'] ?? '') === 'BRL' ? 'selected' : ''; ?>>BRL — Real</option>
            <option value="USD" <?php echo ($settings['sistema.moeda_padrao'] ?? '') === 'USD' ? 'selected' : ''; ?>>USD — Dólar</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label>Timezone</label>
        <input type="text" name="sistema.timezone" value="<?php echo View::e($settings['sistema.timezone'] ?? 'America/Sao_Paulo'); ?>" placeholder="America/Sao_Paulo"/>
      </div>

    <?php else: ?>
      <p style="color:var(--text-muted);font-size:.88rem">Seção "<?php echo View::e($secao); ?>" em desenvolvimento.</p>
    <?php endif; ?>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
