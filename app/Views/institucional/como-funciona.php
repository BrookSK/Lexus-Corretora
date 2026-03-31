<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
?>
<section class="inst-hero">
  <span class="lbl reveal"><?php echo View::e(I18n::t('pagina.como_funciona')); ?></span>
  <h1 class="disp reveal d1">Como a Lexus <em>transforma</em> o processo da sua obra</h1>
  <p class="reveal d2">Entenda cada etapa do nosso processo — da entrada da demanda até a formalização do contrato.</p>
</section>

<section class="inst-section">
  <h2 class="reveal">1. Entrada da <em>Demanda</em></h2>
  <p class="reveal d1">O cliente entra em contato com a Lexus via formulário do site, painel ou contato direto. Registramos uma nova demanda com todos os detalhes do projeto.</p>
</section>

<section class="inst-section alt">
  <h2 class="reveal">2. Estruturação e <em>Organização</em></h2>
  <p class="reveal d1" style="color:rgba(245,242,237,.5)">Nossa equipe analisa e organiza a demanda: tipo de obra, localização, orçamento, escopo, urgência, documentação e perfil ideal do parceiro.</p>
</section>

<section class="inst-section">
  <h2 class="reveal">3. Distribuição para <em>Parceiros</em></h2>
  <p class="reveal d1">A oportunidade é disponibilizada para parceiros elegíveis — de forma direcionada ou inteligente, com base em região, especialidade, qualificação e score.</p>
</section>

<section class="inst-section alt">
  <h2 class="reveal">4. Coleta de <em>Propostas</em></h2>
  <p class="reveal d1" style="color:rgba(245,242,237,.5)">Parceiros interessados enviam propostas estruturadas com valor, prazo, condições e diferenciais. A Lexus reúne e organiza tudo para análise.</p>
</section>

<section class="inst-section">
  <h2 class="reveal">5. Curadoria e <em>Comparação</em></h2>
  <p class="reveal d1">A equipe Lexus analisa as propostas, compara lado a lado, classifica e seleciona as melhores opções para apresentar ao cliente.</p>
</section>

<section class="inst-section alt">
  <h2 class="reveal">6. Contrato <em>Direto</em></h2>
  <p class="reveal d1" style="color:rgba(245,242,237,.5)">Quando cliente e parceiro avançam, a formalização do contrato ocorre diretamente entre as partes. A Lexus não intermedia financeiramente — transparência total.</p>
</section>

<section class="cta">
  <span class="lbl reveal"><?php echo View::e(I18n::t('cta.label')); ?></span>
  <h2 class="disp reveal d1"><?php echo I18n::t('cta.title'); ?></h2>
  <div class="ctabts reveal d2">
    <a href="/abrir-demanda" class="btn-cta"><?php echo View::e(I18n::t('nav.abrir_demanda')); ?></a>
    <a href="/contato" class="btn-out"><?php echo View::e(I18n::t('cta.btn_contato')); ?></a>
  </div>
</section>
