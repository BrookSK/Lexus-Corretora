<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('usuarios.novo_usuario')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('usuarios.subtitulo_criar')); ?></p>
  </div>
  <a href="/equipe/usuarios" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/usuarios">
    <?php echo Csrf::campo(); ?>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.nome')); ?> *</label>
        <input type="text" name="name" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.email')); ?> *</label>
        <input type="email" name="email" required/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.senha')); ?> *</label>
        <input type="password" name="password" required minlength="8"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('usuarios.papel')); ?></label>
        <select name="role_id">
          <option value="">— <?php echo View::e(I18n::t('geral.selecione')); ?> —</option>
          <?php if (!empty($roles)): foreach ($roles as $role): ?>
          <option value="<?php echo (int)$role['id']; ?>"><?php echo View::e($role['name']); ?></option>
          <?php endforeach; endif; ?>
        </select>
      </div>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
