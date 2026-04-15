<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<link rel="stylesheet" href="/assets/css/landing-form.css?v=<?php echo time(); ?>"/>

<!-- HERO -->
<section class="landing-hero" id="landing-hero">
  <!-- LEFT: FORM -->
  <div class="hero-form-col">
    <div class="step-header">
      <div class="step-meta">
        <div class="step-eyebrow">Cadastro de Parceiro</div>
      </div>
    </div>
    
    <form method="POST" action="/parceiro/criar-conta" id="parceiroForm">
      <?php echo Csrf::campo(); ?>
      <div class="slide">
        <div class="slide-title">Faça parte da<br>nossa rede.</div>
        <div class="form-row">
          <div class="form-group">
            <label>Nome completo / Razão Social <span class="req">*</span></label>
            <input type="text" name="name" required placeholder="Seu nome ou empresa"/>
          </div>
          <div class="form-group">
            <label>E-mail <span class="req">*</span></label>
            <input type="email" name="email" required placeholder="seu@email.com"/>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Telefone / WhatsApp <span class="req">*</span></label>
            <input type="tel" name="whatsapp" required placeholder="(11) 00000-0000"/>
          </div>
          <div class="form-group">
            <label>CNPJ <span class="req">*</span></label>
            <input type="text" name="document" class="cnpj-input" required placeholder="00.000.000/0000-00"/>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Senha de acesso <span class="req">*</span></label>
            <input type="password" name="password" required minlength="8" placeholder="Mín. 8 caracteres"/>
          </div>
          <div class="form-group">
            <label>Confirmar senha <span class="req">*</span></label>
            <input type="password" name="password_confirm" required minlength="8" placeholder="Repita a senha"/>
          </div>
        </div>
        <button type="submit" class="submit-btn">Criar Conta</button>
        <p style="text-align:center;margin-top:20px;color:#999;">
          Já tem conta? <a href="/parceiro/login" style="color:#C9A96E;">Faça login</a>
        </p>
      </div>
    </form>
  </div>
  
  <!-- RIGHT: HEADLINE -->
  <div class="hero-headline-col">
    <div class="hero-headline-inner">
      <h1 class="hero-headline-title">Faça parte da nossa rede de parceiros</h1>
      <p class="hero-headline-desc">Conecte-se com clientes qualificados e expanda seus negócios com a Lexus.</p>
      <ul class="hero-headline-list">
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          Oportunidades qualificadas
        </li>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          Gestão simplificada
        </li>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          Pagamentos garantidos
        </li>
      </ul>
    </div>
  </div>
</section>

<!-- COMO FUNCIONA -->
<section class="landing-how">
  <div class="landing-section">
    <h2 class="section-title">Como funciona</h2>
    <p class="section-subtitle">Simples, rápido e lucrativo</p>
    <div class="how-cards">
      <div class="how-card">
        <div class="how-card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
            <circle cx="8.5" cy="7" r="4"/>
            <polyline points="17 11 19 13 23 9"/>
          </svg>
        </div>
        <h3 class="how-card-title">1. Cadastre-se</h3>
        <p class="how-card-desc">Crie sua conta e complete seu perfil profissional.</p>
      </div>
      <div class="how-card">
        <div class="how-card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 6v6l4 2"/>
          </svg>
        </div>
        <h3 class="how-card-title">2. Receba oportunidades</h3>
        <p class="how-card-desc">Acesse demandas compatíveis com seu perfil.</p>
      </div>
      <div class="how-card">
        <div class="how-card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="1" x2="12" y2="23"/>
            <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
          </svg>
        </div>
        <h3 class="how-card-title">3. Envie propostas e fature</h3>
        <p class="how-card-desc">Elabore orçamentos e receba com segurança.</p>
      </div>
    </div>
  </div>
</section>

<!-- BENEFÍCIOS -->
<section class="landing-benefits">
  <div class="landing-section">
    <h2 class="section-title">Vantagens de ser parceiro Lexus</h2>
    <div class="benefits-grid">
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
        </div>
        <h3 class="benefit-title">Leads Qualificados</h3>
        <p class="benefit-desc">Receba apenas oportunidades compatíveis com seu perfil.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
        </div>
        <h3 class="benefit-title">Gestão Facilitada</h3>
        <p class="benefit-desc">Plataforma completa para gerenciar propostas e projetos.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
          </svg>
        </div>
        <h3 class="benefit-title">Pagamento Seguro</h3>
        <p class="benefit-desc">Receba com garantia através da nossa plataforma.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
          </svg>
        </div>
        <h3 class="benefit-title">Cresça seu Negócio</h3>
        <p class="benefit-desc">Expanda sua carteira de clientes e aumente seu faturamento.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="landing-cta">
  <div class="landing-section">
    <h2 class="cta-title">Pronto para crescer com a Lexus?</h2>
    <p class="cta-desc">Cadastre-se agora e comece a receber oportunidades de negócio.</p>
    <div class="cta-buttons">
      <a href="#hero" class="cta-btn cta-btn-primary">Cadastrar Agora</a>
      <a href="/como-funciona" class="cta-btn cta-btn-secondary">Saiba Mais</a>
    </div>
  </div>
</section>

<script>
(function(){
  const form = document.getElementById('parceiroForm');
  
  form.addEventListener('submit', (e) => {
    const pwd = form.querySelector('[name="password"]');
    const conf = form.querySelector('[name="password_confirm"]');
    
    if (pwd.value !== conf.value) {
      e.preventDefault();
      alert('As senhas não coincidem.');
      conf.focus();
      return false;
    }
  });
  
  // Scroll suave para #hero
  document.querySelectorAll('a[href="#hero"]').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      document.querySelector('.hero').scrollIntoView({ behavior: 'smooth' });
    });
  });
})();
</script>

<script src="/assets/js/cnpj.js"></script>
