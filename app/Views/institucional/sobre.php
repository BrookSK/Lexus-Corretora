<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
?>

<style>
/* Sobre - Premium Layout */
.sobre-hero{min-height:100vh;position:relative;overflow:hidden;display:grid;grid-template-columns:1fr 1fr;padding-top:64px;background:var(--black)}
.sobre-hero-left{display:flex;flex-direction:column;justify-content:flex-end;padding:80px 64px;position:relative;z-index:2}
.sobre-hero-eyebrow{display:flex;align-items:center;gap:10px;font-size:.65rem;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);margin-bottom:36px;opacity:0;animation:fadeUp .7s ease both}
.sobre-hero-eyebrow::before{content:'';display:block;width:28px;height:1px;background:var(--gold)}
.sobre-hero h1{font-family:'Cormorant Garamond',serif;font-size:clamp(3rem,5.5vw,6rem);font-weight:300;line-height:1.02;color:var(--white);margin-bottom:32px;letter-spacing:-.01em;opacity:0;animation:fadeUp .7s .12s ease both}
.sobre-hero h1 em{font-style:italic;color:var(--gold)}
.sobre-hero-p{font-size:.95rem;color:rgba(245,242,237,.7);max-width:400px;line-height:1.85;margin-bottom:48px;opacity:0;animation:fadeUp .7s .24s ease both}
.sobre-hero-actions{display:flex;gap:12px;flex-wrap:wrap;opacity:0;animation:fadeUp .7s .36s ease both}
.sobre-hero-right{position:relative;overflow:hidden;background:rgba(12,12,10,.5);border-left:1px solid rgba(184,148,90,.08)}
.sobre-hero-geo{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;overflow:hidden}
.geo-ring{position:absolute;border-radius:50%;border:1px solid rgba(184,148,90,.08);animation:slowSpin 60s linear infinite}
.geo-ring:nth-child(1){width:480px;height:480px;top:-80px;right:-80px}
.geo-ring:nth-child(2){width:340px;height:340px;top:40px;right:40px;animation-direction:reverse;animation-duration:45s}
.geo-ring:nth-child(3){width:200px;height:200px;top:120px;right:120px;border-color:rgba(184,148,90,.15)}
@keyframes slowSpin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
.geo-cross{position:absolute;top:50%;right:50%;transform:translate(50%,-50%)}
.geo-cross::before,.geo-cross::after{content:'';position:absolute;background:rgba(184,148,90,.18)}
.geo-cross::before{width:1px;height:200px;left:0;top:-100px}
.geo-cross::after{width:200px;height:1px;top:0;left:-100px}
.geo-glow{position:absolute;width:500px;height:500px;top:50%;right:50%;transform:translate(50%,-50%);background:radial-gradient(circle,rgba(184,148,90,.08) 0%,transparent 65%);pointer-events:none}
.sobre-hero-quote{position:absolute;bottom:52px;right:52px;left:52px;z-index:2;border:1px solid rgba(184,148,90,.2);background:rgba(11,9,6,.7);backdrop-filter:blur(8px);padding:32px 36px;opacity:0;animation:fadeUp .8s .4s ease both}
.quote-mark{font-family:'Cormorant Garamond',serif;font-size:4rem;font-weight:300;color:var(--gold);line-height:.8;margin-bottom:12px;display:block}
.quote-text{font-family:'Cormorant Garamond',serif;font-size:1.35rem;font-weight:300;font-style:italic;color:var(--white);line-height:1.5;margin-bottom:16px}
.quote-attr{font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;color:rgba(245,242,237,.5)}

.stats-band{background:var(--gold);display:grid;grid-template-columns:repeat(4,1fr)}
.stat-cell{padding:36px 40px;border-right:1px solid rgba(11,9,6,.12);position:relative;overflow:hidden;transition:background .25s}
.stat-cell:last-child{border-right:none}
.stat-cell:hover{background:rgba(11,9,6,.07)}
.stat-num{font-family:'Cormorant Garamond',serif;font-size:3rem;font-weight:300;color:var(--black);line-height:1;margin-bottom:6px}
.stat-label{font-size:.68rem;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,9,6,.6)}

