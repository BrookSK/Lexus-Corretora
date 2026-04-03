<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
?>

<style>
/* Vetriks - Premium Layout */
.vetriks-hero{min-height:100vh;display:flex;align-items:flex-end;padding:64px 0 0;position:relative;overflow:hidden;background:rgba(12,12,10,.98)}
.vetriks-hero-bg-glow{position:absolute;inset:0;background:radial-gradient(ellipse 60% 70% at 65% 40%,rgba(184,148,90,.09) 0%,transparent 65%),radial-gradient(ellipse 40% 40% at 20% 80%,rgba(184,148,90,.04) 0%,transparent 60%)}
.vetriks-hero-seal{position:absolute;right:88px;top:50%;transform:translateY(-50%);z-index:2;animation:floatSeal 6s ease-in-out infinite}
@keyframes floatSeal{0%,100%{transform:translateY(-50%) translateY(0)}50%{transform:translateY(-50%) translateY(-14px)}}
.seal-display{width:280px;height:280px;position:relative;display:flex;align-items:center;justify-content:center}
.seal-ring{position:absolute;border-radius:50%;border:1px solid rgba(184,148,90,.2);animation:rotateSeal 30s linear infinite}
.seal-ring:nth-child(1){width:280px;height:280px}
.seal-ring:nth-child(2){width:220px;height:220px;border-style:dashed;border-color:rgba(184,148,90,.15);animation-duration:25s;animation-direction:reverse}
.seal-ring:nth-child(3){width:160px;height:160px;border-color:rgba(184,148,90,.3)}
@keyframes rotateSeal{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
.seal-center{position:relative;z-index:2;width:120px;height:120px;background:rgba(12,12,10,.95);border:1px solid rgba(184,148,90,.3);border-radius:50%;display:flex;flex-direction:column;align-items:center;justify-content:center}
.seal-center-v{font-family:'Cormorant Garamond',serif;font-size:2.4rem;font-weight:300;color:var(--gold);line-height:1}
.seal-center-sub{font-size:.5rem;letter-spacing:.18em;text-transform:uppercase;color:rgba(245,242,237,.5);margin-top:4px;line-height:1.4;text-align:center}
.vetriks-hero-content{position:relative;z-index:3;padding:80px 64px 90px;max-width:700px}
.vetriks-hero-eyebrow{display:flex;align-items:center;gap:10px;font-size:.65rem;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);margin-bottom:36px;opacity:0;animation:fadeUp .7s ease both}
.vetriks-hero-eyebrow::before{content:'';display:block;width:28px;height:1px;background:var(--gold)}
.vetriks-hero h1{font-family:'Cormorant Garamond',serif;font-size:clamp(4rem,8vw,8.5rem);font-weight:300;line-height:.95;color:var(--white);margin-bottom:32px;letter-spacing:-.02em;opacity:0;animation:fadeUp .7s .1s ease both}
.vetriks-hero h1 em{font-style:italic;color:var(--gold);display:block}
.vetriks-hero-p{font-size:1rem;color:rgba(245,242,237,.7);max-width:460px;line-height:1.85;margin-bottom:52px;opacity:0;animation:fadeUp .7s .22s ease both}
.vetriks-hero-metrics{display:flex;gap:0;border-top:1px solid rgba(184,148,90,.15);padding-top:0;opacity:0;animation:fadeUp .7s .34s ease both}
.vetriks-metric{padding:28px 40px 0 0;border-right:1px solid rgba(184,148,90,.1);margin-right:40px}
.vetriks-metric:last-child{border-right:none;margin-right:0}
.metric-val{font-family:'Cormorant Garamond',serif;font-size:2.6rem;font-weight:300;color:var(--gold);line-height:1;margin-bottom:5px}
.metric-label{font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;color:rgba(245,242,237,.5)}
.scroll-hint{position:absolute;bottom:0;left:64px;z-index:3;display:flex;flex-direction:column;align-items:center}
.scroll-hint-line{width:1px;height:60px;background:linear-gradient(to bottom,var(--gold) 0%,transparent 100%);animation:pulse 2.2s ease infinite}
@keyframes pulse{0%,100%{opacity:.3;transform:scaleY(.6);transform-origin:top}50%{opacity:1;transform:scaleY(1);transform-origin:top}}

