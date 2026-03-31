<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
?>
<section class="inst-hero">
  <span class="lbl reveal"><?php echo View::e(I18n::t('pagina.sobre')); ?></span>
  <h1 class="disp reveal d1">Estruturação <em>estratégica</em> de obras</h1>
  <p class="reveal d2">Conheça a Lexus — a corretora que conecta clientes a parceiros qualificados com método, agilidade e transparência.</p>
</section>

<section class="inst-section">
  <h2 class="reveal">Quem <em>somos</em></h2>
  <p class="reveal d1">A Lexus é uma plataforma de estruturação, conexão e repasse de oportunidades de obras e reformas. Atuamos como uma corretora estratégica entre clientes finais e empresas/profissionais executores.</p>
  <p class="reveal d1">Não executamos obras. Nosso papel é captar a demanda, estruturar a necessidade, organizar o escopo, distribuir para parceiros qualificados, coletar propostas, apoiar a seleção e formalizar o vínculo — com total transparência.</p>
</section>

<section class="inst-section alt">
  <h2 class="reveal" style="color:var(--white)">Nossa <em style="color:var(--gold)">missão</em></h2>
  <p class="reveal d1" style="color:rgba(245,242,237,.5)">Tornar o processo de contratação de obras mais simples, rápido e eficiente — conectando quem precisa a quem executa, com método e confiança.</p>
</section>

<section class="cta">
  <span class="lbl reveal"><?php echo View::e(I18n::t('cta.label')); ?></span>
  <h2 class="disp reveal d1"><?php echo I18n::t('cta.title'); ?></h2>
  <div class="ctabts reveal d2">
    <a href="/contato" class="btn-cta"><?php echo View::e(I18n::t('cta.btn_contato')); ?></a>
    <a href="/como-funciona" class="btn-out"><?php echo View::e(I18n::t('nav.como_funciona')); ?></a>
  </div>
</section>
