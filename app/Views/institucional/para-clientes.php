<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

$CATEGORIAS_NICHO = [
    'Construção Civil', 'Reforma Residencial', 'Reforma Comercial',
    'Arquitetura', 'Engenharia', 'Elétrica', 'Hidráulica',
    'Pintura', 'Marcenaria', 'Serralheria', 'Vidraçaria',
    'Paisagismo', 'Decoração', 'Design de Interiores'
];
?>

<!-- HERO -->
<section class="hero" id="landing-hero">
<script>
// Debug imediato
console.log('=== LANDING PAGE DEBUG ===');
const hero = document.getElementById('landing-hero');
console.log('Hero element:', hero);
if (hero) {
  console.log('Hero computed styles BEFORE:', {
    display: window.getComputedStyle(hero).display,
    gridTemplateColumns: window.getComputedStyle(hero).gridTemplateColumns,
    flexDirection: window.getComputedStyle(hero).flexDirection,
    minHeight: window.getComputedStyle(hero).minHeight,
    padding: window.getComputedStyle(hero).padding,
    position: window.getComputedStyle(hero).position
  });
  
  // Força estilos
  hero.style.cssText = 'display: grid !important; grid-template-columns: 1fr 1fr !important; min-height: calc(100vh - 64px) !important; width: 100% !important; background: transparent !important; padding: 0 !important; position: static !important; margin: 0 !important;';
  
  console.log('Hero computed styles AFTER:', {
    display: window.getComputedStyle(hero).display,
    gridTemplateColumns: window.getComputedStyle(hero).gridTemplateColumns,
    flexDirection: window.getComputedStyle(hero).flexDirection,
    minHeight: window.getComputedStyle(hero).minHeight,
    padding: window.getComputedStyle(hero).padding,
    position: window.getComputedStyle(hero).position
  });
  
  // Verifica todas as classes CSS aplicadas
  const allRules = [];
  for (let sheet of document.styleSheets) {
    try {
      for (let rule of sheet.cssRules || sheet.rules) {
        if (rule.selectorText && rule.selectorText.includes('hero')) {
          allRules.push({
            selector: rule.selectorText,
            display: rule.style.display,
            gridTemplateColumns: rule.style.gridTemplateColumns,
            flexDirection: rule.style.flexDirection
          });
        }
      }
    } catch(e) {
      console.log('Cannot read stylesheet:', sheet.href);
    }
  }
  console.log('All CSS rules with "hero":', allRules);
}

// Debug das colunas
const formCol = document.querySelector('.hero-form-col');
const headlineCol = document.querySelector('.hero-headline-col');
console.log('Form column:', formCol);
console.log('Headline column:', headlineCol);

if (formCol) {
  console.log('Form col computed:', {
    display: window.getComputedStyle(formCol).display,
    background: window.getComputedStyle(formCol).background
  });
}

