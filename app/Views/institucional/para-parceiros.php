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

$pageTitle = 'Para Parceiros — Lexus Corretora';
include __DIR__ . '/_landing-header.php';
?>

<!-- HERO -->
<section class="hero">
  <!-- LEFT: MULTI-STEP FORM -->
  <div class="hero-form-col">
    <!-- top progress -->
    <div class="progress-bar-wrap">
      <div class="progress-bar-fill" id="progressFill" style="width:16.67%"></div>
    </div>
    
    <!-- fixed header -->
    <div class="step-header">
      <div class="step-meta">
        <div class="step-eyebrow" id="stepEyebrow">Dados da Empresa</div>
        <div class="step-counter">Etapa <span id="stepNum">1</span> de 6</div>
      </div>
      <div class="step-dots" id="stepDots"></div>
    </div>
    
    <!-- slides -->
    <form method="POST" action="/seja-parceiro" enctype="multipart/form-data" id="parceiroForm">
      <?php echo Csrf::campo(); ?>
      <div class="slider-viewport">
        <div class="slides-track" id="slidesTrack">

          <!-- 1: Dados da Empresa -->
          <div class="slide">
            <div class="slide-title">Dados da<br>sua empresa.</div>
            <div class="form-row">
              <div class="form-group">
                <label>Nome / Razão Social <span class="req">*</span></label>
                <input type="text" name="name" required/>
              </div>
              <div class="form-group">
                <label>Nome Fantasia</label>
                <input type="text" name="trade_name"/>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Tipo de Parceiro <span class="req">*</span></label>
                <select name="type" required>
                  <option value="">— Selecione —</option>
                  <option value="construtora">Construtora</option>
                  <option value="arquiteto">Arquiteto</option>
                  <option value="engenheiro">Engenheiro</option>
                  <option value="empreiteira">Empreiteira</option>
                  <option value="prestador">Prestador de Serviços</option>
                  <option value="fornecedor">Fornecedor</option>
                </select>
              </div>
              <div class="form-group">
                <label>CPF/CNPJ <span class="req">*</span></label>
                <input type="text" name="document" required/>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>E-mail <span class="req">*</span></label>
                <input type="email" name="email" required/>
              </div>
              <div class="form-group">
                <label>WhatsApp <span class="req">*</span></label>
                <input type="tel" name="whatsapp" required/>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Telefone Fixo</label>
                <input type="tel" name="phone"/>
              </div>
              <div class="form-group">
                <label>CREA/CAU</label>
                <input type="text" name="crea_cau" placeholder="Ex: CREA-SP 123456"/>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Site</label>
                <input type="text" name="website" placeholder="www.suaempresa.com.br"/>
              </div>
              <div class="form-group">
                <label>Instagram</label>
                <input type="text" name="instagram" placeholder="@suaempresa"/>
              </div>
            </div>
            <div class="form-group">
              <label>LinkedIn</label>
              <input type="text" name="linkedin" placeholder="linkedin.com/company/suaempresa"/>
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
          
          <!-- 2: Localização -->
          <div class="slide">
            <div class="slide-title">Localização e<br>área de atuação.</div>
            <div class="form-row">
              <?php
              $estadoSelecionado = '';
              $cidadeSelecionada = '';
              $obrigatorio = true;
              include __DIR__ . '/../_partials/campos-estado-cidade.php';
              ?>
            </div>
            <div class="form-group">
              <label>Endereço Completo</label>
              <input type="text" name="address" placeholder="Rua, número, bairro, CEP"/>
            </div>
          </div>
          
          <!-- 3: Especialidades -->
          <div class="slide">
            <div class="slide-title">Especialidades<br>e serviços.</div>
            <div class="form-group">
              <label>Selecione suas especialidades <span class="req">*</span></label>
              <div class="check-grid" id="specialtiesGrid">
                <?php foreach ($CATEGORIAS_NICHO as $cat): ?>
                <div class="check-item" data-value="<?php echo View::e($cat); ?>">
                  <div class="check-box"><svg viewBox="0 0 12 12"><polyline points="1,6 5,10 11,2"/></svg></div>
                  <span class="check-label"><?php echo View::e($cat); ?></span>
                </div>
                <?php endforeach; ?>
              </div>
              <input type="hidden" name="specialties" id="specialtiesInput"/>
            </div>
          </div>
          
          <!-- 4: Capacidade e Estrutura -->
          <div class="slide">
            <div class="slide-title">Capacidade e<br>estrutura.</div>
            <div class="form-row">
              <div class="form-group">
                <label>Tamanho da Equipe</label>
                <select name="team_size">
                  <option value="">— Selecione —</option>
                  <option value="1-5">1 a 5 pessoas</option>
                  <option value="6-10">6 a 10 pessoas</option>
                  <option value="11-20">11 a 20 pessoas</option>
                  <option value="21-50">21 a 50 pessoas</option>
                  <option value="50+">Mais de 50 pessoas</option>
                </select>
              </div>
              <div class="form-group">
                <label>Tempo de Mercado (anos)</label>
                <input type="number" name="years_in_market" min="0" placeholder="Ex: 10"/>
              </div>
            </div>
            <div class="form-group">
              <label style="display:flex;align-items:center;gap:8px">
                <input type="checkbox" name="has_own_team" value="1"/>
                Possui equipe própria (não terceiriza)
              </label>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Ticket Médio Mínimo (R$)</label>
                <div class="currency-wrap">
                  <span>R$</span>
                  <input type="text" id="ticket_min_display" placeholder="0,00" inputmode="numeric" autocomplete="off" oninput="mascaraBRL(this,'average_ticket_min')"/>
                  <input type="hidden" name="average_ticket_min" id="average_ticket_min"/>
                </div>
              </div>
              <div class="form-group">
                <label>Ticket Médio Máximo (R$)</label>
                <div class="currency-wrap">
                  <span>R$</span>
                  <input type="text" id="ticket_max_display" placeholder="0,00" inputmode="numeric" autocomplete="off" oninput="mascaraBRL(this,'average_ticket_max')"/>
                  <input type="hidden" name="average_ticket_max" id="average_ticket_max"/>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Descrição da Empresa <span class="req">*</span></label>
              <textarea name="description" required rows="4" placeholder="Conte sobre sua empresa, experiência, diferenciais..."></textarea>
            </div>
          </div>
          
          <!-- 5: Portfólio -->
          <div class="slide">
            <div class="slide-title">Portfólio e<br>qualificação.</div>
            <div class="notice-box">
              <span class="ni">📸</span>
              <p><strong>Portfólio (opcional, mas recomendado)</strong>Envie fotos de obras similares para aumentar suas chances de receber oportunidades. Quanto melhor seu portfólio, maior a confiança dos clientes.</p>
            </div>
            <div class="form-group">
              <label>Portfólio (PDF ou Fotos)</label>
              <div class="file-upload-area" onclick="document.getElementById('portfolioInput').click()">
                <input type="file" id="portfolioInput" name="portfolio[]" multiple accept=".pdf,.jpg,.jpeg,.png,.webp"/>
                <div style="font-size:1.4rem;margin-bottom:8px">📎</div>
                <p class="upload-hint">Arraste arquivos aqui ou <span>clique para selecionar</span><br>Envie 1 PDF ou no mínimo 6 fotos de trabalhos realizados</p>
              </div>
            </div>
            <div class="form-group">
              <label>Link do Portfólio Online</label>
              <input type="text" name="portfolio_url" placeholder="https://..."/>
            </div>
          </div>
          
          <!-- 6: Documentação e Preferências -->
          <div class="slide">
            <div class="slide-title">Documentação e<br>preferências.</div>
            <div class="form-group">
              <label>Certidão de CNPJ Ativo</label>
              <div style="background:rgba(201,168,76,.06);border:1px solid rgba(201,168,76,.15);padding:12px;margin-bottom:8px;font-size:.82rem;line-height:1.5">
                Para emitir, acesse: <a href="https://solucoes.receita.fazenda.gov.br/servicos/cnpjreva/cnpjreva_solicitacao.asp" target="_blank" rel="noopener" style="color:var(--gold)">Receita Federal</a>
              </div>
              <div class="file-upload-area" onclick="document.getElementById('certidaoInput').click()">
                <input type="file" id="certidaoInput" name="certidao_cnpj" accept=".pdf,.jpg,.jpeg,.png"/>
                <div style="font-size:1.4rem;margin-bottom:8px">📄</div>
                <p class="upload-hint">Clique para selecionar o arquivo</p>
              </div>
            </div>
            <div class="form-group">
              <label>Outros Documentos (Certificações, Licenças, etc.)</label>
              <div class="file-upload-area" onclick="document.getElementById('docsInput').click()">
                <input type="file" id="docsInput" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png"/>
                <div style="font-size:1.4rem;margin-bottom:8px">📋</div>
                <p class="upload-hint">Clique para selecionar arquivos</p>
              </div>
            </div>
            <div class="form-group">
              <label style="display:flex;align-items:center;gap:8px">
                <input type="checkbox" name="accepts_referral" value="1" checked/>
                Aceito receber demandas por repasse de outros parceiros
              </label>
              <small style="font-size:.75rem;color:var(--text-muted);display:block;margin-top:4px">
                Você poderá receber oportunidades indicadas por outros profissionais da rede
              </small>
            </div>
            <div class="form-group">
              <label>Comissão de Repasse (%)</label>
              <input type="number" name="referral_commission_pct" min="0" max="100" step="0.1" placeholder="Ex: 10" value="10"/>
              <small style="font-size:.75rem;color:var(--text-muted);display:block;margin-top:4px">
                Percentual que você está disposto a pagar em comissão por demandas repassadas
              </small>
            </div>
          </div>
          
        </div><!-- /slides-track -->
      </div><!-- /slider-viewport -->
      
      <!-- success -->
      <div class="success-screen" id="successScreen">
        <div class="success-icon">
          <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="success-title">Cadastro enviado!</div>
        <p class="success-sub">Nossa equipe analisará seu perfil e retornará em até 48h com os próximos passos.</p>
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
      <div class="hero-tag fade-up">Para Parceiros</div>
      <h1 class="hero-h1 fade-up d1">Receba oportunidades<br><em>qualificadas.</em></h1>
      <p class="hero-sub fade-up d2">Faça parte da rede Lexus e receba demandas estruturadas, compatíveis com seu perfil e especialidade — com o Selo Vetriks de qualidade.</p>
      <div class="hero-stats fade-up d3">
        <div>
          <div class="stat-num">500+</div>
          <div class="stat-label">Demandas/mês</div>
        </div>
        <div>
          <div class="stat-num">85%</div>
          <div class="stat-label">Taxa de conversão</div>
        </div>
        <div>
          <div class="stat-num">0</div>
          <div class="stat-label">Taxa de cadastro</div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- HOW -->
