<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
?>
<section class="inst-hero">
  <span class="lbl reveal"><?php echo View::e(I18n::t('pagina.para_parceiros')); ?></span>
  <h1 class="disp reveal d1">Receba oportunidades <em>qualificadas</em></h1>
  <p class="reveal d2">Faça parte da rede Lexus e receba demandas estruturadas, compatíveis com seu perfil e especialidade.</p>
</section>

<section class="inst-section">
  <h2 class="reveal">Como funciona para <em>parceiros</em></h2>
  <p class="reveal d1">Cadastre-se na plataforma e complete seu perfil profissional. Após análise e qualificação, você passa a receber oportunidades compatíveis com sua região, especialidade e porte de atuação.</p>
  <p class="reveal d1">Analise as oportunidades, envie propostas estruturadas e acompanhe todo o processo pelo painel. A Lexus cuida da curadoria e apresentação ao cliente.</p>
</section>

<section class="inst-section alt">
  <h2 class="reveal" style="color:var(--white)">Selo <em style="color:var(--gold)">Vetriks</em></h2>
  <p class="reveal d1" style="color:rgba(245,242,237,.5)">Parceiros que passam pelo processo de qualificação recebem o Selo Vetriks — um certificado de experiência, capacidade e confiabilidade que aumenta sua visibilidade e credibilidade na plataforma.</p>
</section>

<section class="cta">
  <span class="lbl reveal"><?php echo View::e(I18n::t('cta.label')); ?></span>
  <h2 class="disp reveal d1">Quer fazer parte da <em>rede Lexus</em>?</h2>
  <div class="ctabts reveal d2">
    <a href="/seja-parceiro" class="btn-cta"><?php echo View::e(I18n::t('parceiro.titulo')); ?></a>
    <a href="/vetriks" class="btn-out"><?php echo View::e(I18n::t('nav.vetriks')); ?></a>
  </div>
</section>