.what-section{background:var(--white);position:relative;overflow:hidden}
.what-inner{display:grid;grid-template-columns:1fr 1fr;min-height:70vh}
.what-left{padding:100px 64px;display:flex;flex-direction:column;justify-content:center;border-right:1px solid rgba(14,12,9,.08)}
.what-h2{font-family:'Cormorant Garamond',serif;font-size:clamp(2.8rem,4vw,4.2rem);font-weight:300;line-height:1.08;color:var(--black);margin-bottom:28px}
.what-h2 em{font-style:italic;color:var(--gold)}
.what-rule{width:48px;height:1px;background:var(--gold);margin-bottom:28px}
.what-p{font-size:.93rem;color:#4a4338;line-height:1.9;max-width:380px;margin-bottom:16px}
.what-right{background:var(--black);display:flex;align-items:center;justify-content:center;padding:72px 60px;position:relative;overflow:hidden}
.what-right::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 70% at 50% 50%,rgba(184,148,90,.07) 0%,transparent 70%)}
.what-right .seal-display{width:340px;height:340px}
.what-right .seal-ring:nth-child(1){width:340px;height:340px}
.what-right .seal-ring:nth-child(2){width:264px;height:264px}
.what-right .seal-ring:nth-child(3){width:190px;height:190px;border-color:rgba(184,148,90,.35)}
.what-right .seal-center{width:148px;height:148px;border-color:rgba(184,148,90,.4)}
.what-right .seal-center-v{font-size:2.6rem}

.criteria-section{background:rgba(26,23,16,.98);padding:100px 64px;position:relative;overflow:hidden}
.criteria-section::before{content:'';position:absolute;width:600px;height:600px;top:-100px;right:-80px;background:radial-gradient(circle,rgba(184,148,90,.05) 0%,transparent 65%);pointer-events:none}
.criteria-header{display:grid;grid-template-columns:1fr 1fr;gap:64px;margin-bottom:72px;align-items:end}
.criteria-h2{font-family:'Cormorant Garamond',serif;font-size:clamp(2.6rem,4vw,4rem);font-weight:300;line-height:1.08;color:var(--white)}
.criteria-h2 em{font-style:italic;color:var(--gold)}
.criteria-intro{font-size:.9rem;color:rgba(245,242,237,.5);line-height:1.85;max-width:380px}
.criteria-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1px;background:rgba(184,148,90,.08);border:1px solid rgba(184,148,90,.08)}
.criteria-card{background:rgba(26,23,16,.98);padding:48px 44px;position:relative;overflow:hidden;transition:background .3s}
.criteria-card:hover{background:#1c1913}
.criteria-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(to right,var(--gold),transparent);transform:scaleX(0);transform-origin:left;transition:transform .5s cubic-bezier(.4,0,.2,1)}
.criteria-card:hover::before{transform:scaleX(1)}
.cc-num{font-family:'Cormorant Garamond',serif;font-size:.85rem;font-weight:400;color:var(--gold);letter-spacing:.12em;margin-bottom:20px;display:block}
.cc-icon{width:44px;height:44px;border:1px solid rgba(184,148,90,.22);display:flex;align-items:center;justify-content:center;margin-bottom:22px;transition:border-color .3s;font-size:1.3rem}
.criteria-card:hover .cc-icon{border-color:var(--gold)}
.cc-title{font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:400;color:var(--white);margin-bottom:12px;line-height:1.2}
.cc-desc{font-size:.82rem;color:rgba(245,242,237,.5);line-height:1.8}
.cc-deco{position:absolute;right:24px;bottom:16px;font-family:'Cormorant Garamond',serif;font-size:5rem;font-weight:600;color:transparent;-webkit-text-stroke:1px rgba(184,148,90,.05);pointer-events:none;user-select:none;line-height:1}

