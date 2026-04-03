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
      <button type="submit" class="btn btn-primary" id="loginBtn" style="width:100%"><?php echo View::e(I18n::t('auth.entrar')); ?></button>
    </form>
  </div>
</div>
<script>
// Debug do botão de login
console.log('=== LOGIN BUTTON DEBUG ===');
const btn = document.getElementById('loginBtn');
if (btn) {
  console.log('Button element:', btn);
  console.log('Button classes:', btn.className);
  
  const computed = window.getComputedStyle(btn);
  console.log('Computed styles:', {
    background: computed.backgroundColor,
    backgroundImage: computed.backgroundImage,
    color: computed.color,
    display: computed.display,
    padding: computed.padding,
    border: computed.border,
    width: computed.width,
    height: computed.height
  });
  
  // Verificar variáveis CSS
  const root = document.documentElement;
  const rootStyles = window.getComputedStyle(root);
  console.log('CSS Variables:', {
    '--gold': rootStyles.getPropertyValue('--gold'),
    '--black': rootStyles.getPropertyValue('--black'),
    '--white': rootStyles.getPropertyValue('--white'),
    '--gold-lt': rootStyles.getPropertyValue('--gold-lt')
  });
  
  // Verificar todas as regras CSS aplicadas ao botão
  const sheets = document.styleSheets;
  const btnRules = [];
  for (let sheet of sheets) {
    try {
      const rules = sheet.cssRules || sheet.rules;
      for (let rule of rules) {
        if (rule.selectorText && (rule.selectorText.includes('.btn-primary') || rule.selectorText.includes('.btn'))) {
          btnRules.push({
            selector: rule.selectorText,
            background: rule.style.background || rule.style.backgroundColor,
            color: rule.style.color,
            sheet: sheet.href || 'inline'
          });
        }
      }
    } catch(e) {
      console.log('Cannot read stylesheet:', sheet.href);
    }
  }
  console.log('All CSS rules for button:', btnRules);
}
console.log('=== END DEBUG ===');
</script>
</body></html>
