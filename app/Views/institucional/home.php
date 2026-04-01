<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
?>

<!-- HERO -->
<section class="hero">
  <div class="hgrid"></div>
  <div class="hero-img"><img src="/assets/img/foto_hero.png" alt="Obra de alto padrão"/></div>
  <div class="hero-orb"></div>
  <div class="hero-content">
    <span class="lbl"><?php echo View::e(I18n::t('hero.label')); ?></span>
    <h1 class="htitle"><?php echo I18n::t('hero.title'); ?></h1>
    <p class="hbody"><?php echo View::e(I18n::t('hero.body')); ?></p>
    <div class="hactions">
      <a href="/sobre" class="btn-blk"><?php echo View::e(I18n::t('hero.cta_primary')); ?></a>
      <a href="/como-funciona" class="btn-wht"><?php echo View::e(I18n::t('hero.cta_secondary')); ?></a>
    </div>
  </div>
  <div class="hstats">
    <div class="stat"><div class="snum">150+</div><div class="slbl"><?php echo View::e(I18n::t('hero.stat_empresas')); ?></div></div>
    <div class="stat"><div class="snum">300+</div><div class="slbl"><?php echo View::e(I18n::t('hero.stat_prestadores')); ?></div></div>
  </div>
  <div class="scrollhint"><div class="sline"></div><?php echo View::e(I18n::t('hero.scroll')); ?></div>
</section>

<!-- SOBRE -->
<section class="about" id="sobre">
  <div class="about-l reveal">
    <span class="lbl"><?php echo View::e(I18n::t('sobre.label')); ?></span>
    <h2 class="disp"><?php echo I18n::t('sobre.title'); ?></h2>
  </div>
  <div class="about-r reveal d1">
    <p class="atxt"><?php echo I18n::t('sobre.p1'); ?></p>
    <p class="atxt"><?php echo I18n::t('sobre.p2'); ?></p>
    <p class="atxt"><?php echo I18n::t('sobre.p3'); ?></p>
    <span class="apill"><?php echo View::e(I18n::t('sobre.pill')); ?></span>
  </div>
</section>

<!-- METODOLOGIA -->
<section class="how" id="metodologia">
  <div class="how-hd reveal">
    <div>
      <span class="lbl"><?php echo View::e(I18n::t('metodo.label')); ?></span>
      <h2 class="disp"><?php echo View::e(I18n::t('metodo.title')); ?></h2>
    </div>
    <p class="how-intro"><?php echo View::e(I18n::t('metodo.intro')); ?></p>
  </div>
  <div class="steps">
    <div class="step reveal">
      <div class="sbar"></div>
      <div class="sn">01</div>
      <div class="st"><?php echo View::e(I18n::t('metodo.step1_title')); ?></div>
      <p class="sd"><?php echo View::e(I18n::t('metodo.step1_desc')); ?></p>
    </div>
    <div class="step reveal d1">
      <div class="sbar"></div>
      <div class="sn">02</div>
      <div class="st"><?php echo View::e(I18n::t('metodo.step2_title')); ?></div>
      <p class="sd"><?php echo View::e(I18n::t('metodo.step2_desc')); ?></p>
    </div>
    <div class="step reveal d1">
      <div class="sbar"></div>
      <div class="sn">03</div>
      <div class="st"><?php echo View::e(I18n::t('metodo.step3_title')); ?></div>
      <p class="sd"><?php echo View::e(I18n::t('metodo.step3_desc')); ?></p>
    </div>
    <div class="step reveal d2">
      <div class="sbar"></div>
      <div class="sn">04</div>
      <div class="st"><?php echo View::e(I18n::t('metodo.step4_title')); ?></div>
      <p class="sd"><?php echo View::e(I18n::t('metodo.step4_desc')); ?></p>
    </div>
  </div>
</section>

<!-- VETRIKS -->
<section class="vetriks" id="vetriks">
  <div class="vetriks-inner">
    <div class="vetriks-header reveal">
      <span class="lbl"><?php echo View::e(I18n::t('vetriks.label')); ?></span>
      <h2 class="disp"><?php echo I18n::t('vetriks.title'); ?></h2>
      <p class="vdesc"><?php echo View::e(I18n::t('vetriks.desc')); ?></p>
      <div class="sealbox">
        <div class="sealico">
          <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.25 3.75 10.15 9 11.35C17.25 21.15 21 16.25 21 11V5L12 1zm-1 14l-4-4 1.41-1.41L11 12.17l6.59-6.58L19 7l-8 8z"/></svg>
        </div>
        <span class="sealtxt"><?php echo View::e(I18n::t('vetriks.seal')); ?></span>
      </div>
    </div>
    <div class="vetriks-grid">
      <div class="vetriks-card reveal">
        <div class="vc-icon">✦</div>
        <div class="vc-title"><?php echo View::e(I18n::t('vetriks.crit1_title')); ?></div>
        <p class="vc-desc"><?php echo View::e(I18n::t('vetriks.crit1_desc')); ?></p>
      </div>
      <div class="vetriks-card reveal d1">
        <div class="vc-icon">◈</div>
        <div class="vc-title"><?php echo View::e(I18n::t('vetriks.crit2_title')); ?></div>
        <p class="vc-desc"><?php echo View::e(I18n::t('vetriks.crit2_desc')); ?></p>
      </div>
      <div class="vetriks-card reveal d2">
        <div class="vc-icon">◉</div>
        <div class="vc-title"><?php echo View::e(I18n::t('vetriks.crit3_title')); ?></div>
        <p class="vc-desc"><?php echo View::e(I18n::t('vetriks.crit3_desc')); ?></p>
      </div>
    </div>
  </div>
</section>

<!-- MODELO -->
<section class="modelo" id="modelo">
  <span class="lbl reveal"><?php echo View::e(I18n::t('modelo.label')); ?></span>
  <h2 class="disp reveal d1"><?php echo I18n::t('modelo.title'); ?></h2>
  <div class="mgrid">
    <div class="mcard reveal">
      <div class="mico">◈</div>
      <div class="mtit"><?php echo View::e(I18n::t('modelo.card1_title')); ?></div>
      <p class="mdesc"><?php echo View::e(I18n::t('modelo.card1_desc')); ?></p>
    </div>
    <div class="mcard reveal d1">
      <div class="mico">◉</div>
      <div class="mtit"><?php echo View::e(I18n::t('modelo.card2_title')); ?></div>
      <p class="mdesc"><?php echo View::e(I18n::t('modelo.card2_desc')); ?></p>
    </div>
    <div class="mcard reveal d2">
      <div class="mico">◬</div>
      <div class="mtit"><?php echo View::e(I18n::t('modelo.card3_title')); ?></div>
      <p class="mdesc"><?php echo View::e(I18n::t('modelo.card3_desc')); ?></p>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta" id="contato">
  <span class="lbl reveal"><?php echo View::e(I18n::t('cta.label')); ?></span>
  <h2 class="disp reveal d1"><?php echo I18n::t('cta.title'); ?></h2>
  <p class="ctasub reveal d1"><?php echo View::e(I18n::t('cta.body')); ?></p>
  <div class="ctabts reveal d2">
    <a href="/contato" class="btn-cta"><?php echo View::e(I18n::t('cta.btn_contato')); ?></a>
    <a href="/sobre" class="btn-out"><?php echo View::e(I18n::t('cta.btn_saiba')); ?></a>
  </div>
</section>
