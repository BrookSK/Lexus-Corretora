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
  <!-- LEFT: MULTI-STEP FORM -->
  <div class="hero-form-col">
    <!-- top progress -->
    <div class="progress-bar-wrap">
      <div class="progress-bar-fill" id="progressFill" style="width:16.66%"></div>
    </div>
    
    <!-- fixed header -->
    <div class="step-header">
      <div class="step-meta">
        <div class="step-eyebrow" id="stepEyebrow">Dados Pessoais</div>
        <div class="step-counter">Etapa <span id="stepNum">1</span> de 6</div>
      </div>
      <div class="step-dots" id="stepDots"></div>
    </div>
    
    <!-- slides -->
    <form method="POST" action="/seja-parceiro" enctype="multipart/form-data" id="parceiroForm">
      <?php echo Csrf::campo(); ?>
      <div class="slider-viewport">
        <div class="slides-track" id="slidesTrack">

          <!-- 1: Dados Pessoais -->
          <div class="slide">
            <div class="slide-title">Conte-nos<br>quem você é.</div>
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
                <label>CPF / CNPJ <span class="req">*</span></label>
                <input type="text" name="document" required placeholder="000.000.000-00"/>
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
          
          <!-- 2: Dados da Empresa -->
          <div class="slide">
            <div class="slide-title">Sobre sua<br>empresa.</div>
            <div class="form-group">
              <label>Tipo de parceiro <span class="req">*</span></label>
              <select name="type" required>
                <option value="">— Selecione —</option>
                <option value="prestador">Prestador de Serviços</option>
                <option value="fornecedor">Fornecedor de Materiais</option>
                <option value="arquiteto">Arquiteto / Engenheiro</option>
                <option value="construtora">Construtora</option>
              </select>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Nome Fantasia</label>
                <input type="text" name="fantasy_name" placeholder="Nome comercial"/>
              </div>
              <div class="form-group">
                <label>Inscrição Estadual</label>
                <input type="text" name="state_registration" placeholder="IE"/>
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
              <label>Endereço completo <span class="req">*</span></label>
              <input type="text" name="address" required placeholder="Rua, número, bairro, CEP"/>
            </div>
          </div>
          
          <!-- 3: Especialidades -->
          <div class="slide">
            <div class="slide-title">Suas<br>especialidades.</div>
            <div class="form-group">
              <label>Selecione suas áreas de atuação <span class="req">*</span> — pode escolher múltiplas</label>
              <div class="check-grid" id="specialtyGrid">
                <?php foreach ($CATEGORIAS_NICHO as $cat): ?>
                <div class="check-item" data-value="<?php echo View::e(strtolower(str_replace(' ', '_', $cat))); ?>">
                  <div class="check-box"><svg viewBox="0 0 12 12"><polyline points="1,6 5,10 11,2"/></svg></div>
                  <span class="check-label"><?php echo View::e($cat); ?></span>
                </div>
                <?php endforeach; ?>
              </div>
              <input type="hidden" name="specialties" id="specialtiesInput"/>
            </div>
          </div>
          
          <!-- 4: Experiência -->
          <div class="slide">
            <div class="slide-title">Sua<br>experiência.</div>
            <div class="form-row">
              <div class="form-group">
                <label>Tempo de experiência</label>
                <select name="experience_years">
                  <option value="">— Selecione —</option>
                  <option value="0-2">Até 2 anos</option>
                  <option value="3-5">3 a 5 anos</option>
                  <option value="6-10">6 a 10 anos</option>
                  <option value="10+">Mais de 10 anos</option>
                </select>
              </div>
              <div class="form-group">
                <label>Equipe disponível</label>
                <input type="number" name="team_size" placeholder="Nº de profissionais"/>
              </div>
            </div>
            <div class="form-group">
              <label>Capacidade mensal (projetos)</label>
              <input type="number" name="monthly_capacity" placeholder="Quantos projetos pode atender por mês"/>
            </div>
            <div class="form-group">
              <label>Apresentação / Bio <span class="req">*</span></label>
              <textarea name="description" required rows="6" placeholder="Conte sobre sua experiência, diferenciais e principais trabalhos..."></textarea>
            </div>
          </div>
          
          <!-- 5: Certificações e Documentos -->
          <div class="slide">
            <div class="slide-title">Certificações<br>e documentos.</div>
            <div class="form-group">
              <label>Certificações / Registros profissionais</label>
              <textarea name="certifications" rows="4" placeholder="Ex: CREA, CAU, certificações técnicas..."></textarea>
            </div>
            <div class="form-group">
              <label>Certidão CNPJ (opcional)</label>
              <div class="upload-hint">Envie a certidão simplificada da Receita Federal</div>
              <input type="file" name="certidao_cnpj" accept=".pdf,.jpg,.jpeg,.png"/>
            </div>
            <div class="form-group">
              <label>Referências / Clientes anteriores</label>
              <textarea name="references" rows="4" placeholder="Liste empresas ou clientes que podem atestar seu trabalho..."></textarea>
            </div>
          </div>
          
          <!-- 6: Portfólio -->
          <div class="slide">
            <div class="slide-title">Seu<br>portfólio.</div>
            <div class="form-group">
              <label>Fotos de trabalhos realizados (opcional)</label>
              <div class="upload-hint">Mostre seus melhores projetos</div>
              <div class="dropzone" id="dropzone">
                <svg class="dropzone-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                </svg>
                <p class="dropzone-text">Arraste arquivos aqui ou clique para selecionar</p>
                <input type="file" name="portfolio[]" multiple id="fileInput" accept="image/*,.pdf" style="display:none"/>
              </div>
              <div class="file-list" id="fileList"></div>
            </div>
            <div class="form-group">
              <label>Link do site / Instagram (opcional)</label>
              <input type="url" name="website" placeholder="https://..."/>
            </div>
            <button type="submit" class="submit-btn">Enviar Cadastro</button>
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
<section class="how-it-works">
  <div class="section-container">
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
        <p class="how-card-desc">Preencha o formulário e aguarde a aprovação do seu perfil.</p>
      </div>
      <div class="how-card">
        <div class="how-card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 6v6l4 2"/>
          </svg>
        </div>
        <h3 class="how-card-title">2. Receba oportunidades</h3>
        <p class="how-card-desc">Acesse demandas compatíveis com seu perfil e especialidades.</p>
      </div>
      <div class="how-card">
        <div class="how-card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="1" x2="12" y2="23"/>
            <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
          </svg>
        </div>
        <h3 class="how-card-title">3. Envie propostas e fature</h3>
        <p class="how-card-desc">Elabore orçamentos, feche negócios e receba com segurança.</p>
      </div>
    </div>
  </div>
