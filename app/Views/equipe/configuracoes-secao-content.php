<?php
// Conteúdo das seções de configuração
// Variáveis disponíveis: $secao, $settings, $V (View), $I (I18n), $C (Csrf)

if ($secao === 'branding'): ?>
  <div class="form-group">
    <label><?php echo $V::e($I::t('config.nome_empresa')); ?></label>
    <input type="text" name="sistema.nome" value="<?php echo $V::e($settings['sistema.nome'] ?? ''); ?>"/>
  </div>
  <div class="form-group">
    <label><?php echo $V::e($I::t('config.slogan')); ?></label>
    <input type="text" name="sistema.slogan" value="<?php echo $V::e($settings['sistema.slogan'] ?? ''); ?>"/>
  </div>
  <div class="form-group">
    <label>Logo</label>
    <?php if (!empty($settings['sistema.logo'])): ?>
      <div style="margin-bottom:10px"><img src="<?php echo $V::e($settings['sistema.logo']); ?>" alt="Logo" style="max-height:60px;background:#0C0C0A;padding:8px 16px"></div>
    <?php endif; ?>
    <input type="file" name="logo" accept="image/*"/>
    <small style="font-size:.75rem;color:var(--text-muted)">PNG, SVG, JPG — máx. 5MB</small>
  </div>
  <div class="form-group">
    <label>Favicon</label>
    <?php if (!empty($settings['sistema.favicon'])): ?>
      <div style="margin-bottom:10px"><img src="<?php echo $V::e($settings['sistema.favicon']); ?>" alt="Favicon" style="max-height:32px"></div>
    <?php endif; ?>
    <input type="file" name="favicon" accept="image/*,.ico"/>
    <small style="font-size:.75rem;color:var(--text-muted)">ICO, PNG — máx. 5MB</small>
  </div>
  <div class="form-group">
    <label><?php echo $V::e($I::t('config.cor_primaria')); ?></label>
    <input type="color" name="sistema.cor_primaria" value="<?php echo $V::e($settings['sistema.cor_primaria'] ?? '#B8945A'); ?>" style="width:80px;height:40px;padding:2px;cursor:pointer"/>
  </div>
  <div class="form-group">
    <label>Copyright</label>
    <input type="text" name="sistema.copyright" value="<?php echo $V::e($settings['sistema.copyright'] ?? ''); ?>"/>
  </div>

<?php elseif ($secao === 'smtp'): ?>
  <div class="form-row">
    <div class="form-group">
      <label>SMTP Host</label>
      <input type="text" name="smtp.host" value="<?php echo $V::e($settings['smtp.host'] ?? ''); ?>" placeholder="smtp.gmail.com"/>
    </div>
    <div class="form-group">
      <label>Porta</label>
      <input type="number" name="smtp.porta" value="<?php echo $V::e((string)($settings['smtp.porta'] ?? '587')); ?>"/>
    </div>
  </div>
  <div class="form-row">
    <div class="form-group">
      <label>Usuário</label>
      <input type="text" name="smtp.usuario" value="<?php echo $V::e($settings['smtp.usuario'] ?? ''); ?>"/>
    </div>
    <div class="form-group">
      <label>Senha</label>
      <input type="password" name="smtp.senha" value="" placeholder="Deixe vazio para manter"/>
    </div>
  </div>
  <div class="form-row">
    <div class="form-group">
      <label><?php echo $V::e($I::t('config.email_remetente')); ?></label>
      <input type="email" name="smtp.de_email" value="<?php echo $V::e($settings['smtp.de_email'] ?? ''); ?>"/>
    </div>
    <div class="form-group">
      <label><?php echo $V::e($I::t('config.nome_remetente')); ?></label>
      <input type="text" name="smtp.de_nome" value="<?php echo $V::e($settings['smtp.de_nome'] ?? ''); ?>"/>
    </div>
  </div>

  <!-- Teste de SMTP -->
  <div style="margin-top:32px;padding-top:32px;border-top:1px solid var(--border)">
    <h3 style="font-size:.95rem;font-weight:500;margin-bottom:16px;color:var(--gold)">Testar Configuração SMTP</h3>
    <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:16px">Envie um e-mail de teste para verificar se as configurações estão corretas.</p>
    <div class="form-group">
      <label>E-mail de destino para teste</label>
      <input type="email" id="smtpTestEmail" placeholder="seu@email.com" style="max-width:400px"/>
    </div>
    <button type="button" onclick="testarSMTP()" class="btn btn-secondary" id="btnTestarSMTP">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px">
        <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
      </svg>
      Enviar E-mail de Teste
    </button>
    <div id="smtpTestResult" style="margin-top:12px;display:none"></div>
  </div>

  <script>
  function testarSMTP() {
    const email = document.getElementById('smtpTestEmail').value;
    const btn = document.getElementById('btnTestarSMTP');
    const result = document.getElementById('smtpTestResult');
    
    if (!email || !email.includes('@')) {
      result.style.display = 'block';
      result.innerHTML = '<div style="padding:12px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:#dc2626;font-size:.82rem">Por favor, insira um e-mail válido.</div>';
      return;
    }
    
    btn.disabled = true;
    btn.textContent = 'Enviando...';
    result.style.display = 'none';
    
    fetch('/equipe/configuracoes/smtp/testar', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': document.querySelector('meta[name=csrf-token]')?.content || ''
      },
      body: JSON.stringify({ email: email })
    })
    .then(r => r.json())
    .then(data => {
      result.style.display = 'block';
      if (data.success) {
        result.innerHTML = '<div style="padding:12px;background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);color:#166534;font-size:.82rem"><strong>✓ Sucesso!</strong> E-mail de teste enviado para ' + email + '. Verifique sua caixa de entrada.</div>';
      } else {
        result.innerHTML = '<div style="padding:12px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:#dc2626;font-size:.82rem"><strong>✗ Erro:</strong> ' + (data.message || 'Falha ao enviar e-mail de teste.') + '</div>';
      }
    })
    .catch(err => {
      result.style.display = 'block';
      result.innerHTML = '<div style="padding:12px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:#dc2626;font-size:.82rem"><strong>✗ Erro:</strong> ' + err.message + '</div>';
    })
    .finally(() => {
      btn.disabled = false;
      btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>Enviar E-mail de Teste';
    });
  }
  </script>