.quem-section{background:var(--white);display:grid;grid-template-columns:1fr 1fr;min-height:70vh}
.quem-left{padding:96px 64px;display:flex;flex-direction:column;justify-content:center;border-right:1px solid rgba(14,12,9,.08)}
.section-eyebrow{display:flex;align-items:center;gap:10px;font-size:.65rem;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);margin-bottom:22px}
.section-eyebrow::before{content:'';display:block;width:28px;height:1px;background:var(--gold)}
.quem-h2{font-family:'Cormorant Garamond',serif;font-size:clamp(2.8rem,4vw,4.2rem);font-weight:300;line-height:1.08;color:var(--black);margin-bottom:28px}
.quem-h2 em{font-style:italic;color:var(--gold)}
.quem-p{font-size:.93rem;color:#4a4338;line-height:1.9;max-width:420px;margin-bottom:16px}
.quem-divider{width:48px;height:1px;background:var(--gold);margin-bottom:28px}
.quem-right{background:var(--black);display:flex;flex-direction:column}
.quem-card{flex:1;padding:48px 52px;border-bottom:1px solid rgba(184,148,90,.1);position:relative;overflow:hidden;transition:background .3s}
.quem-card:last-child{border-bottom:none}
.quem-card:hover{background:rgba(12,12,10,.95)}
.quem-card-tag{font-size:.62rem;letter-spacing:.18em;text-transform:uppercase;color:var(--gold);margin-bottom:16px;display:flex;align-items:center;gap:8px}
.quem-card-tag::before{content:'';display:block;width:14px;height:1px;background:var(--gold)}
.quem-card-title{font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:300;color:var(--white);margin-bottom:12px;line-height:1.2}
.quem-card-title em{font-style:italic;color:var(--gold)}
.quem-card-desc{font-size:.83rem;color:rgba(245,242,237,.6);line-height:1.8;max-width:340px}
.quem-card-deco{position:absolute;right:40px;bottom:24px;font-family:'Cormorant Garamond',serif;font-size:6rem;font-weight:600;color:transparent;-webkit-text-stroke:1px rgba(184,148,90,.07);line-height:1;pointer-events:none;user-select:none}

.missao-section{background:rgba(12,12,10,.98);color:var(--white);padding:120px 64px;position:relative;overflow:hidden;display:flex;flex-direction:column;align-items:center;text-align:center}
.missao-section::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 50% 50%,rgba(184,148,90,.06) 0%,transparent 70%);pointer-events:none}
.missao-eyebrow{display:flex;align-items:center;gap:12px;justify-content:center;font-size:.65rem;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);margin-bottom:36px}
.missao-eyebrow::before,.missao-eyebrow::after{content:'';display:block;width:40px;height:1px;background:var(--gold)}
.missao-h2{font-family:'Cormorant Garamond',serif;font-size:clamp(2.8rem,5vw,5.5rem);font-weight:300;line-height:1.1;color:var(--white);max-width:860px;position:relative;z-index:1;letter-spacing:-.01em}
.missao-h2 em{font-style:italic;color:var(--gold)}
.missao-rule{width:1px;height:72px;background:linear-gradient(to bottom,var(--gold),transparent);margin:44px auto 0}