</section>

<!-- BENEFÍCIOS -->
<section class="benefits">
  <div class="section-container">
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
<section class="cta-section">
  <div class="section-container">
    <h2 class="cta-title">Pronto para crescer com a Lexus?</h2>
    <p class="cta-desc">Cadastre-se agora e comece a receber oportunidades de negócio.</p>
    <div class="cta-buttons">
      <a href="#hero" class="cta-btn cta-btn-primary">Cadastrar Agora</a>
      <a href="/como-funciona" class="cta-btn cta-btn-secondary">Saiba Mais</a>
    </div>
  </div>
</section>

<script>
// Debug após HTML completo
console.log('=== LANDING PAGE DEBUG (AFTER HTML) ===');
const hero = document.getElementById('landing-hero');
console.log('Hero element:', hero);

if (hero) {
  console.log('Hero computed styles:', {
    display: window.getComputedStyle(hero).display,
    gridTemplateColumns: window.getComputedStyle(hero).gridTemplateColumns,
    flexDirection: window.getComputedStyle(hero).flexDirection,
    minHeight: window.getComputedStyle(hero).minHeight,
    padding: window.getComputedStyle(hero).padding
  });
}

// Debug das colunas
const formCol = document.querySelector('#landing-hero .hero-form-col');
const headlineCol = document.querySelector('#landing-hero .hero-headline-col');
console.log('Form column:', formCol);
console.log('Headline column:', headlineCol);

if (formCol) {
  console.log('Form col computed:', {
    display: window.getComputedStyle(formCol).display,
    flexDirection: window.getComputedStyle(formCol).flexDirection,
    background: window.getComputedStyle(formCol).backgroundColor
  });
  
  // Força estilos
  formCol.style.cssText = 'background: #161410 !important; border-right: 1px solid rgba(201,168,76,.1) !important; display: flex !important; flex-direction: column !important; position: relative !important; overflow: hidden !important; min-height: calc(100vh - 64px) !important;';
  console.log('Form col AFTER force:', window.getComputedStyle(formCol).display);
}

if (headlineCol) {
  console.log('Headline col computed:', {
    display: window.getComputedStyle(headlineCol).display,
    background: window.getComputedStyle(headlineCol).backgroundColor
  });
  
  // Força estilos
  headlineCol.style.cssText = 'background: #0E0C09 !important; display: flex !important; align-items: center !important; justify-content: center !important; padding: 80px 72px !important; position: relative !important;';
  console.log('Headline col AFTER force:', window.getComputedStyle(headlineCol).display);
}

console.log('=== END DEBUG ===');
</script>

<script>
(function(){
  const TOTAL_STEPS = 6;
  let currentStep = 0;
  
  const track = document.getElementById('slidesTrack');
  const slides = track.querySelectorAll('.slide');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const progressFill = document.getElementById('progressFill');
  const stepNum = document.getElementById('stepNum');
  const stepEyebrow = document.getElementById('stepEyebrow');
  const stepDots = document.getElementById('stepDots');
  const form = document.getElementById('parceiroForm');
  
  const stepTitles = [
    'Dados Pessoais',
    'Dados da Empresa',
    'Especialidades',
    'Experiência',
    'Certificações e Documentos',
    'Portfólio'
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
    
    // Validação especial: especialidades
    if (step === 2) {
      const specialtiesInput = document.getElementById('specialtiesInput');
      if (!specialtiesInput.value) {
        alert('Selecione pelo menos uma especialidade.');
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
  
  // Especialidades multi-select
  const specialtyGrid = document.getElementById('specialtyGrid');
  const specialtiesInput = document.getElementById('specialtiesInput');
  if (specialtyGrid) {
    specialtyGrid.addEventListener('click', (e) => {
      const item = e.target.closest('.check-item');
      if (!item) return;
      item.classList.toggle('checked');
      const selected = Array.from(specialtyGrid.querySelectorAll('.check-item.checked'))
        .map(el => el.dataset.value);
      specialtiesInput.value = selected.join(',');
    });
  }
  
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
