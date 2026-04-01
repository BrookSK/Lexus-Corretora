<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('clientes.editar_cliente')); ?></h1>
    <p class="section-subtitle"><?php echo View::e($cliente['name']); ?></p>
  </div>
  <a href="/equipe/clientes/<?php echo (int)$cliente['id']; ?>" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/clientes/<?php echo (int)$cliente['id']; ?>/editar">
    <?php echo Csrf::campo(); ?>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.nome')); ?> *</label>
        <input type="text" name="name" value="<?php echo View::e($cliente['name']); ?>" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.email')); ?> *</label>
        <input type="email" name="email" value="<?php echo View::e($cliente['email']); ?>" required/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.telefone')); ?></label>
        <input type="text" name="phone" value="<?php echo View::e($cliente['phone'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.whatsapp')); ?></label>
        <input type="text" name="whatsapp" value="<?php echo View::e($cliente['whatsapp'] ?? ''); ?>"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.empresa')); ?></label>
        <input type="text" name="company" value="<?php echo View::e($cliente['company'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.documento')); ?></label>
        <input type="text" name="document" value="<?php echo View::e($cliente['document'] ?? ''); ?>"/>
      </div>
    </div>

    <div class="form-row">
    <div class="form-row">
      <?php
      $estadoSelecionado = $cliente['state'] ?? '';
      $cidadeSelecionada = $cliente['city'] ?? '';
      $obrigatorio = false;
      include __DIR__ . '/../_partials/campos-estado-cidade.php';
      ?>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.pais')); ?></label>
        <input type="text" name="country" value="<?php echo View::e($cliente['country'] ?? 'Brasil'); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.status')); ?></label>
        <select name="is_active">
          <option value="1" <?php echo ($cliente['is_active'] ?? 1) ? 'selected' : ''; ?>><?php echo View::e(I18n::t('geral.ativo')); ?></option>
          <option value="0" <?php echo !($cliente['is_active'] ?? 1) ? 'selected' : ''; ?>><?php echo View::e(I18n::t('geral.inativo')); ?></option>
        </select>
      </div>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
