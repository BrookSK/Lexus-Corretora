<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<!DOCTYPE html>
<html lang="<?php echo View::e(I18n::idioma()); ?>">
<head><meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/><title><?php echo View::e(I18n::t('auth.redefinir_senha')); ?> — <?php echo \LEX\Core\View::e(\LEX\Core\SistemaConfig::nome()); ?></title><link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;600&family=Outfit:wght@300;400;500&display=swap" rel="stylesheet"/><link rel="stylesheet" href="/assets/css/painel.css"/></head>
<body><div class="auth-page"><div class="auth-box">
  <a href="/" class="auth-logo"><img src="<?php echo \LEX\Core\View::e(\LEX\Core\SistemaConfig::logo()); ?>" alt="<?php echo \LEX\Core\View::e(\LEX\Core\SistemaConfig::nome()); ?>" style="height:36px"/></a>
  <h2 class="auth-title"><?php echo View::e(I18n::t('auth.redefinir_senha')); ?></h2>
  <form method="POST" action="/cliente/redefinir-senha">
    <?php echo Csrf::campo(); ?>
    <input type="hidden" name="token" value="<?php echo View::e($token ?? ''); ?>"/>
    <div class="form-group"><label><?php echo View::e(I18n::t('auth.senha')); ?></label><input type="password" name="password" required minlength="8"/></div>
    <div class="form-group"><label><?php echo View::e(I18n::t('auth.confirmar_senha')); ?></label><input type="password" name="password_confirmation" required minlength="8"/></div>
    <button type="submit" class="btn btn-primary" style="width:100%"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
  </form>
</div></div></body></html>
