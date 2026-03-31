<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
?>
<section class="inst-hero">
  <span class="lbl reveal"><?php echo View::e(I18n::t('pagina.para_clientes')); ?></span>
  <h1 class="disp reveal d1">Sua obra merece <em>decisões melhores</em></h1>
  <p class="reveal d2">Descubra como a Lexus simplifica o processo de encontrar o parceiro ideal para o seu projeto.</p>
</section>

<section class="inst-section">
  <h2 class="reveal">Como funciona para <em>você</em></h2>
  <p class="reveal d1">Abra uma demanda descrevendo seu projeto. Nossa equipe estrutura sua necessidade, conecta com parceiros qualificados e apresenta as melhores propostas para sua decisão.</p>
  <p class="reveal d1">Você não precisa buscar sozinho. A Lexus faz a curadoria, coleta orçamentos e apoia sua escolha — sem intermediação financeira.</p>
</section>

<section class="inst-section alt">
  <h2 class="reveal" style="color:var(--white)">Benefícios</h2>
  <p class="reveal d1" style="color:rgba(245,242,237,.5)">Acesso a uma rede qualificada de parceiros com Selo Vetriks. Múltiplas propostas para comparação. Processo estruturado e transparente. Sem cobrança antecipada. Suporte da equipe Lexus em cada etapa.</p>
</section>

<section class="cta">
  <span class="lbl reveal"><?php echo View::e(I18n::t('cta.label')); ?></span>
  <h2 class="disp reveal d1">Pronto para <em>começar</em>?</h2>
  <div class="ctabts reveal d2">
    <a href="/abrir-demanda" class="btn-cta"><?php echo View::e(I18n::t('nav.abrir_demanda')); ?></a>
    <a href="/como-funciona" class="btn-out"><?php echo View::e(I18n::t('nav.como_funciona')); ?></a>
  </div>
</section>
