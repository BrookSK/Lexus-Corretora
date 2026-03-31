<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Configurações da conta do parceiro
 * Variáveis: $parceiro (array)
 */
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_par.minha_conta')); ?></h1>
    <p class="section-subtitle">Gerencie suas credenciais e preferências</p>
  </div>
</div>

<form method="POST" action="/parceiro/minha-conta/salvar">
  <?php echo Csrf::campo(); ?>

  <!-- Dados de Acesso -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px">Dados de Acesso</h2>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.nome')); ?> *</label>
        <input type="text" name="name" value="<?php echo View::e($parceiro['name'] ?? ''); ?>" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.email')); ?> *</label>
        <input type="email" name="email" value="<?php echo View::e($parceiro['email'] ?? ''); ?>" required/>
      </div>
    </div>
  </div>

  <!-- Alterar Senha -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('auth.redefinir_senha')); ?></h2>
    <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:16px">Deixe em branco para manter a senha atual.</p>

    <div class="form-group">
      <label>Senha Atual</label>
      <input type="password" name="current_password"/>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Nova Senha</label>
        <input type="password" name="new_password" minlength="8"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.confirmar_senha')); ?></label>
        <input type="password" name="new_password_confirmation" minlength="8"/>
      </div>
    </div>
  </div>

  <!-- Preferências -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px">Preferências</h2>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('config.idioma')); ?></label>
        <select name="language">
          <option value="pt-BR" <?php echo ($parceiro['language'] ?? 'pt-BR') === 'pt-BR' ? 'selected' : ''; ?>>Português (BR)</option>
          <option value="en-US" <?php echo ($parceiro['language'] ?? '') === 'en-US' ? 'selected' : ''; ?>>English (US)</option>
          <option value="es-ES" <?php echo ($parceiro['language'] ?? '') === 'es-ES' ? 'selected' : ''; ?>>Español (ES)</option>
        </select>
      </div>
      <div class="form-group">
        <label>Moeda</label>
        <select name="currency">
          <option value="BRL" <?php echo ($parceiro['currency'] ?? 'BRL') === 'BRL' ? 'selected' : ''; ?>>BRL — Real</option>
          <option value="USD" <?php echo ($parceiro['currency'] ?? '') === 'USD' ? 'selected' : ''; ?>>USD — Dólar</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Segurança -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('auth.2fa_titulo')); ?></h2>

    <div class="form-group">
      <label style="display:flex;align-items:center;gap:10px;text-transform:none;letter-spacing:0;font-size:.88rem;cursor:pointer">
        <input type="checkbox" name="two_factor_enabled" value="1" <?php echo ($parceiro['two_factor_enabled'] ?? 0) ? 'checked' : ''; ?>/>
        Ativar autenticação em dois fatores (2FA)
      </label>
      <small style="color:var(--text-muted);font-size:.75rem;margin-top:4px;display:block">Adiciona uma camada extra de segurança ao seu login.</small>
    </div>
  </div>

  <div style="display:flex;justify-content:flex-end">
    <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
  </div>
</form>