.valores-section{background:var(--black);padding:100px 64px}
.valores-header{display:flex;align-items:flex-end;justify-content:space-between;gap:48px;margin-bottom:64px}
.valores-h2{font-family:'Cormorant Garamond',serif;font-size:clamp(2.4rem,4vw,3.8rem);font-weight:300;line-height:1.1;color:var(--white)}
.valores-h2 em{font-style:italic;color:var(--gold)}
.valores-sub{font-size:.85rem;color:rgba(245,242,237,.5);max-width:300px;line-height:1.75;flex-shrink:0}
.valores-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:rgba(184,148,90,.08);border:1px solid rgba(184,148,90,.08)}
.valor-card{background:rgba(12,12,10,.95);padding:48px 40px;position:relative;overflow:hidden;transition:background .3s}
.valor-card:hover{background:rgba(26,23,16,.95)}
.valor-card::after{content:'';position:absolute;left:0;top:0;bottom:0;width:2px;background:var(--gold);transform:scaleY(0);transform-origin:bottom;transition:transform .4s cubic-bezier(.4,0,.2,1)}
.valor-card:hover::after{transform:scaleY(1)}
.valor-icon{width:44px;height:44px;border:1px solid rgba(184,148,90,.22);display:flex;align-items:center;justify-content:center;margin-bottom:24px;transition:border-color .3s;font-size:1.3rem}
.valor-card:hover .valor-icon{border-color:var(--gold)}
.valor-title{font-family:'Cormorant Garamond',serif;font-size:1.45rem;font-weight:400;color:var(--white);margin-bottom:12px}
.valor-desc{font-size:.82rem;color:rgba(245,242,237,.5);line-height:1.8}
.valor-num{position:absolute;bottom:20px;right:28px;font-family:'Cormorant Garamond',serif;font-size:4.5rem;font-weight:600;color:transparent;-webkit-text-stroke:1px rgba(184,148,90,.06);pointer-events:none;user-select:none;line-height:1}

.pos-band{background:rgba(19,17,9,.98);padding:72px 64px;display:grid;grid-template-columns:1fr 1px 1fr;gap:0 64px;align-items:center;border-top:1px solid rgba(184,148,90,.08);border-bottom:1px solid rgba(184,148,90,.08)}
.pos-divider{width:1px;background:rgba(184,148,90,.15);align-self:stretch}
.pos-tag{font-size:.62rem;letter-spacing:.18em;text-transform:uppercase;color:var(--gold);margin-bottom:14px;display:flex;align-items:center;gap:8px}
.pos-tag::before{content:'';display:block;width:14px;height:1px;background:var(--gold)}
.pos-title{font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:300;color:var(--white);margin-bottom:14px;line-height:1.2}
.pos-title em{font-style:italic;color:var(--gold)}
.pos-text{font-size:.85rem;color:rgba(245,242,237,.5);line-height:1.85;max-width:380px}

@keyframes fadeUp{from{opacity:0;transform:translateY(26px)}to{opacity:1;transform:translateY(0)}}

@media(max-width:768px){
  .sobre-hero{grid-template-columns:1fr;min-height:auto}
  .sobre-hero-left{padding:90px 24px 60px}
  .sobre-hero-right{min-height:400px}
  .sobre-hero-quote{left:24px;right:24px;bottom:24px;padding:24px}
  .quote-text{font-size:1.1rem}
  .stats-band{grid-template-columns:1fr 1fr}
  .stat-cell{border-bottom:1px solid rgba(11,9,6,.12)}
  .quem-section{grid-template-columns:1fr}
  .quem-left{padding:60px 24px;border-right:none;border-bottom:1px solid rgba(14,12,9,.08)}
  .quem-right{flex-direction:column}
  .quem-card{padding:36px 24px}
  .missao-section{padding:80px 24px}
  .valores-section{padding:80px 24px}
  .valores-header{flex-direction:column;align-items:flex-start}
  .valores-grid{grid-template-columns:1fr}
  .pos-band{grid-template-columns:1fr;gap:40px;padding:60px 24px}
  .pos-divider{display:none}
}
</style>

<!-- Hero -->
<section class="sobre-hero">
  <div class="sobre-hero-left">
    <div class="sobre-hero-eyebrow"><?php echo View::e(I18n::t('pagina.sobre')); ?></div>
    <h1>Estruturação<br><em>estratégica</em><br>de obras</h1>
    <p class="sobre-hero-p">Conheça a Lexus — a corretora que conecta clientes a parceiros qualificados com método, agilidade e transparência. Sem intermediação financeira. Sem complicações.</p>
    <div class="sobre-hero-actions">
      <a href="/como-funciona" class="btn-blk">Como Funciona →</a>
      <a href="/contato" class="btn-wht">Entrar em Contato</a>
    </div>
  </div>
  
  <div class="sobre-hero-right">
    <div class="sobre-hero-geo">
      <div class="geo-ring"></div>
      <div class="geo-ring"></div>
      <div class="geo-ring"></div>
      <div class="geo-cross"></div>
      <div class="geo-glow"></div>
    </div>
    <div class="sobre-hero-quote">
      <span class="quote-mark">"</span>
      <p class="quote-text">Nossa missão é tornar o processo de contratação de obras mais simples, rápido e eficiente — conectando quem precisa a quem executa, com método e confiança.</p>
      <span class="quote-attr">— Lexus Corretora · Manifesto</span>
    </div>
  </div>
