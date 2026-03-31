<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<section class="inst-hero">
  <span class="lbl reveal"><?php echo View::e(I18n::t('pagina.contato')); ?></span>
  <h1 class="disp reveal d1"><?php echo View::e(I18n::t('contato.titulo')); ?></h1>
</section>

<section class="form-section">
  <div class="form-container">
    <?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash-msg flash-<?php echo View::e($_SESSION['flash']['type']); ?>" style="margin-bottom:24px">
      <?php echo View::e($_SESSION['flash']['message']); ?>
    </div>
    <?php unset($_SESSION['flash']); endif; ?>

    <form method="POST" action="/contato">
      <?php echo Csrf::campo(); ?>
      <div class="form-row">
        <div class="form-group">
          <label><?php echo View::e(I18n::t('contato.nome')); ?></label>
          <input type="text" name="name" required/>
        </div>
        <div class="form-group">
          <label><?php echo View::e(I18n::t('contato.email')); ?></label>
          <input type="email" name="email" required/>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label><?php echo View::e(I18n::t('contato.telefone')); ?></label>
          <input type="tel" name="phone"/>
        </div>
        <div class="form-group">
          <label><?php echo View::e(I18n::t('contato.assunto')); ?></label>
          <input type="text" name="subject" required/>
        </div>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('contato.mensagem')); ?></label>
        <textarea name="message" required></textarea>
      </div>
      <div class="form-submit">
        <button type="submit" class="btn-cta"><?php echo View::e(I18n::t('contato.enviar')); ?></button>
      </div>
    </form>
  </div>
</section>
