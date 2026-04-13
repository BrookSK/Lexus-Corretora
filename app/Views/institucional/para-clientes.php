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
        <div class="step-eyebrow">Cadastro de Cliente</div>
      </div>
    </div>
    
    <form method="POST" action="/cliente/criar-conta" id="clienteForm">
      <?php echo Csrf::campo(); ?>
      <div class="slide">
        <div class="slide-title">Crie sua conta<br>gratuitamente.</div>
        <div class="form-row">
          <div class="form-group">
            <label>Nome completo <span class="req">*</span></label>
            <input type="text" name="name" required placeholder="Seu nome completo"/>
          </div>
          <div class="form-group">
            <label>E-mail <span class="req">*</span></label>
            <input type="email" name="email" required placeholder="seu@email.com"/>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Telefone / WhatsApp <span class="req">*</span></label>
            <input type="tel" name="phone" required placeholder="(11) 00000-0000"/>
          </div>
          <div class="form-group">
            <label>Empresa (opcional)</label>
            <input type="text" name="company" placeholder="Nome da empresa"/>
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
          Já tem conta? <a href="/cliente/login" style="color:#C9A96E;">Faça login</a>
        </p>
      </div>
    </form>
  </div>
  
  <!-- RIGHT: HEADLINE -->
  <div class="hero-headline-col">
    <div class="hero-headline-inner">
      <h1 class="hero-headline-title">Encontre os melhores profissionais para sua obra</h1>
      <p class="hero-headline-desc">Conectamos você com parceiros qualificados e gerenciamos todo o processo, do orçamento à entrega.</p>
      <ul class="hero-headline-list">
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          Profissionais verificados
        </li>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          Orçamentos competitivos
        </li>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          Acompanhamento completo
        </li>
      </ul>
    </div>
  </div>
</section>

<!-- COMO FUNCIONA -->
<section class="landing-how">
  <div class="landing-section">
    <h2 class="section-title">Como funciona</h2>
    <p class="section-subtitle">Simples, rápido e seguro</p>
    <div class="how-cards">
      <div class="how-card">
        <div class="how-card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
          </svg>
        </div>
        <h3 class="how-card-title">1. Crie sua conta</h3>
        <p class="how-card-desc">Cadastro rápido e gratuito em menos de 1 minuto.</p>
      </div>
      <div class="how-card">
        <div class="how-card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
          </svg>
        </div>
        <h3 class="how-card-title">2. Descreva seu projeto</h3>
        <p class="how-card-desc">Crie uma demanda com os detalhes da sua obra.</p>
      </div>
      <div class="how-card">
        <div class="how-card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
        </div>
        <h3 class="how-card-title">3. Receba propostas</h3>
        <p class="how-card-desc">Compare orçamentos e escolha o melhor parceiro.</p>
      </div>
    </div>
  </div>
</section>

<!-- BENEFÍCIOS -->
<section class="landing-benefits">
  <div class="landing-section">
    <h2 class="section-title">Por que escolher a Lexus?</h2>
    <div class="benefits-grid">
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
          </svg>
        </div>
        <h3 class="benefit-title">Segurança</h3>
        <p class="benefit-desc">Todos os parceiros são verificados e avaliados.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12 6 12 12 16 14"/>
          </svg>
        </div>
        <h3 class="benefit-title">Agilidade</h3>
        <p class="benefit-desc">Receba propostas em até 48 horas.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="1" x2="12" y2="23"/>
            <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
          </svg>
        </div>
        <h3 class="benefit-title">Economia</h3>
        <p class="benefit-desc">Compare preços e escolha a melhor oferta.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>
          </svg>
        </div>
        <h3 class="benefit-title">Suporte</h3>
        <p class="benefit-desc">Acompanhamento em todas as etapas do projeto.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="landing-cta">
  <div class="landing-section">
    <h2 class="cta-title">Pronto para começar seu projeto?</h2>
    <p class="cta-desc">Cadastre-se agora e receba propostas de profissionais qualificados.</p>
    <div class="cta-buttons">
      <a href="#hero" class="cta-btn cta-btn-primary">Criar Conta</a>
      <a href="/como-funciona" class="cta-btn cta-btn-secondary">Saiba Mais</a>
    </div>
  </div>
</section>

<script>
(function(){
  const form = document.getElementById('clienteForm');
  
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
      document.querySelector('.landing-hero').scrollIntoView({ behavior: 'smooth' });
    });
  });
})();
</script>