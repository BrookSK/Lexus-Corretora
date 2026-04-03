<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, SistemaConfig, Csrf};
$pageTitle = $pageTitle ?? SistemaConfig::metaTitle();
$metaDesc = $metaDesc ?? SistemaConfig::metaDescription();
?>
<!DOCTYPE html>
<html lang="<?php echo View::e(I18n::idioma()); ?>">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?php echo View::e($pageTitle); ?></title>
<meta name="description" content="<?php echo View::e($metaDesc); ?>"/>
<meta name="csrf-token" content="<?php echo View::e(Csrf::gerar()); ?>"/>
<link rel="icon" href="<?php echo View::e(SistemaConfig::favicon()); ?>" type="image/x-icon"/>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/assets/css/landing-pages.css"/>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Outfit',sans-serif;background:#0E0C09;color:#F5F0E8;overflow-x:hidden}
nav{position:fixed;top:0;left:0;right:0;z-index:200;background:#0C0C0A;border-bottom:1px solid rgba(184,148,90,.2);padding:22px 64px}
.nav-content{display:flex;align-items:center;justify-content:space-between}
.logo img{height:32px}
.nav-links{display:flex;gap:40px}
.nav-links a{font-size:.72rem;letter-spacing:.16em;text-transform:uppercase;color:rgba(245,242,237,.5);text-decoration:none;transition:color .25s}
.nav-links a:hover{color:#F5F0E8}
.nav-btn{font-size:.7rem;letter-spacing:.15em;text-transform:uppercase;color:#0C0C0A;background:#C9A84C;padding:10px 26px;text-decoration:none;transition:background .25s}
.nav-btn:hover{background:#e4c97a}
</style>
</head>
<body>
<nav>
  <div class="nav-content">
    <a href="/" class="logo"><img src="<?php echo View::e(SistemaConfig::logo()); ?>" alt="<?php echo View::e(SistemaConfig::nome()); ?>"/></a>
    <div class="nav-links">
      <a href="/sobre">Sobre</a>
      <a href="/como-funciona">Como Funciona</a>
      <a href="/para-clientes">Para Clientes</a>
      <a href="/para-parceiros">Para Parceiros</a>
      <a href="/contato">Contato</a>
    </div>
    <a href="/abrir-demanda" class="nav-btn">Abrir Demanda</a>
  </div>
</nav>

<?php echo $conteudo ?? ''; ?>

</body>
</html>