<section class="how-section">
  <div class="section-inner">
    <div class="section-eyebrow">Como funciona</div>
    <h2 class="section-h2">Como funciona para <em>parceiros</em></h2>
    <p style="color:#5a5145;max-width:500px;font-size:.87rem;line-height:1.75">Cadastre-se, complete seu perfil e receba oportunidades compatíveis com sua região, especialidade e porte de atuação.</p>
    <div class="how-grid">
      <div class="how-card">
        <span class="step-num">01</span>
        <div class="step-icon">
          <svg viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5"/><path d="M15.5 2.5a2.121 2.121 0 0 1 3 3L12 12l-4 1 1-4 6.5-6.5z"/></svg>
        </div>
        <div class="step-title">Cadastre-se e qualifique</div>
        <p class="step-desc">Complete seu perfil profissional. Após análise, você recebe o Selo Vetriks e passa a receber oportunidades.</p>
      </div>
      <div class="how-card">
        <span class="step-num">02</span>
        <div class="step-icon">
          <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </div>
        <div class="step-title">Receba demandas estruturadas</div>
        <p class="step-desc">Acesse oportunidades qualificadas e compatíveis com seu perfil. Todas as demandas passam por curadoria da equipe Lexus.</p>
      </div>
      <div class="how-card">
        <span class="step-num">03</span>
        <div class="step-icon">
          <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="step-title">Envie propostas e feche negócios</div>
        <p class="step-desc">Elabore propostas estruturadas e acompanhe todo o processo pelo painel. A Lexus apoia na apresentação ao cliente.</p>
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
        <div class="benefit-title">Selo Vetriks de qualificação</div>
        <p class="benefit-desc">Certificado de experiência, capacidade e confiabilidade que aumenta sua visibilidade e credibilidade na plataforma.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
        </div>
        <div class="benefit-title">Demandas qualificadas e estruturadas</div>
        <p class="benefit-desc">Receba oportunidades com informações completas, orçamento definido e cliente já qualificado pela equipe Lexus.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
        <div class="benefit-title">Processo transparente e ágil</div>
        <p class="benefit-desc">Acompanhe cada etapa em tempo real. Sem taxas de cadastro, sem intermediação financeira, sem burocracia.</p>
      </div>
      <div class="benefit-card">
        <div class="benefit-icon">
          <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="benefit-title">Suporte e curadoria dedicada</div>
        <p class="benefit-desc">Nossa equipe cuida da qualificação de clientes e apresentação de propostas, aumentando sua taxa de conversão.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="cta-inner">
    <div>
      <div class="cta-tag">Próximo passo</div>
      <h2 class="cta-h2">Quer fazer parte da<br><em>rede Lexus?</em></h2>
    </div>
    <div class="cta-actions">
      <a href="/seja-parceiro" class="btn-cta-primary">Seja Parceiro →</a>
      <a href="/vetriks" class="btn-cta-ghost">Conheça o Vetriks</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/_landing-footer.php'; ?>

<script>
/* Multi-step slider */
const TOTAL = 6;
const LABELS = ['Dados da Empresa','Localização','Especialidades','Capacidade e Estrutura','Portfólio','Documentação'];
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
const form = document.getElementById('parceiroForm');

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
    btnNext.innerHTML='Enviar Cadastro <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2.2"><polyline points="20 6 9 17 4 12"/></svg>';
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
  const type = form.querySelector('[name="type"]').value;
  const description = form.querySelector('[name="description"]').value;
  
  if(!name || !email || !password || !type || !description){
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

// checkbox toggle for specialties
document.querySelectorAll('#specialtiesGrid .check-item').forEach(item=>{
  item.addEventListener('click',()=>{
    item.classList.toggle('checked');
    updateSpecialtiesInput();
  });
});

function updateSpecialtiesInput(){
  const checked = Array.from(document.querySelectorAll('#specialtiesGrid .check-item.checked')).map(el=>el.dataset.value);
  document.getElementById('specialtiesInput').value = JSON.stringify(checked);
}

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