</section>

<!-- Stats Band -->
<div class="stats-band">
  <div class="stat-cell reveal">
    <div class="stat-num">300+</div>
    <div class="stat-label">Parceiros na rede</div>
  </div>
  <div class="stat-cell reveal" style="transition-delay:.08s">
    <div class="stat-num">98%</div>
    <div class="stat-label">Taxa de satisfação</div>
  </div>
  <div class="stat-cell reveal" style="transition-delay:.16s">
    <div class="stat-num">0</div>
    <div class="stat-label">Intermediação financeira</div>
  </div>
  <div class="stat-cell reveal" style="transition-delay:.24s">
    <div class="stat-num">6</div>
    <div class="stat-label">Etapas estruturadas</div>
  </div>
</div>

<!-- Quem Somos -->
<section class="quem-section">
  <div class="quem-left">
    <div class="section-eyebrow reveal">Identidade</div>
    <h2 class="quem-h2 reveal" style="transition-delay:.1s">Quem <em>somos</em></h2>
    <div class="quem-divider reveal" style="transition-delay:.15s"></div>
    <p class="quem-p reveal" style="transition-delay:.2s">A Lexus é uma plataforma de estruturação, conexão e repasse de oportunidades de obras e reformas. Atuamos como uma corretora estratégica entre clientes finais e empresas ou profissionais executores.</p>
    <p class="quem-p reveal" style="transition-delay:.25s">Não executamos obras. Nosso papel é captar a demanda, estruturar a necessidade, organizar o escopo, distribuir para parceiros qualificados, coletar propostas, apoiar a seleção e formalizar o vínculo — com total transparência.</p>
  </div>
  
  <div class="quem-right">
    <div class="quem-card reveal">
      <div class="quem-card-tag">O que fazemos</div>
      <div class="quem-card-title">Estruturação e<br><em>Conexão</em></div>
      <p class="quem-card-desc">Organizamos a necessidade do cliente e a conectamos com os parceiros mais adequados — com critério, precisão e método.</p>
      <div class="quem-card-deco">01</div>
    </div>
    <div class="quem-card reveal" style="transition-delay:.12s">
      <div class="quem-card-tag">O que não fazemos</div>
      <div class="quem-card-title">Execução ou<br><em>Intermediação</em></div>
      <p class="quem-card-desc">Não executamos obras e não intermediamos financeiramente. O contrato é direto entre cliente e parceiro. Transparência total.</p>
      <div class="quem-card-deco">02</div>
    </div>
  </div>
</section>

<!-- Missão -->
<section class="missao-section">
  <div class="missao-eyebrow reveal">Nossa Missão</div>
  <h2 class="missao-h2 reveal" style="transition-delay:.1s">Tornar o processo de contratação de obras mais <em>simples, rápido</em> e eficiente — conectando quem precisa a quem executa, com método e confiança.</h2>
  <div class="missao-rule reveal" style="transition-delay:.2s"></div>
</section>

