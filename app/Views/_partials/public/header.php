<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, SistemaConfig};
$logoUrl = SistemaConfig::logo();
?>
<div class="cursor" id="cursor"></div>
<div class="cursor-ring" id="cursorRing"></div>

<nav>
  <div class="nav-row">
    <a href="/" class="logo"><img src="<?php echo View::e($logoUrl); ?>" alt="<?php echo View::e(SistemaConfig::nome()); ?>" style="height:32px"/></a>
    <div class="nav-links">
      <a href="/sobre"><?php echo View::e(I18n::t('nav.sobre')); ?></a>
      <a href="/como-funciona"><?php echo View::e(I18n::t('nav.como_funciona')); ?></a>
      <a href="/para-clientes"><?php echo View::e(I18n::t('nav.para_clientes')); ?></a>
      <a href="/para-parceiros"><?php echo View::e(I18n::t('nav.para_parceiros')); ?></a>
      <a href="/vetriks"><?php echo View::e(I18n::t('nav.vetriks')); ?></a>
      <a href="/contato"><?php echo View::e(I18n::t('nav.contato')); ?></a>
    </div>
    <div class="nav-actions">
      <a href="/cliente/entrar" class="nav-login"><?php echo View::e(I18n::t('nav.entrar')); ?></a>
      <a href="/abrir-demanda" class="nav-btn"><?php echo View::e(I18n::t('nav.abrir_demanda')); ?></a>
    </div>
    <button class="burger" id="burger" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>
  <div class="drawer" id="drawer">
    <a href="/sobre"><?php echo View::e(I18n::t('nav.sobre')); ?></a>
    <a href="/como-funciona"><?php echo View::e(I18n::t('nav.como_funciona')); ?></a>
    <a href="/para-clientes"><?php echo View::e(I18n::t('nav.para_clientes')); ?></a>
    <a href="/para-parceiros"><?php echo View::e(I18n::t('nav.para_parceiros')); ?></a>
    <a href="/vetriks"><?php echo View::e(I18n::t('nav.vetriks')); ?></a>
    <a href="/contato"><?php echo View::e(I18n::t('nav.contato')); ?></a>
    <a href="/cliente/entrar" class="d-login"><?php echo View::e(I18n::t('nav.entrar')); ?></a>
    <a href="/abrir-demanda" class="d-cta"><?php echo View::e(I18n::t('nav.abrir_demanda')); ?></a>
  </div>
</nav>
