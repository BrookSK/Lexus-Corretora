<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf, Auth};

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

<form method="POST" action="/cliente/minha-conta" enctype="multipart/form-data">
  <?php echo Csrf::campo(); ?>

  <!-- Foto de Perfil -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px">Foto de Perfil</h2>
    <div style="display:flex;align-items:center;gap:24px">
      <div id="avatar-preview-wrap" style="width:80px;height:80px;border-radius:50%;overflow:hidden;background:var(--gold);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:2px solid var(--border)">
        <?php $av = Auth::clienteAvatar(); ?>
        <?php if ($av): ?>
        <img id="avatar-preview" src="/<?php echo View::e(ltrim($av,'/')); ?>" style="width:100%;height:100%;object-fit:cover"/>
        <?php else: ?>
        <span id="avatar-initials" style="font-size:1.6rem;font-weight:700;color:var(--black)"><?php echo View::e(mb_substr(Auth::clienteNome() ?? 'U', 0, 1)); ?></span>
        <img id="avatar-preview" src="" style="width:100%;height:100%;object-fit:cover;display:none"/>
        <?php endif; ?>
      </div>
      <div>
        <label class="btn btn-secondary btn-sm" style="cursor:pointer">
          Escolher foto
          <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none" onchange="previewAvatar(this)"/>
        </label>
        <p style="font-size:.75rem;color:var(--text-muted);margin-top:8px">JPG, PNG, WebP — máx. 5MB. Aparece ao lado do seu nome no topo.</p>
      </div>
    </div>
  </div>

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
<script>
function previewAvatar(input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    var img = document.getElementById('avatar-preview');
    var ini = document.getElementById('avatar-initials');
    img.src = e.target.result;
    img.style.display = 'block';
    if (ini) ini.style.display = 'none';
  };
  reader.readAsDataURL(input.files[0]);
}
</script>
