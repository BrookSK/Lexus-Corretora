<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<!DOCTYPE html>
<html lang="<?php echo View::e(I18n::idioma()); ?>">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?php echo View::e(I18n::t('auth.entrar')); ?> — <?php echo View::e(\LEX\Core\SistemaConfig::nome()); ?></title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;600&family=Outfit:wght@300;400;500&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/assets/css/painel.css"/>
</head>
<body>
<div class="auth-page">
  <div class="auth-box">
    <a href="/" class="auth-logo">
      <img src="<?php echo View::e(\LEX\Core\SistemaConfig::logo()); ?>" alt="<?php echo View::e(\LEX\Core\SistemaConfig::nome()); ?>" style="height:36px"/>
    </a>
    <h2 class="auth-title"><?php echo View::e(I18n::t('auth.entrar')); ?></h2>
    <p style="font-size:.82rem;color:var(--text-muted);text-align:center;margin-bottom:24px">
      Acesse com seu e-mail e senha. Você será redirecionado automaticamente para seu painel.
    </p>
    <?php require __DIR__ . '/../_partials/painel/flash-messages.php'; ?>
    <form method="POST" action="/login">
      <?php echo Csrf::campo(); ?>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.email')); ?></label>
        <input type="email" name="email" required autocomplete="email" autofocus/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('auth.senha')); ?></label>
        <input type="password" name="password" required autocomplete="current-password"/>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%"><?php echo View::e(I18n::t('auth.entrar')); ?></button>
    </form>
  </div>
</div>
</body></html>
