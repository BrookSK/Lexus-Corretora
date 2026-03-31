<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('clientes.novo_cliente')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('clientes.subtitulo_criar')); ?></p>
  </div>
  <a href="/equipe/clientes" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/clientes">
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
        <label><?php echo View::e(I18n::t('geral.telefone')); ?></label>
        <input type="text" name="phone"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.whatsapp')); ?></label>
        <input type="text" name="whatsapp"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.empresa')); ?></label>
        <input type="text" name="company"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.documento')); ?></label>
        <input type="text" name="document"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.cidade')); ?></label>
        <input type="text" name="city"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.estado')); ?></label>
        <input type="text" name="state"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.pais')); ?></label>
        <input type="text" name="country" value="Brasil"/>
      </div>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
