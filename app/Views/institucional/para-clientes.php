<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

// Incluir categorias para o formulário
$CATEGORIAS_NICHO = [
    'Construção Civil', 'Reforma Residencial', 'Reforma Comercial',
    'Arquitetura', 'Engenharia', 'Elétrica', 'Hidráulica',
    'Pintura', 'Marcenaria', 'Serralheria', 'Vidraçaria',
    'Paisagismo', 'Decoração', 'Design de Interiores'
];

$pageTitle = 'Para Clientes — Lexus Corretora';
include __DIR__ . '/_landing-header.php';
?>

<!-- HERO -->
<section class="hero">
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
          
          <!-- 5: Escopo -->
          <div class="slide">
            <div class="slide-title">Descreva seu<br>projeto.</div>
            <div class="form-group">
              <label>Descrição detalhada <span class="req">*</span></label>
              <textarea name="description" required style="min-height:180px" placeholder="Descreva o escopo da obra, necessidades específicas, situação atual, expectativas… quanto mais detalhe, melhor a qualidade dos orçamentos recebidos."></textarea>
            </div>
          </div>
          
          <!-- 6: Prazo e Urgência -->
          <div class="slide">
            <div class="slide-title">Prazos e<br>urgência.</div>
            <div class="form-row">
              <div class="form-group">
                <label>Quando quer iniciar?</label>
                <input type="date" name="desired_start_date"/>
              </div>
              <div class="form-group">
                <label>Prazo desejado</label>
                <input type="date" name="desired_deadline"/>
              </div>
            </div>
            <div class="form-group">
              <label>Nível de urgência</label>
              <select name="urgency">
                <option value="baixa">Baixa — Posso aguardar</option>
                <option value="media" selected>Média — Algumas semanas</option>
                <option value="alta">Alta — Preciso em breve</option>
                <option value="critica">Crítica — Urgente</option>
              </select>
            </div>
          </div>
          
          <!-- 7: Financeiro + Preferências -->
          <div class="slide">
            <div class="slide-title">Orçamento e<br>preferências.</div>
            <div class="form-row">
              <div class="form-group">
                <label>Orçamento mínimo</label>
                <div class="currency-wrap">
                  <span>R$</span>
                  <input type="text" id="bmin_display" placeholder="0,00" inputmode="numeric" autocomplete="off" oninput="mascaraBRL(this,'budget_min')"/>
                  <input type="hidden" name="budget_min" id="budget_min"/>
                </div>
              </div>
              <div class="form-group">
                <label>Orçamento máximo</label>
                <div class="currency-wrap">
                  <span>R$</span>
                  <input type="text" id="bmax_display" placeholder="0,00" inputmode="numeric" autocomplete="off" oninput="mascaraBRL(this,'budget_max')"/>
                  <input type="hidden" name="budget_max" id="budget_max"/>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Preferências adicionais</label>
              <div class="check-grid">
                <div class="check-item" data-checkbox="wants_invoice">
                  <div class="check-box"><svg viewBox="0 0 12 12"><polyline points="1,6 5,10 11,2"/></svg></div>
                  <span class="check-label">Preciso de nota fiscal</span>
                </div>
                <div class="check-item" data-checkbox="needs_art_rrt">
                  <div class="check-box"><svg viewBox="0 0 12 12"><polyline points="1,6 5,10 11,2"/></svg></div>
                  <span class="check-label">Preciso de ART/RRT</span>
                </div>
              </div>
              <input type="hidden" name="wants_invoice" id="wants_invoice" value="0"/>
              <input type="hidden" name="needs_art_rrt" id="needs_art_rrt" value="0"/>
            </div>
            <div class="form-group">
              <label>Preferência de contratação</label>
              <select name="hiring_preference">
                <option value="equilibrio" selected>Equilíbrio Preço/Qualidade</option>
                <option value="menor_preco">Menor Preço</option>
                <option value="melhor_qualidade">Melhor Qualidade</option>
              </select>
            </div>
            <div class="form-group">
              <label>Observações adicionais</label>
              <textarea name="notes" placeholder="Informações adicionais, restrições, preferências…" style="min-height:80px"></textarea>
            </div>
          </div>
          
          <!-- 8: Evidências visuais -->
          <div class="slide">
            <div class="slide-title">Evidências<br>visuais.</div>
            <div class="notice-box">
              <span class="ni">⚠️</span>
              <p><strong>Crítico para bom orçamento</strong>Fotos, vídeos e plantas são essenciais para que os parceiros elaborem orçamentos precisos. Quanto mais informação visual você fornecer, maior a qualidade das propostas.</p>
            </div>
            <div class="form-group">
              <label>Upload de fotos / vídeos / plantas (recomendado)</label>
              <div class="file-upload-area" id="dropArea" onclick="document.getElementById('fileInput').click()">
                <input type="file" id="fileInput" name="files[]" multiple accept="image/*,video/*,.pdf,.dwg" onchange="handleFiles(this.files)"/>
                <div style="font-size:1.4rem;margin-bottom:8px">📎</div>
                <p class="upload-hint">Arraste arquivos aqui ou <span>clique para selecionar</span><br>JPG, PNG, MP4, PDF, DWG • até 50MB por arquivo</p>
              </div>
              <div class="upload-files-list" id="fileList"></div>
            </div>
          </div>
          
        </div><!-- /slides-track -->
      </div><!-- /slider-viewport -->
      
      <!-- success -->
      <div class="success-screen" id="successScreen">
        <div class="success-icon">
          <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="success-title">Demanda enviada!</div>
        <p class="success-sub">Nossa equipe receberá sua solicitação e retornará em até 24h com os próximos passos.</p>
        <button type="button" class="btn-reset" onclick="resetForm()">Enviar nova demanda</button>
      </div>
      
      <!-- nav buttons -->
      <div class="form-nav" id="formNav">
        <button type="button" class="btn-back" id="btnBack" disabled onclick="navigate(-1)">← Voltar</button>
        <button type="button" class="btn-next" id="btnNext" onclick="navigate(1)">
          Avançar
          <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
    </form>
  </div>
  
  <!-- RIGHT: HEADLINE -->
  <div class="hero-headline-col">
    <div class="hero-text">
      <div class="hero-tag fade-up">Para Clientes</div>
      <h1 class="hero-h1 fade-up d1">Sua obra merece<br><em>decisões<br>melhores.</em></h1>
      <p class="hero-sub fade-up d2">Descubra como a Lexus simplifica o processo de encontrar o parceiro ideal para o seu projeto — com curadoria, transparência e suporte em cada etapa.</p>
      <div class="hero-stats fade-up d3">
        <div>
          <div class="stat-num">300+</div>
          <div class="stat-label">Parceiros ativos</div>
        </div>
        <div>
          <div class="stat-num">98%</div>
          <div class="stat-label">Satisfação</div>
        </div>
        <div>
          <div class="stat-num">0</div>
          <div class="stat-label">Cobrança antecipada</div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- HOW -->
