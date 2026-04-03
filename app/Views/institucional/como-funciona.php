<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
?>

<style>
/* Como Funciona - Premium Layout */
.cf-hero{background:linear-gradient(135deg,#0C0C0A 0%,#1a1816 100%);color:#F5F2ED;padding:180px 64px 120px;position:relative;overflow:hidden}
.cf-hero::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle at 20% 50%,rgba(184,148,90,.08) 0%,transparent 50%),radial-gradient(circle at 80% 80%,rgba(184,148,90,.05) 0%,transparent 50%);pointer-events:none}
.cf-hero-content{max-width:800px;margin:0 auto;text-align:center;position:relative;z-index:2}
.cf-hero .lbl{color:#B8945A;justify-content:center;margin-bottom:24px;font-size:.7rem;letter-spacing:.28em}
.cf-hero h1{font-family:'Cormorant Garamond',serif;font-size:clamp(3rem,5.5vw,5rem);font-weight:300;line-height:1.08;margin-bottom:28px}
.cf-hero h1 em{font-style:italic;color:#B8945A}
.cf-hero p{font-size:1.05rem;line-height:1.75;color:rgba(245,242,237,.55);max-width:620px;margin:0 auto}

.cf-process{background:#F5F2ED;padding:120px 64px;position:relative}
.cf-process-inner{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:200px 1fr;gap:80px;align-items:start}

.cf-sidebar{position:sticky;top:120px;display:flex;flex-direction:column;gap:32px}
.cf-dot{width:16px;height:16px;border-radius:50%;border:2px solid rgba(184,148,90,.3);background:transparent;transition:all .4s;cursor:pointer;position:relative}
.cf-dot.active{background:#B8945A;border-color:#B8945A;transform:scale(1.3)}
.cf-dot::after{content:'';position:absolute;left:50%;top:100%;width:2px;height:32px;background:rgba(184,148,90,.2);transform:translateX(-50%);margin-top:8px}
.cf-dot:last-child::after{display:none}

.cf-timeline{display:flex;flex-direction:column;gap:100px}
.cf-step{opacity:0;transform:translateY(40px);transition:opacity .8s,transform .8s}
.cf-step.in{opacity:1;transform:translateY(0)}
.cf-step-num{font-family:'Cormorant Garamond',serif;font-size:5rem;font-weight:300;color:rgba(184,148,90,.15);line-height:1;margin-bottom:16px}
.cf-step-title{font-family:'Cormorant Garamond',serif;font-size:2.4rem;font-weight:300;color:#0C0C0A;margin-bottom:20px;line-height:1.15}
.cf-step-title em{font-style:italic;color:#B8945A}
.cf-step-desc{font-size:1rem;line-height:1.82;color:rgba(12,12,10,.65);max-width:680px}

.cf-why{background:#0C0C0A;color:#F5F2ED;padding:120px 64px;position:relative}
.cf-why::before{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(184,148,90,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(184,148,90,.03) 1px,transparent 1px);background-size:80px 80px;mask-image:radial-gradient(ellipse 70% 60% at 50% 50%,black 0%,transparent 100%);pointer-events:none}
.cf-why-inner{max-width:1200px;margin:0 auto;position:relative;z-index:2}
.cf-why-header{text-align:center;margin-bottom:80px}
.cf-why-header .lbl{color:#B8945A;justify-content:center;margin-bottom:20px}
.cf-why-header h2{font-family:'Cormorant Garamond',serif;font-size:clamp(2.6rem,4.5vw,4rem);font-weight:300;color:#F5F2ED;line-height:1.12}
.cf-why-header h2 em{font-style:italic;color:#B8945A}
.cf-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:rgba(184,148,90,.15);border:1px solid rgba(184,148,90,.15)}
.cf-card{background:#0C0C0A;padding:48px 36px;transition:background .3s;position:relative}
.cf-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:#B8945A;transform:scaleX(0);transform-origin:left;transition:transform .4s}
.cf-card:hover::before{transform:scaleX(1)}
.cf-card:hover{background:rgba(184,148,90,.04)}
.cf-card-icon{width:48px;height:48px;border-radius:50%;border:1px solid rgba(184,148,90,.35);display:flex;align-items:center;justify-content:center;margin-bottom:24px;font-size:1.3rem}
.cf-card-title{font-size:1.05rem;font-weight:500;color:#F5F2ED;margin-bottom:12px}
.cf-card-desc{font-size:.9rem;line-height:1.75;color:rgba(245,242,237,.5)}

@media(max-width:768px){
  .cf-hero{padding:120px 24px 80px}
  .cf-hero h1{font-size:2.6rem}
  .cf-process{padding:80px 24px}
  .cf-process-inner{grid-template-columns:1fr;gap:0}
  .cf-sidebar{display:none}
  .cf-timeline{gap:60px}
  .cf-step-num{font-size:3.5rem}
  .cf-step-title{font-size:1.9rem}
  .cf-why{padding:80px 24px}
  .cf-cards{grid-template-columns:1fr}
  .cf-card{padding:36px 24px}
}
</style>

<!-- Hero -->
<section class="cf-hero">
  <div class="cf-hero-content">
    <span class="lbl reveal"><?php echo View::e(I18n::t('pagina.como_funciona')); ?></span>
    <h1 class="reveal d1">Como a Lexus <em>transforma</em> o processo da sua obra</h1>
    <p class="reveal d2">Entenda cada etapa do nosso processo — da entrada da demanda até a formalização do contrato.</p>
  </div>
</section>

<!-- Process Timeline -->
<section class="cf-process">
  <div class="cf-process-inner">
    <!-- Sidebar Progress -->
    <div class="cf-sidebar">
      <div class="cf-dot active" data-step="1"></div>
      <div class="cf-dot" data-step="2"></div>
      <div class="cf-dot" data-step="3"></div>
      <div class="cf-dot" data-step="4"></div>
      <div class="cf-dot" data-step="5"></div>
      <div class="cf-dot" data-step="6"></div>
    </div>

    <!-- Timeline Steps -->
    <div class="cf-timeline">
      <div class="cf-step" data-step="1">
        <div class="cf-step-num">01</div>
        <h2 class="cf-step-title">Entrada da <em>Demanda</em></h2>
        <p class="cf-step-desc">O cliente entra em contato com a Lexus via formulário do site, painel ou contato direto. Registramos uma nova demanda com todos os detalhes do projeto.</p>
      </div>

      <div class="cf-step" data-step="2">
        <div class="cf-step-num">02</div>
        <h2 class="cf-step-title">Estruturação e <em>Organização</em></h2>
        <p class="cf-step-desc">Nossa equipe analisa e organiza a demanda: tipo de obra, localização, orçamento, escopo, urgência, documentação e perfil ideal do parceiro.</p>
      </div>

      <div class="cf-step" data-step="3">
        <div class="cf-step-num">03</div>
        <h2 class="cf-step-title">Distribuição para <em>Parceiros</em></h2>
        <p class="cf-step-desc">A oportunidade é disponibilizada para parceiros elegíveis — de forma direcionada ou inteligente, com base em região, especialidade, qualificação e score.</p>
      </div>

      <div class="cf-step" data-step="4">
        <div class="cf-step-num">04</div>
        <h2 class="cf-step-title">Coleta de <em>Propostas</em></h2>
        <p class="cf-step-desc">Parceiros interessados enviam propostas estruturadas com valor, prazo, condições e diferenciais. A Lexus reúne e organiza tudo para análise.</p>
      </div>

      <div class="cf-step" data-step="5">
        <div class="cf-step-num">05</div>
        <h2 class="cf-step-title">Curadoria e <em>Comparação</em></h2>
        <p class="cf-step-desc">A equipe Lexus analisa as propostas, compara lado a lado, classifica e seleciona as melhores opções para apresentar ao cliente.</p>
      </div>

      <div class="cf-step" data-step="6">
        <div class="cf-step-num">06</div>
        <h2 class="cf-step-title">Contrato <em>Direto</em></h2>
        <p class="cf-step-desc">Quando cliente e parceiro avançam, a formalização do contrato ocorre diretamente entre as partes. A Lexus não intermedia financeiramente — transparência total.</p>
      </div>
    </div>
  </div>
</section>

<!-- Why Lexus is Different -->
<section class="cf-why">
  <div class="cf-why-inner">
    <div class="cf-why-header">
      <span class="lbl reveal">Diferenciais</span>
      <h2 class="reveal d1">Por que a Lexus é <em>diferente</em></h2>
    </div>

    <div class="cf-cards">
      <div class="cf-card reveal">
        <div class="cf-card-icon">🎯</div>
        <h3 class="cf-card-title">Curadoria Especializada</h3>
        <p class="cf-card-desc">Selecionamos apenas os melhores parceiros para cada tipo de projeto, garantindo qualidade e expertise.</p>
      </div>

      <div class="cf-card reveal d1">
        <div class="cf-card-icon">⚡</div>
        <h3 class="cf-card-title">Processo Ágil</h3>
        <p class="cf-card-desc">Tecnologia e automação para conectar clientes e parceiros de forma rápida e eficiente.</p>
      </div>

      <div class="cf-card reveal d2">
        <div class="cf-card-icon">🔒</div>
        <h3 class="cf-card-title">Transparência Total</h3>
        <p class="cf-card-desc">Sem intermediação financeira. Contratos diretos entre cliente e parceiro, com total clareza.</p>
      </div>

      <div class="cf-card reveal">
        <div class="cf-card-icon">📊</div>
        <h3 class="cf-card-title">Análise Comparativa</h3>
        <p class="cf-card-desc">Organizamos e comparamos propostas lado a lado para facilitar sua decisão.</p>
      </div>

      <div class="cf-card reveal d1">
        <div class="cf-card-icon">🤝</div>
        <h3 class="cf-card-title">Suporte Contínuo</h3>
        <p class="cf-card-desc">Acompanhamento em todas as etapas, desde a demanda inicial até a formalização.</p>
      </div>

      <div class="cf-card reveal d2">
        <div class="cf-card-icon">✨</div>
        <h3 class="cf-card-title">Rede Qualificada</h3>
        <p class="cf-card-desc">Parceiros verificados e avaliados continuamente para garantir excelência.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta">
  <span class="lbl reveal"><?php echo View::e(I18n::t('cta.label')); ?></span>
  <h2 class="disp reveal d1"><?php echo I18n::t('cta.title'); ?></h2>
  <div class="ctabts reveal d2">
    <a href="/abrir-demanda" class="btn-cta"><?php echo View::e(I18n::t('nav.abrir_demanda')); ?></a>
    <a href="/contato" class="btn-out"><?php echo View::e(I18n::t('cta.btn_contato')); ?></a>
  </div>
</section>

<script>
// Scroll Progress & Reveal Animations
document.addEventListener('DOMContentLoaded', function() {
  const steps = document.querySelectorAll('.cf-step');
  const dots = document.querySelectorAll('.cf-dot');
  
  // Reveal on scroll
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in');
        
        // Update sidebar dots
        const stepNum = entry.target.dataset.step;
        if (stepNum) {
          dots.forEach(dot => {
            if (parseInt(dot.dataset.step) <= parseInt(stepNum)) {
              dot.classList.add('active');
            } else {
              dot.classList.remove('active');
            }
          });
        }
      }
    });
  }, { threshold: 0.3 });

  steps.forEach(step => revealObserver.observe(step));
  
  // Generic reveal for other elements
  document.querySelectorAll('.reveal').forEach(el => {
    revealObserver.observe(el);
  });

  // Dot click navigation
  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      const stepNum = dot.dataset.step;
      const targetStep = document.querySelector(`.cf-step[data-step="${stepNum}"]`);
      if (targetStep) {
        targetStep.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  });
});
</script>
