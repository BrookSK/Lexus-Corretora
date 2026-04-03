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
?>
<style>
/* Estilos específicos da página para-parceiros com formulário multi-step */
.hero-para-parceiros { min-height: 100vh; display: grid; grid-template-columns: 1fr 1fr; padding-top: 64px; }
.hero-headline-col { order: 2; display: flex; align-items: center; padding: 80px 72px; position: relative; overflow: hidden; }
.hero-headline-col::before { content: ''; position: absolute; inset: 0; pointer-events: none; background: radial-gradient(ellipse 60% 50% at 75% 30%, rgba(201,168,76,.08) 0%, transparent 70%), radial-gradient(ellipse 40% 60% at 20% 80%, rgba(201,168,76,.04) 0%, transparent 60%); }
.hero-text { position: relative; z-index: 1; }
.hero-tag { display: flex; align-items: center; gap: 10px; font-size: .68rem; letter-spacing: .18em; text-transform: uppercase; color: var(--gold); margin-bottom: 32px; }
.hero-tag::before { content: ''; display: block; width: 28px; height: 1px; background: var(--gold); }
.hero-h1 { font-family: 'Cormorant Garamond', serif; font-size: clamp(3rem, 4.5vw, 5.2rem); font-weight: 300; line-height: 1.05; color: var(--cream); margin-bottom: 24px; }
.hero-h1 em { font-style: italic; color: var(--gold); }
.hero-sub { font-size: .95rem; color: var(--text-light); max-width: 340px; line-height: 1.75; margin-bottom: 48px; }
.hero-stats { display: flex; gap: 36px; border-top: 1px solid rgba(201,168,76,.15); padding-top: 28px; }
.stat-num { font-family: 'Cormorant Garamond', serif; font-size: 2.1rem; font-weight: 300; color: var(--gold); line-height: 1; margin-bottom: 4px; }
.stat-label { font-size: .68rem; letter-spacing: .1em; text-transform: uppercase; color: var(--text-muted); }

/* Form panel */
.hero-form-col { order: 1; background: var(--dark2); border-right: 1px solid rgba(201,168,76,.1); display: flex; flex-direction: column; position: relative; overflow: hidden; }
.progress-bar-wrap { height: 3px; background: rgba(255,255,255,.05); position: absolute; top: 0; left: 0; right: 0; z-index: 10; }
.progress-bar-fill { height: 100%; background: var(--gold); transition: width .55s cubic-bezier(.4,0,.2,1); }
.step-header { padding: 36px 90px 0; flex-shrink: 0; }
.step-meta { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.step-eyebrow { display: flex; align-items: center; gap: 8px; font-size: .65rem; letter-spacing: .18em; text-transform: uppercase; color: var(--gold); }
.step-eyebrow::before { content: ''; display: block; width: 20px; height: 1px; background: var(--gold); }
.step-counter { font-size: .68rem; color: var(--text-muted); letter-spacing: .06em; }
.step-counter span { color: var(--gold); }
.step-dots { display: flex; gap: 5px; margin-bottom: 24px; }
.step-dot { height: 3px; border-radius: 2px; background: rgba(201,168,76,.2); transition: all .4s; }
.step-dot.active { background: var(--gold); }
.step-dot.done { background: rgba(201,168,76,.45); }

/* Slider */
.slider-viewport { flex: 1; overflow: hidden; position: relative; }
.slides-track { display: flex; height: 100%; transition: transform .52s cubic-bezier(.4,0,.2,1); will-change: transform; }
.slide { min-width: 100%; height: 100%; padding: 4px 90px 20px; overflow-y: auto; scroll-behavior: smooth; }
.slide::-webkit-scrollbar { width: 3px; }
.slide::-webkit-scrollbar-track { background: transparent; }
.slide::-webkit-scrollbar-thumb { background: rgba(201,168,76,.2); border-radius: 2px; }
.slide-title { font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; font-weight: 300; color: var(--cream); margin-bottom: 24px; line-height: 1.2; }

/* Form elements */
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: .63rem; letter-spacing: .13em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 6px; }
.req { color: var(--gold); margin-left: 2px; }
.form-group input, .form-group select, .form-group textarea { width: 100%; background: rgba(255,255,255,.04); border: 1px solid rgba(201,168,76,.18); color: var(--cream); font-family: 'DM Sans', sans-serif; font-size: .875rem; font-weight: 300; padding: 10px 14px; outline: none; transition: border-color .2s, background .2s; appearance: none; border-radius: 1px; }
.form-group input::placeholder, .form-group textarea::placeholder { color: var(--text-muted); font-size: .8rem; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--gold); background: rgba(201,168,76,.04); }
.form-group select { cursor: pointer; }
.form-group select option { background: #1a1710; color: var(--cream); }
.form-group textarea { resize: vertical; min-height: 110px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

/* Checkboxes */
.check-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 4px; }
.check-item { display: flex; align-items: center; gap: 9px; padding: 10px 12px; border: 1px solid rgba(201,168,76,.15); cursor: pointer; transition: border-color .2s, background .2s; user-select: none; }
.check-item:hover { border-color: rgba(201,168,76,.4); }
.check-item.checked { border-color: var(--gold); background: rgba(201,168,76,.07); }
.check-box { width: 15px; height: 15px; flex-shrink: 0; border: 1px solid rgba(201,168,76,.35); display: flex; align-items: center; justify-content: center; transition: all .2s; }
.check-item.checked .check-box { border-color: var(--gold); background: var(--gold); }
.check-box svg { width: 9px; height: 9px; stroke: var(--dark); fill: none; stroke-width: 3; opacity: 0; transition: opacity .2s; }
.check-item.checked .check-box svg { opacity: 1; }
.check-label { font-size: .76rem; color: var(--text-light); }

/* Notice box */
.notice-box { padding: 14px 16px; margin-bottom: 18px; border: 1px solid rgba(201,168,76,.25); background: rgba(201,168,76,.06); display: flex; gap: 12px; align-items: flex-start; }
.notice-box .ni { font-size: .95rem; flex-shrink: 0; margin-top: 1px; }
.notice-box p { font-size: .76rem; color: var(--text-light); line-height: 1.65; }
.notice-box strong { color: var(--gold); font-weight: 500; font-size: .68rem; letter-spacing: .08em; text-transform: uppercase; display: block; margin-bottom: 4px; }

/* File upload */
.file-upload-area { border: 1px dashed rgba(201,168,76,.3); padding: 28px 20px; text-align: center; cursor: pointer; transition: border-color .2s, background .2s; }
.file-upload-area:hover { border-color: var(--gold); background: rgba(201,168,76,.04); }
.file-upload-area input[type="file"] { display: none; }
.upload-hint { font-size: .74rem; color: var(--text-muted); line-height: 1.65; margin-top: 8px; }
.upload-hint span { color: var(--gold); text-decoration: underline; }

/* Nav buttons */
.form-nav { padding: 18px 90px 28px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; border-top: 1px solid rgba(201,168,76,.08); }
.btn-back { font-family: 'DM Sans', sans-serif; font-size: .7rem; letter-spacing: .12em; text-transform: uppercase; color: var(--text-muted); background: none; border: 1px solid rgba(201,168,76,.2); cursor: pointer; padding: 11px 26px; transition: border-color .2s, color .2s; }
.btn-back:hover { border-color: var(--gold); color: var(--cream); }
.btn-back:disabled { opacity: .3; pointer-events: none; }
.btn-next { font-family: 'DM Sans', sans-serif; font-size: .7rem; letter-spacing: .12em; text-transform: uppercase; color: var(--dark); background: var(--gold); border: none; cursor: pointer; padding: 11px 32px; font-weight: 500; display: flex; align-items: center; gap: 8px; transition: background .2s, transform .15s; }
.btn-next:hover { background: var(--gold-light); transform: translateY(-1px); }
.btn-next svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2.2; }