<section class="how-section">
  <div class="section-inner">
    <div class="section-eyebrow">Como funciona</div>
    <h2 class="section-h2">Como funciona para <em>você</em></h2>
    <p style="color:#5a5145;max-width:500px;font-size:.87rem;line-height:1.75">Você não precisa buscar sozinho. A Lexus faz a curadoria, coleta orçamentos e apoia sua escolha — sem intermediação financeira.</p>
    <div class="how-grid">
      <div class="how-card">
        <span class="step-num">01</span>
        <div class="step-icon">
          <svg viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5"/><path d="M15.5 2.5a2.121 2.121 0 0 1 3 3L12 12l-4 1 1-4 6.5-6.5z"/></svg>
        </div>
        <div class="step-title">Abra uma demanda</div>
        <p class="step-desc">Descreva seu projeto. Nossa equipe estrutura sua necessidade para atrair os melhores parceiros qualificados.</p>
      </div>
      <div class="how-card">
        <span class="step-num">02</span>
        <div class="step-icon">
          <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </div>
        <div class="step-title">Receba propostas qualificadas</div>
        <p class="step-desc">Conectamos sua demanda a parceiros com Selo Vetriks. Você recebe múltiplas propostas para comparar sem perder tempo.</p>
      </div>
      <div class="how-card">
        <span class="step-num">03</span>
        <div class="step-icon">
          <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="step-title">Tome a melhor decisão</div>
        <p class="step-desc">Com suporte da equipe Lexus, avalie as propostas e escolha o parceiro ideal com segurança e transparência.</p>
      </div>
    </div>
  </div>