if (headlineCol) {
  console.log('Headline col computed:', {
    display: window.getComputedStyle(headlineCol).display,
    background: window.getComputedStyle(headlineCol).background
  });
}
</script>
  <!-- LEFT: MULTI-STEP FORM -->
  <div class="hero-form-col">
    <!-- top progress -->
    <div class="progress-bar-wrap">
      <div class="progress-bar-fill" id="progressFill" style="width:12.5%"></div>
    </div>
    
    <!-- fixed header -->
    <div class="step-header">
      <div class="step-meta">
        <div class="step-eyebrow" id="stepEyebrow">Dados Pessoais</div>
        <div class="step-counter">Etapa <span id="stepNum">1</span> de 8</div>
      </div>
      <div class="step-dots" id="stepDots"></div>
    </div>
    
    <!-- slides -->
    <form method="POST" action="/abrir-demanda" enctype="multipart/form-data" id="demandaForm">
      <?php echo Csrf::campo(); ?>
      <div class="slider-viewport">
        <div class="slides-track" id="slidesTrack">

          <!-- 1: Dados Pessoais -->
          <div class="slide">
            <div class="slide-title">Conte-nos<br>quem você é.</div>
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
          </div>
          
          <!-- 2: Informações Básicas -->
          <div class="slide">
            <div class="slide-title">Sobre o seu<br>projeto.</div>
            <div class="form-group">
              <label>Título da demanda <span class="req">*</span></label>
              <input type="text" name="title" required placeholder="Ex: Reforma residencial completa"/>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Tipo de imóvel <span class="req">*</span></label>
                <select name="property_type" required>
                  <option value="">— Selecione —</option>
                  <option value="casa">Casa</option>
                  <option value="apartamento">Apartamento</option>
                  <option value="comercial">Comercial</option>
                  <option value="industrial">Industrial</option>
                  <option value="terreno">Terreno</option>
                </select>
              </div>
              <div class="form-group">
                <label>Tipo de obra <span class="req">*</span></label>
                <select name="work_type" required>
                  <option value="">— Selecione —</option>
                  <option value="reforma">Reforma</option>
                  <option value="construcao">Construção</option>
                  <option value="manutencao">Manutenção</option>
                  <option value="ampliacao">Ampliação</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <?php
              $estadoSelecionado = '';
              $cidadeSelecionada = '';
              $obrigatorio = true;
              include __DIR__ . '/../_partials/campos-estado-cidade.php';
              ?>
            </div>
            <div class="form-group">
              <label>Endereço / Bairro <span class="req">*</span></label>
              <input type="text" name="address" required placeholder="Rua, número, bairro"/>
            </div>
          </div>
          
          <!-- 3: Tipo de Serviço -->
          <div class="slide">
            <div class="slide-title">Que serviços<br>você precisa?</div>
            <div class="form-group">
              <label>Selecione os serviços <span class="req">*</span> — pode escolher múltiplos</label>
              <div class="check-grid" id="serviceGrid">
                <div class="check-item" data-value="eletrica">
                  <div class="check-box"><svg viewBox="0 0 12 12"><polyline points="1,6 5,10 11,2"/></svg></div>
                  <span class="check-label">Elétrica</span>
                </div>
                <div class="check-item" data-value="hidraulica">
                  <div class="check-box"><svg viewBox="0 0 12 12"><polyline points="1,6 5,10 11,2"/></svg></div>
                  <span class="check-label">Hidráulica</span>
                </div>
                <div class="check-item" data-value="pintura">
                  <div class="check-box"><svg viewBox="0 0 12 12"><polyline points="1,6 5,10 11,2"/></svg></div>
                  <span class="check-label">Pintura</span>
                </div>
                <div class="check-item" data-value="piso_revestimento">
                  <div class="check-box"><svg viewBox="0 0 12 12"><polyline points="1,6 5,10 11,2"/></svg></div>
                  <span class="check-label">Piso / Revestimento</span>
                </div>
                <div class="check-item" data-value="marcenaria">
                  <div class="check-box"><svg viewBox="0 0 12 12"><polyline points="1,6 5,10 11,2"/></svg></div>
                  <span class="check-label">Marcenaria</span>
                </div>
                <div class="check-item" data-value="obra_completa">
                  <div class="check-box"><svg viewBox="0 0 12 12"><polyline points="1,6 5,10 11,2"/></svg></div>
                  <span class="check-label">Obra Completa</span>
                </div>
              </div>
              <input type="hidden" name="services" id="servicesInput"/>
            </div>
          </div>
          
          <!-- 4: Detalhamento Técnico -->
          <div class="slide">
            <div class="slide-title">Detalhamento<br>técnico.</div>
            <div class="form-row">
              <div class="form-group">
                <label>Área aproximada (m²)</label>
                <input type="number" name="area_sqm" step="0.01" placeholder="Ex: 180"/>
              </div>
              <div class="form-group">
                <label>Qtd. de ambientes</label>
                <input type="number" name="rooms_count" placeholder="Ex: 5"/>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Idade do imóvel</label>
                <select name="property_age">
                  <option value="">— Selecione —</option>
                  <option value="novo">Novo (até 5 anos)</option>
                  <option value="medio">Médio (5-15 anos)</option>
                  <option value="antigo">Antigo (15-30 anos)</option>
                  <option value="muito_antigo">Muito Antigo (30+ anos)</option>
                </select>
              </div>
              <div class="form-group">
                <label>Situação atual</label>
                <select name="current_situation">
                  <option value="">— Selecione —</option>
                  <option value="vazio">Vazio</option>
                  <option value="habitado">Habitado</option>
                  <option value="em_obra">Em Obra</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Existe projeto?</label>
                <select name="has_project">
                  <option value="nao">Não</option>
                  <option value="arquitetonico">Arquitetônico</option>
                  <option value="estrutural">Estrutural</option>
                  <option value="completo">Completo</option>
                </select>
              </div>
              <div class="form-group">
                <label>Necessita demolição?</label>
                <select name="needs_demolition">
                  <option value="0">Não</option>
                  <option value="1">Sim</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label>Necessita remoção de entulho?</label>
              <select name="needs_debris_removal_select">
                <option value="0">Não</option>
                <option value="1">Sim</option>
                <option value="2">A avaliar</option>
              </select>
            </div>
          </div>
          
          <!-- 5: Orçamento e Prazo -->
          <div class="slide">
            <div class="slide-title">Orçamento<br>e prazo.</div>
            <div class="form-row">
              <div class="form-group">
                <label>Orçamento mínimo (R$)</label>
                <input type="text" name="budget_min" class="money-input" placeholder="R$ 0,00"/>
              </div>
              <div class="form-group">
                <label>Orçamento máximo (R$)</label>
                <input type="text" name="budget_max" class="money-input" placeholder="R$ 0,00"/>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Prazo desejado</label>
                <input type="date" name="desired_deadline"/>
              </div>
              <div class="form-group">
                <label>Urgência <span class="req">*</span></label>
                <select name="urgency" required>
                  <option value="">— Selecione —</option>
                  <option value="baixa">Baixa</option>
                  <option value="media">Média</option>
                  <option value="alta">Alta</option>
                  <option value="urgente">Urgente</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label>Forma de pagamento preferida</label>
              <select name="payment_method">
                <option value="">— Selecione —</option>
                <option value="a_vista">À vista</option>
                <option value="parcelado">Parcelado</option>
                <option value="etapas">Por etapas</option>
                <option value="financiamento">Financiamento</option>
              </select>
            </div>
          </div>
          
          <!-- 6: Materiais e Acabamentos -->
          <div class="slide">
            <div class="slide-title">Materiais e<br>acabamentos.</div>
            <div class="form-group">
              <label>Padrão de acabamento</label>
              <select name="finish_standard">
                <option value="">— Selecione —</option>
                <option value="economico">Econômico</option>
                <option value="medio">Médio</option>
                <option value="alto">Alto</option>
                <option value="luxo">Luxo</option>
              </select>
            </div>
            <div class="form-group">
              <label>Fornecimento de materiais</label>
              <select name="material_supply">
                <option value="">— Selecione —</option>
                <option value="cliente">Cliente fornece</option>
                <option value="parceiro">Parceiro fornece</option>
                <option value="misto">Misto</option>
              </select>
            </div>
            <div class="form-group">
              <label>Preferências de materiais / marcas</label>
              <textarea name="material_preferences" rows="4" placeholder="Descreva suas preferências..."></textarea>
            </div>
          </div>
          
          <!-- 7: Descrição Detalhada -->
          <div class="slide">
            <div class="slide-title">Conte-nos mais<br>sobre o projeto.</div>
            <div class="form-group">
              <label>Descrição detalhada <span class="req">*</span></label>
              <textarea name="description" required rows="6" placeholder="Descreva seu projeto em detalhes..."></textarea>
            </div>
            <div class="form-group">
              <label>Observações adicionais</label>
              <textarea name="notes" rows="4" placeholder="Informações extras que possam ajudar..."></textarea>
            </div>
          </div>
          
          <!-- 8: Anexos -->
          <div class="slide">
            <div class="slide-title">Anexe arquivos<br>(opcional).</div>
            <div class="form-group">
              <label>Fotos, plantas, projetos, etc.</label>
              <div class="upload-hint">Arraste arquivos aqui ou clique para selecionar</div>
              <div class="dropzone" id="dropzone">
                <svg class="dropzone-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                </svg>
                <p class="dropzone-text">Arraste arquivos aqui ou clique para selecionar</p>
                <input type="file" name="files[]" multiple id="fileInput" style="display:none"/>
              </div>
              <div class="file-list" id="fileList"></div>
            </div>
            <button type="submit" class="submit-btn">Enviar Demanda</button>
          </div>

        </div><!-- .slides-track -->
      </div><!-- .slider-viewport -->
      
      <!-- navigation -->
      <div class="slide-nav">
        <button type="button" class="slide-nav-btn" id="prevBtn" disabled>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
          Voltar
        </button>
        <button type="button" class="slide-nav-btn slide-nav-btn-next" id="nextBtn">
          Próximo
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
        </button>
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
<section class="how-it-works">
  <div class="section-container">
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
        <h3 class="how-card-title">1. Descreva seu projeto</h3>
        <p class="how-card-desc">Preencha o formulário com os detalhes da sua obra ou reforma.</p>
      </div>
      <div class="how-card">
        <div class="how-card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
          </svg>
        </div>
        <h3 class="how-card-title">2. Receba propostas</h3>
        <p class="how-card-desc">Parceiros qualificados enviam orçamentos personalizados.</p>
      </div>
      <div class="how-card">
        <div class="how-card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
        </div>
        <h3 class="how-card-title">3. Escolha e contrate</h3>
        <p class="how-card-desc">Compare propostas, escolha a melhor e inicie seu projeto.</p>
      </div>
    </div>
  </div>