.importance-section{background:rgba(12,12,10,.98);padding:100px 64px;position:relative;overflow:hidden}
.imp-header{margin-bottom:72px}
.imp-h2{font-family:'Cormorant Garamond',serif;font-size:clamp(2.6rem,4vw,4rem);font-weight:300;line-height:1.1;color:var(--white)}
.imp-h2 em{font-style:italic;color:var(--gold)}
.imp-tabs{display:flex;gap:0;border-bottom:1px solid rgba(184,148,90,.15);margin-bottom:0}
.imp-tab{font-family:'DM Sans',sans-serif;font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;color:rgba(245,242,237,.5);background:none;border:none;cursor:pointer;padding:16px 28px;position:relative;transition:color .2s;border-bottom:2px solid transparent;margin-bottom:-1px}
.imp-tab:hover{color:rgba(245,242,237,.8)}
.imp-tab.active{color:var(--gold);border-bottom-color:var(--gold)}
.imp-panel{display:none;padding:52px 0 0;grid-template-columns:1fr 1fr;gap:80px;align-items:center}
.imp-panel.active{display:grid}
.imp-panel-label{font-size:.65rem;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);margin-bottom:20px;display:flex;align-items:center;gap:8px}
.imp-panel-label::before{content:'';display:block;width:14px;height:1px;background:var(--gold)}
.imp-panel-title{font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,3vw,3rem);font-weight:300;line-height:1.15;color:var(--white);margin-bottom:20px}
.imp-panel-title em{font-style:italic;color:var(--gold)}
.imp-panel-desc{font-size:.9rem;color:rgba(245,242,237,.5);line-height:1.9;max-width:380px}
.imp-panel-visual{display:flex;flex-direction:column;gap:12px}
.imp-benefit{display:flex;align-items:flex-start;gap:16px;padding:20px 24px;border:1px solid rgba(184,148,90,.1);background:rgba(184,148,90,.03);transition:border-color .25s,background .25s}
.imp-benefit:hover{border-color:rgba(184,148,90,.3);background:rgba(184,148,90,.06)}
.imp-benefit-icon{width:32px;height:32px;flex-shrink:0;border:1px solid rgba(184,148,90,.25);display:flex;align-items:center;justify-content:center;margin-top:1px;font-size:1rem}
.imp-benefit-title{font-size:.78rem;font-weight:500;color:var(--white);margin-bottom:3px;letter-spacing:.02em}
.imp-benefit-desc{font-size:.75rem;color:rgba(245,242,237,.5);line-height:1.65}