<?php elseif ($secao === 'seo'): ?>
  <div class="form-group">
    <label>Meta Title</label>
    <input type="text" name="seo.meta_title" value="<?php echo $V::e($settings['seo.meta_title'] ?? ''); ?>"/>
  </div>
  <div class="form-group">
    <label>Meta Description</label>
    <textarea name="seo.meta_description" rows="3"><?php echo $V::e($settings['seo.meta_description'] ?? ''); ?></textarea>
  </div>
  <div class="form-group">
    <label>Imagem Open Graph</label>
    <?php if (!empty($settings['seo.og_image'])): ?>
      <div style="margin-bottom:10px"><img src="<?php echo $V::e($settings['seo.og_image']); ?>" alt="OG Image" style="max-height:80px;border:1px solid var(--border)"></div>
    <?php endif; ?>
    <input type="file" name="og_image" accept="image/*"/>
    <small style="font-size:.75rem;color:var(--text-muted)">Imagem para compartilhamento em redes sociais — 1200x630px recomendado</small>
  </div>
  <div class="form-group">
    <label>Google Analytics ID</label>
    <input type="text" name="seo.ga_id" value="<?php echo $V::e($settings['seo.ga_id'] ?? ''); ?>" placeholder="G-XXXXXXXXXX"/>
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
    <div class="form-group"><label>Publishable Key (test)</label><input type="text" name="stripe.test_publishable_key" value="<?php echo $V::e($settings['stripe.test_publishable_key'] ?? ''); ?>" placeholder="pk_test_..."/></div>
    <div class="form-group"><label>Secret Key (test)</label><input type="password" name="stripe.test_secret_key" value="" placeholder="Deixe vazio para manter"/></div>
    <div class="form-group" style="margin-bottom:0"><label>Webhook Secret (test)</label><input type="password" name="stripe.test_webhook_secret" value="" placeholder="Deixe vazio para manter"/></div>
  </div>
  <div style="background:rgba(34,197,94,.04);border:1px solid rgba(34,197,94,.15);padding:20px;margin-bottom:24px">
    <p style="font-size:.78rem;font-weight:500;text-transform:uppercase;letter-spacing:.1em;color:#166534;margin-bottom:12px">Produção / Live</p>
    <div class="form-group"><label>Publishable Key (live)</label><input type="text" name="stripe.live_publishable_key" value="<?php echo $V::e($settings['stripe.live_publishable_key'] ?? ''); ?>" placeholder="pk_live_..."/></div>
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
    <p style="font-size:.82rem;color:var(--text-muted)">Stripe: <code style="background:rgba(0,0,0,.06);padding:2px 6px"><?php echo $V::e(($_SERVER['REQUEST_SCHEME'] ?? 'https') . '://' . ($_SERVER['HTTP_HOST'] ?? '')); ?>/webhooks/stripe</code></p>
    <p style="font-size:.82rem;color:var(--text-muted);margin-top:4px">Asaas: <code style="background:rgba(0,0,0,.06);padding:2px 6px"><?php echo $V::e(($_SERVER['REQUEST_SCHEME'] ?? 'https') . '://' . ($_SERVER['HTTP_HOST'] ?? '')); ?>/webhooks/asaas</code></p>
  </div>

