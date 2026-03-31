<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<!DOCTYPE html>
<html lang="<?php echo View::e(I18n::idioma()); ?>">
<head><meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/><title><?php echo View::e(I18n::t('auth.esqueci_senha')); ?> — Lexus</title><link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;600&family=Outfit:wght@300;400;500&display=swap" rel="stylesheet"/><link rel="stylesheet" href="/assets/css/painel.css"/></head>
<body><div class="auth-page"><div class="auth-box">
  <a href="/" class="auth-logo">Lexus</a>
  <h2 class="auth-title"><?php echo View::e(I18n::t('auth.redefinir_senha')); ?></h2>
  <?php require __DIR__ . '/../../_partials/painel/flash-messages.php'; ?>
  <form method="POST" action="/parceiro/esqueci-senha">
    <?php echo Csrf::campo(); ?>
    <div class="form-group"><label><?php echo View::e(I18n::t('auth.email')); ?></label><input type="email" name="email" required/></div>
    <button type="submit" class="btn btn-primary" style="width:100%"><?php echo View::e(I18n::t('geral.enviar')); ?></button>
  </form>
  <div class="auth-footer"><a href="/parceiro/entrar"><?php echo View::e(I18n::t('auth.entrar')); ?></a></div>
</div></div></body></html>