.process-section{background:var(--white);padding:100px 64px}
.process-header{margin-bottom:64px}
.process-h2{font-family:'Cormorant Garamond',serif;font-size:clamp(2.6rem,4vw,4rem);font-weight:300;line-height:1.1;color:var(--black)}
.process-h2 em{font-style:italic;color:var(--gold)}
.process-sub{font-size:.88rem;color:#5a5145;line-height:1.8;max-width:400px;margin-top:16px}
.process-steps{display:grid;grid-template-columns:repeat(4,1fr);gap:0;border:1px solid rgba(14,12,9,.1);position:relative}
.process-steps::before{content:'';position:absolute;top:52px;left:calc(12.5%);right:calc(12.5%);height:1px;background:rgba(184,148,90,.3);z-index:0}
.process-step{padding:44px 32px;border-right:1px solid rgba(14,12,9,.08);background:var(--white);position:relative;z-index:1;transition:background .25s}
.process-step:last-child{border-right:none}
.process-step:hover{background:#ede8df}
.ps-circle{width:40px;height:40px;border-radius:50%;border:1px solid var(--gold);background:var(--white);display:flex;align-items:center;justify-content:center;margin-bottom:28px;position:relative;z-index:1;transition:background .3s,border-color .3s}
.process-step:hover .ps-circle{background:var(--gold)}
.ps-circle-num{font-family:'Cormorant Garamond',serif;font-size:1rem;font-weight:400;color:var(--gold);transition:color .3s}
.process-step:hover .ps-circle-num{color:var(--black)}
.ps-title{font-family:'Cormorant Garamond',serif;font-size:1.3rem;font-weight:400;color:var(--black);margin-bottom:10px}
.ps-desc{font-size:.8rem;color:#5a5145;line-height:1.75}

@keyframes fadeUp{from{opacity:0;transform:translateY(26px)}to{opacity:1;transform:translateY(0)}}

@media(max-width:768px){
  .vetriks-hero-seal{display:none}
  .vetriks-hero-content{padding:90px 24px 60px}
  .vetriks-hero-metrics{flex-direction:column;gap:20px}
  .vetriks-metric{border-right:none;border-bottom:1px solid rgba(184,148,90,.1);padding:0 0 20px;margin:0}
  .vetriks-metric:last-child{border-bottom:none}
  .scroll-hint{left:24px}
  .what-inner{grid-template-columns:1fr}
  .what-left{padding:60px 24px;border-right:none;border-bottom:1px solid rgba(14,12,9,.08)}
  .what-right{min-height:300px;padding:60px 24px}
  .criteria-section{padding:80px 24px}
  .criteria-header{grid-template-columns:1fr}
  .criteria-grid{grid-template-columns:1fr}
  .importance-section{padding:80px 24px}
  .imp-panel{grid-template-columns:1fr}
  .imp-panel-visual{display:none}
  .process-section{padding:80px 24px}
  .process-steps{grid-template-columns:1fr}
  .process-steps::before{display:none}
}
</style>

<!-- Hero -->
<section class="vetriks-hero">
  <div class="vetriks-hero-bg-glow"></div>
  
  <!-- Animated Seal -->
  <div class="vetriks-hero-seal">
    <div class="seal-display">
      <div class="seal-ring"></div>
      <div class="seal-ring"></div>
      <div class="seal-ring"></div>
      <div class="seal-center">
        <div class="seal-center-v">V</div>
        <div class="seal-center-sub">SELO<br>VETRIKS</div>
      </div>
    </div>
  </div>
  
  <div class="vetriks-hero-content">
    <div class="vetriks-hero-eyebrow"><?php echo View::e(I18n::t('pagina.vetriks')); ?></div>
    <h1>Selo<em>Vetriks</em></h1>
    <p class="vetriks-hero-p">O padrão de qualificação que garante confiança, experiência comprovada e capacidade de execução para cada parceiro da nossa rede.</p>
    <div class="vetriks-hero-metrics">
      <div class="vetriks-metric">
        <div class="metric-val">100%</div>
        <div class="metric-label">Verificados</div>
      </div>
      <div class="vetriks-metric">
        <div class="metric-val">4</div>
        <div class="metric-label">Critérios de avaliação</div>
      </div>
      <div class="vetriks-metric">
        <div class="metric-val">Prioridade</div>
        <div class="metric-label">Na distribuição</div>
      </div>
    </div>
  </div>
  
  <div class="scroll-hint">
    <div class="scroll-hint-line"></div>
  </div>
</section>

<!-- What Is -->
<section class="what-section">
  <div class="what-inner">
    <div class="what-left">
      <div class="section-eyebrow reveal">O Certificado</div>
      <h2 class="what-h2 reveal" style="transition-delay:.1s">O que é o Selo <em>Vetriks</em></h2>
      <div class="what-rule reveal" style="transition-delay:.15s"></div>
      <p class="what-p reveal" style="transition-delay:.2s">O Selo Vetriks é a certificação exclusiva da Lexus que identifica parceiros que passaram por um processo rigoroso de qualificação. Ele certifica experiência comprovada, capacidade de execução e confiabilidade operacional.</p>
      <p class="what-p reveal" style="transition-delay:.25s">Parceiros com Selo Vetriks têm prioridade na distribuição de oportunidades e maior visibilidade na plataforma — sendo os primeiros a receber demandas compatíveis com seu perfil.</p>
    </div>
    
    <div class="what-right">
      <div class="seal-display">
        <div class="seal-ring"></div>
        <div class="seal-ring"></div>
        <div class="seal-ring"></div>
        <div class="seal-center">
          <div class="seal-center-v">V</div>
          <div class="seal-center-sub">SELO<br>VETRIKS</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Criteria -->
<section class="criteria-section">
  <div class="criteria-header reveal">
    <div>
      <div class="section-eyebrow" style="margin-bottom:16px">Exigências</div>
      <h2 class="criteria-h2">Critérios de <em>Qualificação</em></h2>
    </div>
    <p class="criteria-intro">Cada parceiro é avaliado em quatro dimensões fundamentais antes de receber o Selo. O processo é conduzido internamente pela equipe Lexus, sem exceções.</p>
  </div>
  
  <div class="criteria-grid">
    <div class="criteria-card reveal">
      <span class="cc-num">01 —</span>
      <div class="cc-icon">💼</div>
      <div class="cc-title">Experiência comprovada</div>
      <p class="cc-desc">Portfólio verificado com obras executadas, referências reais de clientes anteriores e histórico documentado de projetos similares ao perfil de atuação declarado.</p>
      <div class="cc-deco">01</div>
    </div>
    <div class="criteria-card reveal" style="transition-delay:.1s">
      <span class="cc-num">02 —</span>
      <div class="cc-icon">📊</div>
      <div class="cc-title">Capacidade de execução</div>
      <p class="cc-desc">Avaliação estrutural da equipe, equipamentos, capacidade produtiva e disponibilidade operacional. A empresa deve ter estrutura real para entregar o que promete.</p>
      <div class="cc-deco">02</div>
    </div>
    <div class="criteria-card reveal" style="transition-delay:.05s">
      <span class="cc-num">03 —</span>
      <div class="cc-icon">🛡️</div>
      <div class="cc-title">Confiabilidade e histórico</div>
      <p class="cc-desc">Verificação de referências, análise de cumprimento de prazos em projetos anteriores e avaliação de reclamações ou conflitos registrados. Confiança é inegociável.</p>
      <div class="cc-deco">03</div>
    </div>
    <div class="criteria-card reveal" style="transition-delay:.15s">
      <span class="cc-num">04 —</span>
      <div class="cc-icon">📄</div>
      <div class="cc-title">Documentação validada</div>
      <p class="cc-desc">Regularidade jurídica, fiscal e operacional verificada. CNPJ ativo, certidões em dia, registro em conselhos profissionais quando aplicável e estrutura administrativa adequada.</p>
      <div class="cc-deco">04</div>
    </div>
  </div>
</section>

<!-- Importance - Tabs -->
<section class="importance-section">
  <div class="imp-header reveal">
    <div class="section-eyebrow" style="margin-bottom:16px;color:var(--gold)">Relevância</div>
    <h2 class="imp-h2">Por que <em>importa</em></h2>
  </div>
  
  <div class="imp-tabs">
    <button class="imp-tab active" onclick="switchTab('clientes',this)">Para Clientes</button>
    <button class="imp-tab" onclick="switchTab('parceiros',this)">Para Parceiros</button>
    <button class="imp-tab" onclick="switchTab('lexus',this)">Para a Lexus</button>
  </div>
  
  <div class="imp-panels">
    <!-- Clientes -->
    <div class="imp-panel active" id="tab-clientes">
      <div class="imp-panel-text">
        <div class="imp-panel-label">Perspectiva do Cliente</div>
        <h3 class="imp-panel-title">Segurança na<br><em>escolha</em></h3>
        <p class="imp-panel-desc">Para clientes, o Selo Vetriks significa que cada parceiro apresentado já foi verificado e aprovado. Você não precisa pesquisar, ligar para referências ou arriscar. A curadoria já foi feita com rigor pela equipe Lexus.</p>
      </div>
      <div class="imp-panel-visual">
        <div class="imp-benefit">
          <div class="imp-benefit-icon">✓</div>
          <div class="imp-benefit-text">
            <div class="imp-benefit-title">Parceiros pré-aprovados</div>
            <div class="imp-benefit-desc">Todos os parceiros já passaram por análise antes de chegar até você.</div>
          </div>
        </div>
        <div class="imp-benefit">
          <div class="imp-benefit-icon">✓</div>
          <div class="imp-benefit-text">
            <div class="imp-benefit-title">Risco reduzido</div>
            <div class="imp-benefit-desc">Histórico verificado e referências validadas por nossa equipe.</div>
          </div>
        </div>
        <div class="imp-benefit">
          <div class="imp-benefit-icon">✓</div>
          <div class="imp-benefit-text">
            <div class="imp-benefit-title">Decisão mais informada</div>
            <div class="imp-benefit-desc">Você escolhe entre os melhores, não entre desconhecidos.</div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Parceiros -->
    <div class="imp-panel" id="tab-parceiros">
      <div class="imp-panel-text">
        <div class="imp-panel-label">Perspectiva do Parceiro</div>
        <h3 class="imp-panel-title">Reconhecimento e<br><em>acesso</em></h3>
        <p class="imp-panel-desc">Para parceiros, o Selo significa reconhecimento pelo trabalho sério que já constroem — e acesso privilegiado a oportunidades qualificadas. Com o Selo, sua empresa é vista primeiro e considerada com mais credibilidade.</p>
      </div>
      <div class="imp-panel-visual">
        <div class="imp-benefit">
          <div class="imp-benefit-icon">✓</div>
          <div class="imp-benefit-text">
            <div class="imp-benefit-title">Prioridade na distribuição</div>
            <div class="imp-benefit-desc">Recebe demandas antes dos parceiros sem certificação.</div>
          </div>
        </div>
        <div class="imp-benefit">
          <div class="imp-benefit-icon">✓</div>
          <div class="imp-benefit-text">
            <div class="imp-benefit-title">Maior visibilidade</div>
            <div class="imp-benefit-desc">Destaque na plataforma com badge de qualificação.</div>
          </div>
        </div>
        <div class="imp-benefit">
          <div class="imp-benefit-icon">✓</div>
          <div class="imp-benefit-text">
            <div class="imp-benefit-title">Credibilidade validada</div>
            <div class="imp-benefit-desc">Seu histórico e qualidade são reconhecidos formalmente.</div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Lexus -->
    <div class="imp-panel" id="tab-lexus">
      <div class="imp-panel-text">
        <div class="imp-panel-label">Perspectiva da Lexus</div>
        <h3 class="imp-panel-title">Curadoria real e<br><em>compromisso</em></h3>
        <p class="imp-panel-desc">Para a Lexus, o Selo Vetriks é a expressão concreta do nosso compromisso com a qualidade. Não conectamos qualquer empresa — conectamos as melhores. É a base de confiança que sustenta toda a plataforma.</p>
      </div>
      <div class="imp-panel-visual">
        <div class="imp-benefit">
          <div class="imp-benefit-icon">✓</div>
          <div class="imp-benefit-text">
            <div class="imp-benefit-title">Rede curada</div>
            <div class="imp-benefit-desc">Qualidade sobre quantidade em cada parceiro admitido.</div>
          </div>
        </div>
        <div class="imp-benefit">
          <div class="imp-benefit-icon">✓</div>
          <div class="imp-benefit-text">
            <div class="imp-benefit-title">Confiança sistêmica</div>
            <div class="imp-benefit-desc">O Selo garante a integridade de toda a cadeia de valor.</div>
          </div>
        </div>
        <div class="imp-benefit">
          <div class="imp-benefit-icon">✓</div>
          <div class="imp-benefit-text">
            <div class="imp-benefit-title">Resultado sustentável</div>
            <div class="imp-benefit-desc">Clientes satisfeitos, parceiros reconhecidos, ciclo virtuoso.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Process -->
<section class="process-section">
  <div class="process-header reveal">
    <div class="section-eyebrow" style="color:var(--gold)">Elegibilidade</div>
    <h2 class="process-h2">Como obter o <em>Selo Vetriks</em></h2>
    <p class="process-sub">O processo é conduzido pela equipe Lexus após a solicitação de parceria. Levamos em média 5 dias úteis para concluir a avaliação.</p>
  </div>
  
  <div class="process-steps">
    <div class="process-step reveal">
      <div class="ps-circle"><span class="ps-circle-num">1</span></div>
      <div class="ps-title">Solicite ser parceiro</div>
      <p class="ps-desc">Acesse o formulário de cadastro e submeta as informações da sua empresa, especialidades e portfólio inicial.</p>
    </div>
    <div class="process-step reveal" style="transition-delay:.1s">
      <div class="ps-circle"><span class="ps-circle-num">2</span></div>
      <div class="ps-title">Análise documental</div>
      <p class="ps-desc">Nossa equipe revisa documentação, CNPJ, certidões e regularidade profissional. Tudo verificado internamente.</p>
    </div>
    <div class="process-step reveal" style="transition-delay:.2s">
      <div class="ps-circle"><span class="ps-circle-num">3</span></div>
      <div class="ps-title">Avaliação de portfólio</div>
      <p class="ps-desc">Obras executadas, referências de clientes e capacidade técnica são avaliados por critérios objetivos.</p>
    </div>
    <div class="process-step reveal" style="transition-delay:.3s">
      <div class="ps-circle"><span class="ps-circle-num">4</span></div>
      <div class="ps-title">Aprovação e ativação</div>
      <p class="ps-desc">Aprovado, você recebe o Selo Vetriks e passa a ter acesso às demandas com prioridade e visibilidade máxima.</p>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta">
  <span class="lbl reveal"><?php echo View::e(I18n::t('cta.label')); ?></span>
  <h2 class="disp reveal d1">Quer ser um parceiro <em>Vetriks</em>?</h2>
  <p class="ctasub reveal d2">Junte-se à rede de empresas qualificadas que recebem as melhores oportunidades da plataforma Lexus com prioridade e visibilidade.</p>
  <div class="ctabts reveal d2">
    <a href="/seja-parceiro" class="btn-cta">Solicitar Certificação →</a>
    <a href="/contato" class="btn-out">Entrar em Contato</a>
  </div>
</section>

<script>
// Tab switching
function switchTab(name, btn) {
  document.querySelectorAll('.imp-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.imp-panel').forEach(p => p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('tab-' + name).classList.add('active');
}

// Scroll reveal
document.addEventListener('DOMContentLoaded', function() {
  const els = document.querySelectorAll('.reveal');
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if(e.isIntersecting) {
        e.target.classList.add('in');
        obs.unobserve(e.target);
      }
    });
  }, {threshold: .1});
  
  els.forEach(el => obs.observe(el));
});
</script>