<?php elseif ($secao === 'notificacoes'): ?>
  <p style="color:var(--text-muted);font-size:.88rem">Configure os eventos de notificação em <a href="/equipe/notificacoes" style="color:var(--gold)">Notificações</a>.</p>

<?php elseif ($secao === 'integracao'): ?>
  <p style="color:var(--text-muted);font-size:.88rem">Integrações disponíveis: Trello (veja aba Trello).</p>

<?php elseif ($secao === 'seguranca'): ?>
  <div class="form-group">
    <label>Senha mínima (caracteres)</label>
    <input type="number" name="seguranca.senha_min" value="<?php echo $V::e((string)($settings['seguranca.senha_min'] ?? '8')); ?>" min="6" max="32"/>
  </div>
  <div class="form-group">
    <label>Sessão expira em (minutos)</label>
    <input type="number" name="seguranca.sessao_expira" value="<?php echo $V::e((string)($settings['seguranca.sessao_expira'] ?? '1440')); ?>" min="30"/>
  </div>
  <div class="form-group">
    <label>2FA obrigatório</label>
    <select name="seguranca.2fa_obrigatorio">
      <option value="0" <?php echo empty($settings['seguranca.2fa_obrigatorio']) ? 'selected' : ''; ?>>Não</option>
      <option value="1" <?php echo !empty($settings['seguranca.2fa_obrigatorio']) ? 'selected' : ''; ?>>Sim</option>
    </select>
  </div>

<?php elseif ($secao === 'legal'): ?>
  <div class="form-group">
    <label>Termos de Uso (HTML)</label>
    <textarea name="legal.termos" rows="20" style="font-family:monospace;font-size:.82rem"><?php echo $V::e($settings['legal.termos'] ?? ''); ?></textarea>
    <small style="font-size:.75rem;color:var(--text-muted)">Aceita HTML. Editável diretamente.</small>
  </div>
  <div class="form-group">
    <label>Política de Privacidade (HTML)</label>
    <textarea name="legal.privacidade" rows="20" style="font-family:monospace;font-size:.82rem"><?php echo $V::e($settings['legal.privacidade'] ?? ''); ?></textarea>
    <small style="font-size:.75rem;color:var(--text-muted)">Aceita HTML. Editável diretamente.</small>
  </div>

<?php elseif ($secao === 'trello'): ?>
  <p style="color:var(--text-muted);font-size:.88rem">Integração com Trello. Configure em <a href="/equipe/configuracoes/trello" style="color:var(--gold)">Configurações > Trello</a> (rota antiga).</p>

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
    <input type="text" name="sistema.timezone" value="<?php echo $V::e($settings['sistema.timezone'] ?? 'America/Sao_Paulo'); ?>" placeholder="America/Sao_Paulo"/>
  </div>

<?php elseif ($secao === 'comissoes'): ?>
  <div style="background:rgba(184,148,90,.04);border:1px solid rgba(184,148,90,.15);padding:20px;margin-bottom:24px">
    <p style="font-size:.82rem;color:var(--text-muted);line-height:1.6">
      Os percentuais abaixo são aplicados automaticamente ao valor do contrato no momento da formalização.<br>
      <strong>% Empresa:</strong> receita da Lexus sobre todo contrato formalizado (cobrado do cliente).<br>
      <strong>% Parceiro de Origem:</strong> repasse ao parceiro que submeteu a demanda (pago pela Lexus).
    </p>
  </div>
  <div class="form-row">
    <div class="form-group">
      <label>% Comissão da Empresa</label>
      <input type="number" step="0.01" min="0" max="100"
             name="comissao_empresa_pct"
             value="<?php echo $V::e((string)($settings['comissao.empresa_pct'] ?? '10')); ?>"
             placeholder="10.00"/>
      <small style="font-size:.75rem;color:var(--text-muted)">Gerado como pendência de recebimento (cliente → Lexus)</small>
    </div>
    <div class="form-group">
      <label>% Comissão do Parceiro de Origem</label>
      <input type="number" step="0.01" min="0" max="100"
             name="comissao_parceiro_origem_pct"
             value="<?php echo $V::e((string)($settings['comissao.parceiro_origem_pct'] ?? '5')); ?>"
             placeholder="5.00"/>
      <small style="font-size:.75rem;color:var(--text-muted)">Gerado como pendência de pagamento (Lexus → parceiro de origem), apenas quando a demanda foi submetida por um parceiro</small>
    </div>
  </div>

<?php else: ?>
  <p style="color:var(--text-muted);font-size:.88rem">Seção "<?php echo $V::e($secao); ?>" em desenvolvimento.</p>
<?php endif; ?>