/* Currency */
.currency-wrap { position: relative; }
.currency-wrap span { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: .8rem; pointer-events: none; }
.currency-wrap input { padding-left: 34px; }

/* Success */
.success-screen { display: none; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 60px 90px; height: 100%; }
.success-screen.show { display: flex; }
.success-icon { width: 68px; height: 68px; border: 1px solid var(--gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 28px; }
.success-icon svg { width: 30px; height: 30px; stroke: var(--gold); fill: none; stroke-width: 1.5; }
.success-title { font-family: 'Cormorant Garamond', serif; font-size: 2.2rem; font-weight: 300; color: var(--cream); margin-bottom: 14px; }
.success-sub { font-size: .875rem; color: var(--text-muted); max-width: 290px; line-height: 1.7; margin-bottom: 28px; }

@media(max-width:960px) {
  .hero-para-parceiros { grid-template-columns: 1fr; min-height: auto; }
  .hero-headline-col { order: 1; padding: 48px 28px; }
  .hero-form-col { order: 2; min-height: 100vh; }
  .step-header, .form-nav, .slide { padding-left: 28px; padding-right: 28px; }
}
</style>

<section class="hero-para-parceiros">
  <!-- LEFT: MULTI-STEP FORM -->
  <div class="hero-form-col">
    <div class="progress-bar-wrap">
      <div class="progress-bar-fill" id="progressFill" style="width:16.67%"></div>
    </div>
    
    <div class="step-header">
      <div class="step-meta">
        <div class="step-eyebrow" id="stepEyebrow">Dados da Empresa</div>
        <div class="step-counter">Etapa <span id="stepNum">1</span> de 6</div>
      </div>
      <div class="step-dots" id="stepDots"></div>
    </div>
    
    <form method="POST" action="/seja-parceiro" enctype="multipart/form-data" id="parceiroForm">
      <?php echo Csrf::campo(); ?>
      
      <div class="slider-viewport">
        <div class="slides-track" id="slidesTrack">

          <!-- Slide 1: Dados da Empresa -->
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
          
          <!-- Slide 2: Localização -->
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
          
          <!-- Slide 3: Especialidades -->
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
          
          <!-- Slide 4: Capacidade e Estrutura -->
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
          
          <!-- Slide 5: Portfólio -->
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
          
          <!-- Slide 6: Documentação e Preferências -->
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
          
        </div>
      </div>
      
      <!-- Success screen -->
      <div class="success-screen" id="successScreen">
        <div class="success-icon">
          <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="success-title">Cadastro enviado!</div>
        <p class="success-sub">Nossa equipe analisará seu perfil e retornará em até 48h com os próximos passos.</p>
      </div>
      
      <!-- Nav buttons -->
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
      <div class="hero-tag">Para Parceiros</div>
      <h1 class="hero-h1">Receba oportunidades<br><em>qualificadas.</em></h1>
      <p class="hero-sub">Faça parte da rede Lexus e receba demandas estruturadas, compatíveis com seu perfil e especialidade — com o Selo Vetriks de qualidade.</p>
      <div class="hero-stats">
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

// Build dots
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
  // Validação básica
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

// Checkbox toggle for specialties
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

updateUI();
</script>
