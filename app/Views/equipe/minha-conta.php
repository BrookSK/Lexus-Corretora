<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf, Auth};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('conta.titulo')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('conta.dados_pessoais')); ?></p>
  </div>
</div>

<form method="POST" action="/equipe/minha-conta" enctype="multipart/form-data">
  <?php echo Csrf::campo(); ?>

  <!-- Foto de Perfil -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px">Foto de Perfil</h2>
    <div style="display:flex;align-items:center;gap:24px">
      <div id="avatar-preview-wrap" style="width:80px;height:80px;border-radius:50%;overflow:hidden;background:var(--gold);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:2px solid var(--border)">
        <?php $av = Auth::equipeAvatar(); ?>
        <?php if ($av): ?>
        <img id="avatar-preview" src="/<?php echo View::e(ltrim($av,'/')); ?>" style="width:100%;height:100%;object-fit:cover"/>
        <?php else: ?>
        <span id="avatar-initials" style="font-size:1.6rem;font-weight:700;color:var(--black)"><?php echo View::e(mb_substr(Auth::equipeNome() ?? 'U', 0, 1)); ?></span>
        <img id="avatar-preview" src="" style="width:100%;height:100%;object-fit:cover;display:none"/>
        <?php endif; ?>
      </div>
      <div>
        <label class="btn btn-secondary btn-sm" style="cursor:pointer">
          Escolher foto
          <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none" onchange="previewAvatar(this)"/>
        </label>
        <p style="font-size:.75rem;color:var(--text-muted);margin-top:8px">JPG, PNG, WebP — máx. 5MB. A foto aparece ao lado do seu nome no topo.</p>
      </div>
    </div>
  </div>

  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('conta.dados_pessoais')); ?></h2>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.nome')); ?> *</label>
        <input type="text" name="name" value="<?php echo View::e($usuario['name'] ?? ''); ?>" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.email')); ?> *</label>
        <input type="email" name="email" value="<?php echo View::e($usuario['email'] ?? ''); ?>" required/>
      </div>
    </div>
  </div>

  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:8px"><?php echo View::e(I18n::t('conta.alterar_senha')); ?></h2>
    <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:20px"><?php echo View::e(I18n::t('conta.manter_vazia')); ?></p>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('conta.nova_senha')); ?></label>
        <input type="password" name="new_password" minlength="8" autocomplete="new-password"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('conta.confirmar_nova')); ?></label>
        <input type="password" name="new_password_confirmation" minlength="8" autocomplete="new-password"/>
      </div>
    </div>
  </div>

  <div class="card" style="padding:32px">
    <h2 class="card-title" style="margin-bottom:16px">Informações da Conta</h2>
    <table style="font-size:.88rem;width:100%">
      <tr>
        <td style="color:var(--text-muted);padding:8px 0;width:200px">Role</td>
        <td style="padding:8px 0"><span class="badge badge-gold"><?php echo View::e(Auth::equipeRole() ?? 'admin'); ?></span></td>
      </tr>
      <tr>
        <td style="color:var(--text-muted);padding:8px 0">Último login</td>
        <td style="padding:8px 0"><?php echo $usuario['last_login_at'] ? View::e(date('d/m/Y H:i', strtotime($usuario['last_login_at']))) : '—'; ?></td>
      </tr>
      <tr>
        <td style="color:var(--text-muted);padding:8px 0">Conta criada em</td>
        <td style="padding:8px 0"><?php echo View::e(date('d/m/Y', strtotime($usuario['created_at']))); ?></td>
      </tr>
    </table>
  </div>

  <div style="display:flex;justify-content:flex-end;margin-top:24px">
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
(function(){
  document.querySelector('form[action="/equipe/minha-conta"]').addEventListener('submit', function(e){
    var p = document.querySelector('[name="new_password"]');
    var c = document.querySelector('[name="new_password_confirmation"]');
    if (p.value !== '' && p.value !== c.value) {
      e.preventDefault();
      alert('As senhas não coincidem.');
      c.focus();
    }
  });
})();
</script>