</section>

<!-- BENEFITS -->
<section class="benefits-section">
  <div class="section-inner">
    <div class="section-eyebrow">Benefícios</div>
    <h2 class="section-h2">O que você <em>ganha</em></h2>
    <div class="benefits-grid">
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div class="benefit-title">Rede qualificada com Selo Vetriks</div>
        <p class="benefit-desc">Acesso exclusivo a parceiros verificados e certificados. Cada empresa passa por rigoroso processo de qualificação.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
        </div>
        <div class="benefit-title">Múltiplas propostas para comparar</div>
        <p class="benefit-desc">Receba orçamentos detalhados de diferentes parceiros e tome decisões embasadas com informação completa.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
        <div class="benefit-title">Processo estruturado e transparente</div>
        <p class="benefit-desc">Acompanhe cada etapa em tempo real. Sem surpresas, sem taxas ocultas, sem intermediação financeira.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="benefit-title">Suporte dedicado em cada etapa</div>
        <p class="benefit-desc">Nossa equipe acompanha toda a jornada — da abertura da demanda até a escolha do parceiro ideal.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="cta-inner">
    <div>
      <div class="cta-tag">Próximo passo</div>
      <h2 class="cta-h2">Pronto para<br><em>começar?</em></h2>
    </div>
    <div class="cta-actions">
      <a href="/abrir-demanda" class="btn-cta-primary">Abrir Demanda →</a>
      <a href="/como-funciona" class="btn-cta-ghost">Como Funciona</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/_landing-footer.php'; ?>

<script>
/* Multi-step slider */
const TOTAL = 8;
const LABELS = ['Dados Pessoais','Informações Básicas','Tipo de Serviço','Detalhamento Técnico','Escopo do Serviço','Prazo e Urgência','Expectativa Financeira','Evidências Visuais'];
let current = 0;
const track = document.getElementById('slidesTrack');
const progressFill = document.getElementById('progressFill');
const stepNumEl = document.getElementById('stepNum');
const eyebrowEl = document.getElementById('stepEyebrow');
const dotsWrap = document.getElementById('stepDots');
const btnBack = document.getElementById('btnBack');
const btnNext = document.getElementById('btnNext');
const formNav = document.getElementById('formNav');
const successEl = document.getElementById('successScreen');
const form = document.getElementById('demandaForm');

// build dots
for(let i=0;i<TOTAL;i++){
  const d=document.createElement('div');
  d.className='step-dot'+(i===0?' active':'');
  d.style.width=i===0?'24px':'8px';
  dotsWrap.appendChild(d);
}

function updateUI(){
  track.style.transform=`translateX(-${current*100}%)`;
  progressFill.style.width=`${((current+1)/TOTAL)*100}%`;
  stepNumEl.textContent=current+1;
  eyebrowEl.textContent=LABELS[current];
  const dots=dotsWrap.querySelectorAll('.step-dot');
  dots.forEach((d,i)=>{
    d.classList.remove('active','done');
    d.style.width='8px';
    if(i<current)d.classList.add('done');
    else if(i===current){d.classList.add('active');d.style.width='24px';}
  });
  btnBack.disabled=current===0;
  if(current===TOTAL-1){
    btnNext.innerHTML='Enviar Solicitação <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2.2"><polyline points="20 6 9 17 4 12"/></svg>';
  } else {
    btnNext.innerHTML='Avançar <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2.2"><polyline points="9 18 15 12 9 6"/></svg>';
  }
  const slides=track.querySelectorAll('.slide');
  if(slides[current])slides[current].scrollTop=0;
}

function navigate(dir){
  if(dir===1&&current===TOTAL-1){ 
    // Validar e submeter
    if(validateForm()){
      form.submit();
    }
    return; 
  }
  current=Math.max(0,Math.min(TOTAL-1,current+dir));
  updateUI();
}

