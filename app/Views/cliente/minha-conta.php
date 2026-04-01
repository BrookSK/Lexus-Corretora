<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Configurações da conta do cliente
 * Variáveis: $cliente (array)
 */
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_cli.minha_conta')); ?></h1>
    <p class="section-subtitle">Gerencie suas informações pessoais e preferências</p>
  </div>
</div>

<form method="POST" action="/cliente/minha-conta">
  <?php echo Csrf::campo(); ?>

  <!-- Dados Pessoais -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('demanda.dados_pessoais')); ?></h2>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('auth.nome')); ?> *</label>
      <input type="text" name="name" value="<?php echo View::e($cliente['name'] ?? ''); ?>" required/>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.email')); ?> *</label>
        <input type="email" name="email" value="<?php echo View::e($cliente['email'] ?? ''); ?>" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('contato.telefone')); ?></label>
        <input type="tel" name="phone" value="<?php echo View::e($cliente['phone'] ?? ''); ?>"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>WhatsApp</label>
        <input type="tel" name="whatsapp" value="<?php echo View::e($cliente['whatsapp'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label>Empresa</label>
        <input type="text" name="company" value="<?php echo View::e($cliente['company'] ?? ''); ?>"/>
      </div>
    </div>

    <div class="form-row">
      <?php
      $estadoSelecionado = $cliente['state'] ?? '';
      $cidadeSelecionada = $cliente['city'] ?? '';
      $obrigatorio = false;
      include __DIR__ . '/../_partials/campos-estado-cidade.php';
      ?>
    </div>
  </div>

  <!-- Preferências -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px">Preferências</h2>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('config.idioma')); ?></label>
        <select name="language">
          <option value="pt-BR" <?php echo ($cliente['language'] ?? 'pt-BR') === 'pt-BR' ? 'selected' : ''; ?>>Português (BR)</option>
          <option value="en-US" <?php echo ($cliente['language'] ?? '') === 'en-US' ? 'selected' : ''; ?>>English (US)</option>
          <option value="es-ES" <?php echo ($cliente['language'] ?? '') === 'es-ES' ? 'selected' : ''; ?>>Español (ES)</option>
        </select>
      </div>
      <div class="form-group">
        <label>Moeda</label>
        <select name="currency">
          <option value="BRL" <?php echo ($cliente['currency'] ?? 'BRL') === 'BRL' ? 'selected' : ''; ?>>BRL — Real</option>
          <option value="USD" <?php echo ($cliente['currency'] ?? '') === 'USD' ? 'selected' : ''; ?>>USD — Dólar</option>
        </select>
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

  <div style="display:flex;justify-content:flex-end">
    <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
  </div>
</form>
