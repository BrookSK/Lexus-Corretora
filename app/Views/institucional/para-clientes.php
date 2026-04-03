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
/* Estilos específicos da página para-clientes com formulário multi-step */
.hero-para-clientes { min-height: 100vh; display: grid; grid-template-columns: 1fr 1fr; padding-top: 64px; }
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
  .hero-para-clientes { grid-template-columns: 1fr; min-height: auto; }
  .hero-headline-col { order: 1; padding: 48px 28px; }
  .hero-form-col { order: 2; min-height: 100vh; }
  .step-header, .form-nav, .slide { padding-left: 28px; padding-right: 28px; }
}
</style>

<section class="hero-para-clientes">
  <!-- LEFT: MULTI-STEP FORM -->
  <div class="hero-form-col">
    <div class="progress-bar-wrap">
      <div class="progress-bar-fill" id="progressFill" style="width:12.5%"></div>
    </div>
    
    <div class="step-header">
      <div class="step-meta">
        <div class="step-eyebrow" id="stepEyebrow">Dados Pessoais</div>
        <div class="step-counter">Etapa <span id="stepNum">1</span> de 8</div>
      </div>
      <div class="step-dots" id="stepDots"></div>
    </div>
    
    <form method="POST" action="/abrir-demanda" enctype="multipart/form-data" id="demandaForm">
      <?php echo Csrf::campo(); ?>
      
      <div class="slider-viewport">
        <div class="slides-track" id="slidesTrack">
          
          <!-- Slide 1: Dados Pessoais -->
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
          
          <!-- Slide 2: Informações Básicas -->
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
          
          <!-- Slide 3: Tipo de Serviço -->
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
          
          <!-- Slide 4: Detalhamento Técnico -->
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
              <label style="display:flex;align-items:center;gap:8px">
                <input type="checkbox" name="needs_debris_removal" value="1"/>
                Necessita remoção de entulho
              </label>
            </div>
          </div>
          
          <!-- Slide 5: Escopo -->
          <div class="slide">
            <div class="slide-title">Descreva seu<br>projeto.</div>
            <div class="form-group">
              <label>Descrição detalhada <span class="req">*</span></label>
              <textarea name="description" required style="min-height:180px" placeholder="Descreva o escopo da obra, necessidades específicas, situação atual, expectativas… quanto mais detalhe, melhor a qualidade dos orçamentos recebidos."></textarea>
            </div>
          </div>
          
          <!-- Slide 6: Prazo e Urgência -->
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
          
          <!-- Slide 7: Financeiro + Preferências -->
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
          
          <!-- Slide 8: Evidências visuais -->
          <div class="slide">
            <div class="slide-title">Evidências<br>visuais.</div>
            <div class="notice-box">
              <span class="ni">⚠️</span>
              <p><strong>Crítico para bom orçamento</strong>Fotos, vídeos e plantas são essenciais para que os parceiros elaborem orçamentos precisos. Quanto mais informação visual você fornecer, maior a qualidade das propostas.</p>
            </div>
            <div class="form-group">
              <label>Upload de fotos / vídeos / plantas (recomendado)</label>
              <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                <input type="file" id="fileInput" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.mp4,.mov,.dwg,.dxf,.doc,.docx,.zip"/>
                <div style="font-size:1.4rem;margin-bottom:8px">📎</div>
                <p class="upload-hint">Arraste arquivos aqui ou <span>clique para selecionar</span><br>JPG, PNG, MP4, PDF, DWG • até 10MB por arquivo</p>
              </div>
            </div>
          </div>
          
        </div>
      </div>
      
      <!-- Success screen -->
      <div class="success-screen" id="successScreen">
        <div class="success-icon">
          <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="success-title">Demanda enviada!</div>
        <p class="success-sub">Nossa equipe receberá sua solicitação e retornará em até 24h com os próximos passos.</p>
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
      <div class="hero-tag">Para Clientes</div>
      <h1 class="hero-h1">Sua obra merece<br><em>decisões<br>melhores.</em></h1>
      <p class="hero-sub">Descubra como a Lexus simplifica o processo de encontrar o parceiro ideal para o seu projeto — com curadoria, transparência e suporte em cada etapa.</p>
      <div class="hero-stats">
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
  // Validação básica
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

// Checkbox toggle for services
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

// Checkbox toggle for preferences
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

updateUI();
</script>