function validateForm(){
  const name = form.querySelector('[name="name"]').value;
  const email = form.querySelector('[name="email"]').value;
  const password = form.querySelector('[name="password"]').value;
  const password_confirm = form.querySelector('[name="password_confirm"]').value;
  const title = form.querySelector('[name="title"]').value;
  const description = form.querySelector('[name="description"]').value;
  
  if(!name || !email || !password || !title || !description){
    alert('Por favor, preencha todos os campos obrigatórios.');
    return false;
  }
  
  if(password !== password_confirm){
    alert('As senhas não coincidem.');
    return false;
  }
  
  if(password.length < 8){
    alert('A senha deve ter no mínimo 8 caracteres.');
    return false;
  }
  
  return true;
}

function resetForm(){
  current=0;
  track.style.display='flex';
  document.querySelector('.step-header').style.display='block';
  formNav.style.display='flex';
  successEl.classList.remove('show');
  updateUI();
}

// checkbox toggle for services
document.querySelectorAll('#serviceGrid .check-item').forEach(item=>{
  item.addEventListener('click',()=>{
    item.classList.toggle('checked');
    updateServicesInput();
  });
});

function updateServicesInput(){
  const checked = Array.from(document.querySelectorAll('#serviceGrid .check-item.checked')).map(el=>el.dataset.value);
  document.getElementById('servicesInput').value = checked.join(',');
}

// checkbox toggle for preferences
document.querySelectorAll('[data-checkbox]').forEach(item=>{
  item.addEventListener('click',()=>{
    item.classList.toggle('checked');
    const field = item.dataset.checkbox;
    document.getElementById(field).value = item.classList.contains('checked') ? '1' : '0';
  });
});

// Currency mask
function mascaraBRL(input, hiddenId) {
  var digits = input.value.replace(/\D/g, '');
  if (!digits) { input.value = ''; document.getElementById(hiddenId).value = ''; return; }
  var cents = parseInt(digits, 10);
  var reais = (cents / 100).toFixed(2);
  var parts = reais.split('.');
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  input.value = 'R$ ' + parts[0] + ',' + parts[1];
  document.getElementById(hiddenId).value = (cents / 100).toFixed(2);
}

// drag and drop
const dropArea=document.getElementById('dropArea');
const fileList =document.getElementById('fileList');
let uploadedFiles=[];

['dragenter','dragover'].forEach(ev=>{
  dropArea.addEventListener(ev,e=>{
    e.preventDefault();
    dropArea.style.borderColor='var(--gold)';
    dropArea.style.background='rgba(201,168,76,.06)';
  });
});

['dragleave','drop'].forEach(ev=>{
  dropArea.addEventListener(ev,e=>{
    e.preventDefault();
    dropArea.style.borderColor='';
    dropArea.style.background='';
    if(ev==='drop')handleFiles(e.dataTransfer.files);
  });
});

function handleFiles(files){
  Array.from(files).forEach(f=>{
    uploadedFiles.push(f);
  });
  renderFiles();
}

function renderFiles(){
  fileList.innerHTML='';
  uploadedFiles.forEach((f,i)=>{
    const el=document.createElement('div');
    el.className='upload-file-item';
    el.innerHTML=`<span>${f.name} <span style="color:var(--text-muted);font-size:.68rem">${(f.size/1024).toFixed(0)} KB</span></span><button type="button" onclick="removeFile(${i})">✕</button>`;
    fileList.appendChild(el);
  });
}

function removeFile(i){
  uploadedFiles.splice(i,1);
  renderFiles();
}

// scroll reveal
const obs=new IntersectionObserver(entries=>{
  entries.forEach(e=>{
    if(e.isIntersecting){
      e.target.querySelectorAll('.how-card,.benefit-card').forEach((el,i)=>{
        el.style.cssText=`opacity:0;transform:translateY(18px);transition:opacity .5s ease ${i*.1}s,transform .5s ease ${i*.1}s`;
        requestAnimationFrame(()=>{
          el.style.opacity='1';
          el.style.transform='translateY(0)';
        });
      });
      obs.unobserve(e.target);
    }
  });
},{threshold:.12});

document.querySelectorAll('.how-section,.benefits-section').forEach(s=>obs.observe(s));

updateUI();
</script>