<!-- Valores -->
<section class="valores-section">
  <div class="valores-header reveal">
    <div>
      <div class="section-eyebrow" style="margin-bottom:16px">Princípios</div>
      <h2 class="valores-h2">Nossos <em>valores</em></h2>
    </div>
    <p class="valores-sub">Os princípios que guiam cada decisão, cada processo e cada relação que construímos com clientes e parceiros.</p>
  </div>
  
  <div class="valores-grid">
    <div class="valor-card reveal">
      <div class="valor-icon">🛡️</div>
      <div class="valor-title">Transparência total</div>
      <p class="valor-desc">Cada etapa do processo é documentada e comunicada. Nenhuma decisão ocorre sem o cliente estar plenamente informado e no controle.</p>
      <div class="valor-num">01</div>
    </div>
    <div class="valor-card reveal" style="transition-delay:.1s">
      <div class="valor-icon">📊</div>
      <div class="valor-title">Método e rigor</div>
      <p class="valor-desc">Estruturamos cada demanda com critério técnico. O match entre cliente e parceiro segue um processo padronizado que garante qualidade e consistência.</p>
      <div class="valor-num">02</div>
    </div>
    <div class="valor-card reveal" style="transition-delay:.2s">
      <div class="valor-icon">👥</div>
      <div class="valor-title">Autonomia do cliente</div>
      <p class="valor-desc">Você recebe todas as propostas, toda a análise e toma a decisão final. Nunca pressionamos — nosso papel é facilitar, não decidir por você.</p>
      <div class="valor-num">03</div>
    </div>
    <div class="valor-card reveal" style="transition-delay:.05s">
      <div class="valor-icon">⭐</div>
      <div class="valor-title">Qualidade sobre quantidade</div>
      <p class="valor-desc">Parceiros passam por processo seletivo rigoroso. Preferimos uma rede menor e altamente qualificada a uma grande e imprecisa.</p>
      <div class="valor-num">04</div>
    </div>
    <div class="valor-card reveal" style="transition-delay:.15s">
      <div class="valor-icon">💡</div>
      <div class="valor-title">Inovação contínua</div>
      <p class="valor-desc">Aprimoramos constantemente nossa plataforma, processos e rede de parceiros. Tecnologia a serviço de obras mais inteligentes e bem-sucedidas.</p>
      <div class="valor-num">05</div>
    </div>
    <div class="valor-card reveal" style="transition-delay:.25s">
      <div class="valor-icon">❤️</div>
      <div class="valor-title">Relações de longo prazo</div>
      <p class="valor-desc">Construímos conexões duradouras com clientes e parceiros. Confiança não se impõe — se conquista, entrega a entrega, obra a obra.</p>
      <div class="valor-num">06</div>
    </div>
  </div>
</section>

<!-- Posicionamento -->
<div class="pos-band">
  <div class="pos-block reveal">
    <div class="pos-tag">O que somos</div>
    <div class="pos-title">Uma corretora<br><em>estratégica</em></div>
    <p class="pos-text">Atuamos como ponte qualificada entre clientes que precisam de obras e empresas que executam com excelência. Nosso valor está na inteligência da conexão.</p>
  </div>
  <div class="pos-divider"></div>
  <div class="pos-block reveal" style="transition-delay:.12s">
    <div class="pos-tag">O que não somos</div>
    <div class="pos-title">Não somos<br><em>executores</em></div>
    <p class="pos-text">Não concorremos com nossos parceiros. Não tocamos no dinheiro do cliente. Não fazemos obras. Somos especialistas em estruturar, conectar e garantir o processo.</p>
  </div>
</div>

<!-- CTA -->
<section class="cta">
  <span class="lbl reveal"><?php echo View::e(I18n::t('cta.label')); ?></span>
  <h2 class="disp reveal d1">Pronto para tomar <em>decisões melhores</em> na sua obra?</h2>
  <p class="ctasub reveal d2">Abra uma demanda agora e conecte-se com parceiros qualificados. Sem custo inicial, sem surpresas, com total transparência.</p>
  <div class="ctabts reveal d2">
    <a href="/contato" class="btn-cta">Entrar em Contato →</a>
    <a href="/como-funciona" class="btn-out">Como Funciona</a>
  </div>
</section>

<script>
// Reveal on scroll
document.addEventListener('DOMContentLoaded', function() {
  const revealEls = document.querySelectorAll('.reveal');
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if(e.isIntersecting){
        e.target.classList.add('in');
        obs.unobserve(e.target);
      }
    });
  }, {threshold: .12});
  
  revealEls.forEach(el => obs.observe(el));
});
</script>
