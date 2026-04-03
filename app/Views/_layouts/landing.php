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
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
--gold: #C9A84C;
--gold-light: #e4c97a;
--cream: #F5F0E8;
--dark: #0E0C09;
--dark2: #161410;
--dark3: #1E1B16;
--text-muted: #8a8070;
--text-light: #cfc9be;
--ml: 90px;
}
html { scroll-behavior: smooth; }
body {
background: var(--dark);
color: var(--cream);
font-family: 'DM Sans', sans-serif;
font-weight: 300;
line-height: 1.6;
overflow-x: hidden;
}
/* NAV */
nav {
position: fixed; top: 0; left: 0; right: 0; z-index: 200;
display: flex; align-items: center; justify-content: space-between;
padding: 0 40px; height: 64px;
background: rgba(14,12,9,.9); backdrop-filter: blur(14px);
border-bottom: 1px solid rgba(201,168,76,.12);
}
.logo {
display: flex; align-items: center; gap: 10px;
font-family: 'Cormorant Garamond', serif;
font-size: 1.35rem; font-weight: 400; letter-spacing: .08em;
color: var(--cream); text-decoration: none;
}
.logo img { height: 32px; }
.nav-links { display: flex; gap: 34px; list-style: none; }
.nav-links a {
font-size: .72rem; letter-spacing: .12em; text-transform: uppercase;
color: var(--text-muted); text-decoration: none; transition: color .2s;
}
.nav-links a:hover { color: var(--cream); }
.nav-actions { display: flex; gap: 12px; align-items: center; }
.btn-ghost {
font-size: .72rem; letter-spacing: .1em; text-transform: uppercase;
color: var(--text-muted); background: none; border: none; cursor: pointer;
padding: 8px 0; font-family: 'DM Sans', sans-serif; transition: color .2s;
text-decoration: none;
}
.btn-ghost:hover { color: var(--cream); }
.btn-primary {
font-size: .72rem; letter-spacing: .1em; text-transform: uppercase;
color: var(--dark); background: var(--gold); border: none; cursor: pointer;
padding: 10px 22px; font-family: 'DM Sans', sans-serif; font-weight: 500;
transition: background .2s, transform .15s;
text-decoration: none; display: inline-block;
}
.btn-primary:hover { background: var(--gold-light); transform: translateY(-1px); }
/* HERO */
.hero {
min-height: 100vh !important;
display: grid !important;
grid-template-columns: 1fr 1fr !important;
grid-template-rows: none !important;
grid-auto-flow: dense !important;
padding: 64px 0 0 0 !important;
flex-direction: row !important;
}
/* RIGHT: headline */
.hero-headline-col {
order: 2 !important;
display: flex !important;
align-items: center !important;
padding: 80px 72px !important;
position: relative !important;
overflow: hidden !important;
grid-column: 2 !important;
width: auto !important;
max-width: none !important;
}
.hero-headline-col::before {
content: ''; position: absolute; inset: 0; pointer-events: none;
background:
radial-gradient(ellipse 60% 50% at 75% 30%, rgba(201,168,76,.08) 0%, transparent 70%),
radial-gradient(ellipse 40% 60% at 20% 80%, rgba(201,168,76,.04) 0%, transparent 60%);
}
.hero-text { position: relative; z-index: 1; }
.hero-headline-inner { position: relative; z-index: 1; }
.hero-headline-title {
font-family: 'Cormorant Garamond', serif;
font-size: clamp(2.8rem, 4vw, 4.5rem);
font-weight: 300; line-height: 1.1; color: var(--cream); margin-bottom: 24px;
}
.hero-headline-desc {
font-size: .95rem; color: var(--text-light);
max-width: 480px; line-height: 1.75; margin-bottom: 36px;
}
.hero-headline-list {
list-style: none; padding: 0; margin: 0;
}
.hero-headline-list li {
display: flex; align-items: center; gap: 12px;
font-size: .88rem; color: var(--text-light);
margin-bottom: 14px;
}
.hero-headline-list li svg {
width: 18px; height: 18px; stroke: var(--gold); flex-shrink: 0; fill: none; stroke-width: 2;
}
/* LEFT: form panel */
.hero-form-col {
order: 1 !important;
background: var(--dark2) !important;
border-right: 1px solid rgba(201,168,76,.1) !important;
display: flex !important;
flex-direction: column !important;
position: relative !important;
overflow: hidden !important;
min-height: 100vh !important;
width: auto !important;
max-width: none !important;
grid-column: 1 !important;
}
/* Progress bar */
.progress-bar-wrap { height: 3px; background: rgba(255,255,255,.05); position: absolute; top: 0; left: 0; right: 0; z-index: 10; }
.progress-bar-fill { height: 100%; background: var(--gold); transition: width .55s cubic-bezier(.4,0,.2,1); }
/* Step header */
.step-header { padding: 36px var(--ml) 0; flex-shrink: 0; }
.step-meta { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.step-eyebrow {
display: flex; align-items: center; gap: 8px;
font-size: .65rem; letter-spacing: .18em; text-transform: uppercase; color: var(--gold);
}
.step-eyebrow::before { content: ''; display: block; width: 20px; height: 1px; background: var(--gold); }
.step-counter { font-size: .68rem; color: var(--text-muted); letter-spacing: .06em; }
.step-counter span { color: var(--gold); }
/* Dots */
.step-dots { display: flex; gap: 5px; margin-bottom: 24px; }
.step-dot { height: 3px; flex: 1; border-radius: 2px; background: rgba(201,168,76,.2); transition: all .4s; }
.step-dot.active { background: var(--gold); }
/* Slider */
.slider-viewport { flex: 1; overflow: hidden; position: relative; }
.slides-track { display: flex; height: 100%; transition: transform .52s cubic-bezier(.4,0,.2,1); }
.slide {
min-width: 100%; height: 100%;
padding: 4px var(--ml) 20px;
overflow-y: auto;
}
.slide::-webkit-scrollbar { width: 3px; }
.slide::-webkit-scrollbar-track { background: transparent; }
.slide::-webkit-scrollbar-thumb { background: rgba(201,168,76,.2); border-radius: 2px; }
.slide-title {
font-family: 'Cormorant Garamond', serif;
font-size: 1.6rem; font-weight: 300; color: var(--cream);
margin-bottom: 24px; line-height: 1.2;
}
/* Form elements */
.form-group { margin-bottom: 16px; }
.form-group label {
display: block; font-size: .63rem; letter-spacing: .13em; text-transform: uppercase;
color: var(--text-muted); margin-bottom: 6px;
}
.req { color: var(--gold); margin-left: 2px; }
.form-group input,
.form-group select,
.form-group textarea {
width: 100%; background: rgba(255,255,255,.04);
border: 1px solid rgba(201,168,76,.18);
color: var(--cream); font-family: 'DM Sans', sans-serif;
font-size: .875rem; font-weight: 300;
padding: 10px 14px; outline: none;
transition: border-color .2s;
appearance: none; border-radius: 1px;
}
.form-group input::placeholder,
.form-group textarea::placeholder { color: var(--text-muted); font-size: .8rem; }
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus { border-color: var(--gold); background: rgba(201,168,76,.04); }
.form-group select { cursor: pointer; }
.form-group select option { background: #1a1710; color: var(--cream); }
.form-group textarea { resize: vertical; min-height: 110px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
/* Checkboxes */
.check-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 4px; }
.check-item {
display: flex; align-items: center; gap: 9px;
padding: 10px 12px; border: 1px solid rgba(201,168,76,.15);
cursor: pointer; transition: all .2s; user-select: none;
}
.check-item:hover { border-color: rgba(201,168,76,.4); }
.check-item.checked { border-color: var(--gold); background: rgba(201,168,76,.07); }
.check-box {
width: 15px; height: 15px; flex-shrink: 0;
border: 1px solid rgba(201,168,76,.35);
display: flex; align-items: center; justify-content: center;
transition: all .2s;
}
.check-item.checked .check-box { border-color: var(--gold); background: var(--gold); }
.check-box svg { width: 9px; height: 9px; stroke: var(--dark); fill: none; stroke-width: 3; opacity: 0; transition: opacity .2s; }
.check-item.checked .check-box svg { opacity: 1; }
.check-label { font-size: .76rem; color: var(--text-light); }
/* File upload */
.dropzone {
border: 1px dashed rgba(201,168,76,.3); padding: 38px 20px;
text-align: center; cursor: pointer; transition: all .2s;
display: flex; flex-direction: column; align-items: center; gap: 12px;
}
.dropzone:hover { border-color: var(--gold); background: rgba(201,168,76,.04); }
.dropzone-icon { width: 32px; height: 32px; stroke: var(--text-muted); }
.dropzone-text { font-size: .74rem; color: var(--text-muted); margin: 0; }
.upload-hint { font-size: .74rem; color: var(--text-muted); margin-bottom: 8px; }
.file-list { margin-top: 12px; }
.file-item {
display: flex; align-items: center; justify-content: space-between;
padding: 8px 14px; margin-top: 6px;
background: rgba(201,168,76,.06); border: 1px solid rgba(201,168,76,.15);
font-size: .74rem; color: var(--text-light);
}
.file-name { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.file-remove { background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.2rem; padding: 0 4px; transition: color .2s; }
.file-remove:hover { color: #e07060; }
/* Nav buttons */
.slide-nav {
padding: 18px var(--ml) 28px;
display: flex; align-items: center; justify-content: space-between;
flex-shrink: 0; border-top: 1px solid rgba(201,168,76,.08);
}
.slide-nav-btn {
font-family: 'DM Sans', sans-serif; font-size: .7rem; letter-spacing: .12em;
text-transform: uppercase; color: var(--text-muted); background: none;
border: 1px solid rgba(201,168,76,.2); cursor: pointer; padding: 11px 26px;
transition: all .2s; display: flex; align-items: center; gap: 8px;
}
.slide-nav-btn:hover { border-color: var(--gold); color: var(--cream); }
.slide-nav-btn:disabled { opacity: .3; pointer-events: none; }
.slide-nav-btn svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2.2; }
.slide-nav-btn-next {
color: var(--dark); background: var(--gold); border: none; font-weight: 500;
}
.slide-nav-btn-next:hover { background: var(--gold-light); }
.submit-btn {
font-family: 'DM Sans', sans-serif; font-size: .75rem; letter-spacing: .12em;
text-transform: uppercase; color: var(--dark); background: var(--gold); border: none;
cursor: pointer; padding: 14px 42px; font-weight: 500; width: 100%; margin-top: 24px;
transition: all .2s;
}
.submit-btn:hover { background: var(--gold-light); }
/* Sections */
.section-container { max-width: 1400px; margin: 0 auto; padding: 92px var(--ml); }
.how-it-works { background: var(--cream); padding: 92px 0; }
.how-it-works .section-title {
font-family: 'Cormorant Garamond', serif;
font-size: clamp(2.4rem, 3.5vw, 3.8rem); font-weight: 300;
line-height: 1.1; color: var(--dark); margin-bottom: 12px; text-align: center;
}
.how-it-works .section-subtitle {
font-size: .95rem; color: #5a5145; text-align: center; margin-bottom: 52px;
}
.how-cards { display: grid; grid-template-columns: repeat(3,1fr); gap: 32px; }
.how-card { background: #fff; padding: 44px 36px; border: 1px solid rgba(14,12,9,.08); transition: all .25s; }
.how-card:hover { background: #ede8df; transform: translateY(-4px); }
.how-card-icon {
width: 48px; height: 48px; border: 1px solid var(--gold);
display: flex; align-items: center; justify-content: center; margin-bottom: 22px;
}
.how-card-icon svg { width: 22px; height: 22px; stroke: var(--gold); fill: none; stroke-width: 1.5; }
.how-card-title {
font-family: 'Cormorant Garamond', serif;
font-size: 1.4rem; font-weight: 400; color: var(--dark); margin-bottom: 10px;
}
.how-card-desc { font-size: .83rem; color: #5a5145; line-height: 1.7; }
.benefits { background: var(--dark3); padding: 92px 0; }
.benefits .section-title {
font-family: 'Cormorant Garamond', serif;
font-size: clamp(2.4rem, 3.5vw, 3.8rem); font-weight: 300;
line-height: 1.1; color: var(--cream); margin-bottom: 52px; text-align: center;
}
.benefits-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 32px; }
.benefit-card {
background: var(--dark2); padding: 44px;
border: 1px solid rgba(201,168,76,.08); transition: all .25s;
}
.benefit-card:hover { background: #1a1710; transform: translateY(-4px); }
.benefit-icon { width: 42px; height: 42px; margin-bottom: 18px; }
.benefit-icon svg { width: 100%; height: 100%; stroke: var(--gold); fill: none; stroke-width: 1.2; }
.benefit-title {
font-family: 'Cormorant Garamond', serif;
font-size: 1.28rem; font-weight: 400; color: var(--cream); margin-bottom: 10px;
}
.benefit-desc { font-size: .82rem; color: var(--text-muted); line-height: 1.75; }
.cta-section { background: var(--dark2); padding: 110px 0; }
.cta-title {
font-family: 'Cormorant Garamond', serif;
font-size: clamp(2.8rem, 4.5vw, 4.2rem); font-weight: 300;
line-height: 1.1; color: var(--cream); margin-bottom: 18px; text-align: center;
}
.cta-desc {
font-size: .95rem; color: var(--text-muted); text-align: center;
margin-bottom: 42px; max-width: 600px; margin-left: auto; margin-right: auto;
}
.cta-buttons { display: flex; gap: 16px; justify-content: center; }
.cta-btn {
font-family: 'DM Sans', sans-serif; font-size: .72rem; font-weight: 500;
letter-spacing: .14em; text-transform: uppercase; text-decoration: none;
padding: 15px 36px; transition: all .2s; display: inline-block;
}
.cta-btn-primary { color: var(--dark); background: var(--gold); }
.cta-btn-primary:hover { background: var(--gold-light); transform: translateY(-2px); }
.cta-btn-secondary { color: var(--text-muted); border: 1px solid rgba(201,168,76,.25); }
.cta-btn-secondary:hover { border-color: var(--gold); color: var(--cream); }
@media(max-width:960px){
:root{--ml:20px}
/* Nav mobile */
nav{padding:0 20px;height:56px}
.nav-links,.nav-actions{display:none}
/* Hero: form em cima, headline embaixo */
.hero{grid-template-columns:1fr !important;min-height:auto}
.hero-headline-col{order:1 !important;padding:80px 20px 40px;grid-column:1 !important}
.hero-headline-title{font-size:clamp(2rem,8vw,3rem)}
.hero-headline-desc{max-width:100%;font-size:.88rem}
.hero-form-col{order:2 !important;min-height:100svh;grid-column:1 !important}
/* Step header */
.step-header{padding:28px 20px 0}
/* Slide */
.slide{padding:4px 20px 16px}
/* Nav buttons */
.slide-nav{padding:14px 20px 20px}
.slide-nav-btn{padding:10px 18px;font-size:.65rem}
/* Form row: 1 coluna no mobile */
.form-row{grid-template-columns:1fr}
/* Check grid: 1 coluna */
.check-grid{grid-template-columns:1fr}
/* Sections */
.how-cards{grid-template-columns:1fr}
.benefits-grid{grid-template-columns:1fr}
.cta-buttons{flex-direction:column;width:100%;align-items:center}
.cta-btn{width:100%;max-width:320px;text-align:center}
.section-container{padding:52px 20px}
.how-it-works{padding:52px 0}
.benefits{padding:52px 0}
.cta-section{padding:72px 0}
}
</style>
</head>
<body>
<nav>
  <a href="/" class="logo"><img src="<?php echo View::e(SistemaConfig::logo()); ?>" alt="<?php echo View::e(SistemaConfig::nome()); ?>"/></a>
  <div class="nav-links">
    <a href="/sobre">Sobre</a>
    <a href="/como-funciona">Como Funciona</a>
    <a href="/para-clientes">Para Clientes</a>
    <a href="/para-parceiros">Para Parceiros</a>
    <a href="/contato">Contato</a>
  </div>
  <div class="nav-actions">
    <a href="/cliente/entrar" class="btn-ghost">Entrar</a>
    <a href="/abrir-demanda" class="btn-primary">Abrir Demanda</a>
  </div>
  <button class="nav-burger" id="navBurger" aria-label="Menu">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
  </button>
</nav>
<div class="nav-drawer" id="navDrawer">
  <a href="/sobre">Sobre</a>
  <a href="/como-funciona">Como Funciona</a>
  <a href="/para-clientes">Para Clientes</a>
  <a href="/para-parceiros">Para Parceiros</a>
  <a href="/contato">Contato</a>
  <a href="/cliente/entrar" style="color:var(--gold)">Entrar</a>
  <a href="/abrir-demanda" style="background:var(--gold);color:var(--dark);padding:12px 20px;text-align:center;font-weight:500">Abrir Demanda</a>
</div>
<style>
.nav-burger{display:none;background:none;border:none;color:var(--cream);cursor:pointer;padding:4px}
.nav-drawer{display:none;position:fixed;top:56px;left:0;right:0;background:var(--dark2);border-bottom:1px solid rgba(201,168,76,.15);z-index:199;flex-direction:column;padding:16px 20px;gap:4px}
.nav-drawer a{display:block;padding:12px 0;font-size:.82rem;color:var(--text-light);text-decoration:none;border-bottom:1px solid rgba(201,168,76,.08);letter-spacing:.06em}
.nav-drawer a:last-child{border-bottom:none;margin-top:8px}
@media(max-width:960px){
  .nav-burger{display:flex}
  .nav-drawer.open{display:flex}
}
</style>
<script>
(function(){
  var btn=document.getElementById('navBurger'),drawer=document.getElementById('navDrawer');
  btn.addEventListener('click',function(){drawer.classList.toggle('open')});
})();
</script>

<?php if (!empty($_SESSION['flash'])): ?>
<div style="position:fixed;top:80px;left:50%;transform:translateX(-50%);z-index:9999;padding:14px 28px;border-radius:4px;font-size:.88rem;font-weight:500;max-width:500px;text-align:center;
  background:<?php echo $_SESSION['flash']['type'] === 'success' ? 'rgba(34,197,94,.95)' : 'rgba(239,68,68,.95)'; ?>;
  color:#fff;box-shadow:0 4px 20px rgba(0,0,0,.2)">
  <?php echo View::e($_SESSION['flash']['message']); ?>
</div>
<?php unset($_SESSION['flash']); endif; ?>

<?php echo $conteudo ?? ''; ?>

</body>
</html>