</section>

<!-- BENEFÍCIOS -->
<section class="benefits">
  <div class="section-container">
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
<section class="cta-section">
  <div class="section-container">
    <h2 class="cta-title">Pronto para começar seu projeto?</h2>
    <p class="cta-desc">Cadastre-se agora e receba propostas de profissionais qualificados.</p>
    <div class="cta-buttons">
      <a href="#hero" class="cta-btn cta-btn-primary">Abrir Demanda</a>
      <a href="/como-funciona" class="cta-btn cta-btn-secondary">Saiba Mais</a>
    </div>
  </div>
</section>

<script>
(function(){
  const TOTAL_STEPS = 8;
  let currentStep = 0;
  
  const track = document.getElementById('slidesTrack');
  const slides = track.querySelectorAll('.slide');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const progressFill = document.getElementById('progressFill');
  const stepNum = document.getElementById('stepNum');
  const stepEyebrow = document.getElementById('stepEyebrow');
  const stepDots = document.getElementById('stepDots');
  const form = document.getElementById('demandaForm');
  
  const stepTitles = [
    'Dados Pessoais',
    'Informações Básicas',
    'Tipo de Serviço',
    'Detalhamento Técnico',
    'Orçamento e Prazo',
    'Materiais e Acabamentos',
    'Descrição Detalhada',
    'Anexos'
  ];
  
  // Criar dots
  for (let i = 0; i < TOTAL_STEPS; i++) {
    const dot = document.createElement('span');
    dot.className = 'step-dot' + (i === 0 ? ' active' : '');
    stepDots.appendChild(dot);
  }
  
  function updateUI() {
    track.style.transform = `translateX(-${currentStep * 100}%)`;
    progressFill.style.width = ((currentStep + 1) / TOTAL_STEPS * 100) + '%';
    stepNum.textContent = currentStep + 1;
    stepEyebrow.textContent = stepTitles[currentStep];
    
    prevBtn.disabled = currentStep === 0;
    nextBtn.style.display = currentStep === TOTAL_STEPS - 1 ? 'none' : 'flex';
    
    const dots = stepDots.querySelectorAll('.step-dot');
    dots.forEach((dot, i) => {
      dot.classList.toggle('active', i === currentStep);
    });
  }
  
  function validateStep(step) {
    const slide = slides[step];
    const required = slide.querySelectorAll('[required]');
    for (let inp of required) {
      if (!inp.value.trim()) {
        inp.focus();
        alert('Por favor, preencha todos os campos obrigatórios.');
        return false;
      }
    }
    
    // Validação especial: senha
    if (step === 0) {
      const pwd = slide.querySelector('[name="password"]');
      const conf = slide.querySelector('[name="password_confirm"]');
      if (pwd.value !== conf.value) {
        alert('As senhas não coincidem.');
        conf.focus();
        return false;
      }
    }
    
    // Validação especial: serviços
    if (step === 2) {
      const servicesInput = document.getElementById('servicesInput');
      if (!servicesInput.value) {
        alert('Selecione pelo menos um serviço.');
        return false;
      }
    }
    
    return true;
  }
  
  prevBtn.addEventListener('click', () => {
    if (currentStep > 0) {
      currentStep--;
      updateUI();
    }
  });
  
  nextBtn.addEventListener('click', () => {
    if (!validateStep(currentStep)) return;
    if (currentStep < TOTAL_STEPS - 1) {
      currentStep++;
      updateUI();
    }
  });
  
  // Serviços multi-select
  const serviceGrid = document.getElementById('serviceGrid');
  const servicesInput = document.getElementById('servicesInput');
  if (serviceGrid) {
    serviceGrid.addEventListener('click', (e) => {
      const item = e.target.closest('.check-item');
      if (!item) return;
      item.classList.toggle('checked');
      const selected = Array.from(serviceGrid.querySelectorAll('.check-item.checked'))
        .map(el => el.dataset.value);
      servicesInput.value = selected.join(',');
    });
  }
  
  // Máscaras BRL
  document.querySelectorAll('.money-input').forEach(inp => {
    inp.addEventListener('input', (e) => {
      let val = e.target.value.replace(/\D/g, '');
      if (!val) { e.target.value = ''; return; }
      val = (parseInt(val) / 100).toFixed(2);
      e.target.value = 'R$ ' + val.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    });
  });
  
  // Drag & Drop
  const dropzone = document.getElementById('dropzone');
  const fileInput = document.getElementById('fileInput');
  const fileList = document.getElementById('fileList');
  
  if (dropzone && fileInput) {
    dropzone.addEventListener('click', () => fileInput.click());
    
    dropzone.addEventListener('dragover', (e) => {
      e.preventDefault();
      dropzone.classList.add('dragover');
    });
    
    dropzone.addEventListener('dragleave', () => {
      dropzone.classList.remove('dragover');
    });
    
    dropzone.addEventListener('drop', (e) => {
      e.preventDefault();
      dropzone.classList.remove('dragover');
      const dt = e.dataTransfer;
      fileInput.files = dt.files;
      updateFileList();
    });
    
    fileInput.addEventListener('change', updateFileList);
    
    function updateFileList() {
      fileList.innerHTML = '';
      Array.from(fileInput.files).forEach((file, i) => {
        const item = document.createElement('div');
        item.className = 'file-item';
        item.innerHTML = `
          <span class="file-name">${file.name}</span>
          <button type="button" class="file-remove" data-index="${i}">×</button>
        `;
        fileList.appendChild(item);
      });
      
      fileList.querySelectorAll('.file-remove').forEach(btn => {
        btn.addEventListener('click', () => {
          const idx = parseInt(btn.dataset.index);
          const dt = new DataTransfer();
          Array.from(fileInput.files).forEach((f, i) => {
            if (i !== idx) dt.items.add(f);
          });
          fileInput.files = dt.files;
          updateFileList();
        });
      });
    }
  }
  
  // Scroll suave para #hero
  document.querySelectorAll('a[href="#hero"]').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      document.querySelector('.hero').scrollIntoView({ behavior: 'smooth' });
    });
  });
  
  updateUI();
})();
</script>
