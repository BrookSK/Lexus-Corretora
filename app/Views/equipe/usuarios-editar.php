<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('usuarios.editar_usuario')); ?></h1>
    <p class="section-subtitle"><?php echo View::e($usuario['name'] ?? ''); ?></p>
  </div>
  <a href="/equipe/usuarios" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/usuarios/<?php echo (int)$usuario['id']; ?>/editar">
    <?php echo Csrf::campo(); ?>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.nome')); ?> *</label>
        <input type="text" name="name" value="<?php echo View::e($usuario['name']); ?>" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.email')); ?> *</label>
        <input type="email" name="email" value="<?php echo View::e($usuario['email']); ?>" required/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.nova_senha')); ?></label>
        <input type="password" name="password" placeholder="<?php echo View::e(I18n::t('usuarios.deixe_vazio')); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('usuarios.papel')); ?></label>
        <select name="role_id">
          <option value="">— <?php echo View::e(I18n::t('geral.selecione')); ?> —</option>
          <?php if (!empty($roles)): foreach ($roles as $role): ?>
          <option value="<?php echo (int)$role['id']; ?>" <?php echo ((int)($usuario['role_id'] ?? 0)) === (int)$role['id'] ? 'selected' : ''; ?>><?php echo View::e($role['name']); ?></option>
          <?php endforeach; endif; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('geral.status')); ?></label>
      <select name="is_active">
        <option value="1" <?php echo ($usuario['is_active'] ?? 1) ? 'selected' : ''; ?>><?php echo View::e(I18n::t('geral.ativo')); ?></option>
        <option value="0" <?php echo !($usuario['is_active'] ?? 1) ? 'selected' : ''; ?>><?php echo View::e(I18n::t('geral.inativo')); ?></option>
      </select>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
