<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
?>
<section class="inst-hero">
  <span class="lbl reveal"><?php echo View::e(I18n::t('pagina.vetriks')); ?></span>
  <h1 class="disp reveal d1">Selo <em>Vetriks</em></h1>
  <p class="reveal d2">O padrão de qualificação que garante confiança, experiência e capacidade de execução.</p>
</section>

<section class="inst-section">
  <h2 class="reveal">O que é o Selo <em>Vetriks</em></h2>
  <p class="reveal d1">O Selo Vetriks é a certificação exclusiva da Lexus que identifica parceiros que passaram por um processo rigoroso de qualificação. Ele certifica experiência comprovada, capacidade de execução e confiabilidade operacional.</p>
  <p class="reveal d1">Parceiros com Selo Vetriks têm prioridade na distribuição de oportunidades e maior visibilidade na plataforma.</p>
</section>

<section class="inst-section alt">
  <h2 class="reveal" style="color:var(--white)">Critérios de <em style="color:var(--gold)">Qualificação</em></h2>
  <p class="reveal d1" style="color:rgba(245,242,237,.5)">Experiência comprovada com portfólio verificado. Capacidade de execução avaliada pela equipe Lexus. Confiabilidade com referências e histórico de cumprimento de prazos. Documentação validada e estrutura operacional adequada.</p>
</section>

<section class="inst-section">
  <h2 class="reveal">Por que <em>importa</em></h2>
  <p class="reveal d1">Para clientes, o Selo Vetriks significa segurança na escolha. Para parceiros, significa reconhecimento e acesso a oportunidades qualificadas. Para a Lexus, significa curadoria real e compromisso com a qualidade.</p>
</section>

<section class="cta">
  <span class="lbl reveal"><?php echo View::e(I18n::t('cta.label')); ?></span>
  <h2 class="disp reveal d1">Quer ser um parceiro <em>Vetriks</em>?</h2>
  <div class="ctabts reveal d2">
    <a href="/seja-parceiro" class="btn-cta"><?php echo View::e(I18n::t('parceiro.titulo')); ?></a>
  </div>
</section>
