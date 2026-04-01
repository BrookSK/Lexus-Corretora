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

<form method="POST" action="/equipe/minha-conta">
  <?php echo Csrf::campo(); ?>

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
