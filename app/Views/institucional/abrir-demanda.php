<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<section class="inst-hero">
  <span class="lbl reveal"><?php echo View::e(I18n::t('demanda.titulo')); ?></span>
  <h1 class="disp reveal d1">Conte-nos sobre sua <em>obra</em></h1>
  <p class="reveal d2">Preencha o formulário completo para recebermos orçamentos precisos e qualificados.</p>
</section>

<section class="form-section">
  <div class="form-container">
    <?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash-msg flash-<?php echo View::e($_SESSION['flash']['type']); ?>" style="margin-bottom:24px">
      <?php echo View::e($_SESSION['flash']['message']); ?>
    </div>
    <?php unset($_SESSION['flash']); endif; ?>

    <form method="POST" action="/abrir-demanda" enctype="multipart/form-data">
      <?php echo Csrf::campo(); ?>

      <!-- DADOS PESSOAIS -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:20px">Dados Pessoais</h3>
      <div class="form-row">
        <div class="form-group"><label>Nome Completo *</label><input type="text" name="name" required/></div>
        <div class="form-group"><label>E-mail *</label><input type="email" name="email" required/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Telefone / WhatsApp *</label><input type="tel" name="phone" required/></div>
        <div class="form-group"><label>Empresa *</label><input type="text" name="company" required/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Senha de acesso <small style="font-weight:400;color:rgba(12,12,10,.4)">(mín. 8 caracteres)</small></label><input type="password" name="password" required minlength="8"/></div>
        <div class="form-group"><label>Confirmar senha</label><input type="password" name="password_confirm" required minlength="8"/></div>
      </div>

      <!-- INFORMAÇÕES BÁSICAS -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px">Informações Básicas</h3>
      <div class="form-group"><label>Título da Demanda *</label><input type="text" name="title" required placeholder="Ex: Reforma residencial completa"/></div>
      
      <?php require __DIR__ . '/../_partials/categorias.php'; ?>
      <div class="form-row">
        <div class="form-group">
          <label>Tipo de Imóvel *</label>
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
          <label>Tipo de Obra *</label>
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

      <div class="form-group"><label>Endereço / Bairro *</label><input type="text" name="address" required placeholder="Rua, número, bairro"/></div>

      <div class="form-group">
        <label>Tipo de Serviço (pode selecionar múltiplos) *</label>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-top:8px">
          <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" name="services[]" value="eletrica"/> Elétrica</label>
          <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" name="services[]" value="hidraulica"/> Hidráulica</label>
          <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" name="services[]" value="pintura"/> Pintura</label>
          <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" name="services[]" value="piso_revestimento"/> Piso/Revestimento</label>
          <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" name="services[]" value="marcenaria"/> Marcenaria</label>
          <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" name="services[]" value="obra_completa"/> Obra Completa</label>
        </div>
      </div>

      <!-- DETALHAMENTO TÉCNICO -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px">Detalhamento Técnico</h3>
      <div class="form-row">
        <div class="form-group"><label>Área Aproximada (m²) *</label><input type="number" name="area_sqm" step="0.01" placeholder="Ex: 150.00" required/></div>
        <div class="form-group"><label>Quantidade de Ambientes *</label><input type="number" name="rooms_count" placeholder="Ex: 5" required/></div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Idade do Imóvel *</label>
          <select name="property_age" required>
            <option value="">— Selecione —</option>
            <option value="novo">Novo (até 5 anos)</option>
            <option value="medio">Médio (5-15 anos)</option>
            <option value="antigo">Antigo (15-30 anos)</option>
            <option value="muito_antigo">Muito Antigo (30+ anos)</option>
          </select>
        </div>
        <div class="form-group">
          <label>Situação Atual *</label>
          <select name="current_situation" required>
            <option value="">— Selecione —</option>
            <option value="vazio">Vazio</option>
            <option value="habitado">Habitado</option>
            <option value="em_obra">Em Obra</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Existe Projeto? *</label>
          <select name="has_project" required>
            <option value="">— Selecione —</option>
            <option value="nao">Não</option>
            <option value="arquitetonico">Arquitetônico</option>
            <option value="estrutural">Estrutural</option>
            <option value="completo">Completo</option>
          </select>
        </div>
        <div class="form-group">
          <label>Necessita Demolição? *</label>
          <select name="needs_demolition" required>
            <option value="">— Selecione —</option>
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

      <!-- ESCOPO DO SERVIÇO -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px">Escopo do Serviço</h3>
      <div class="form-group">
        <label>Descrição Detalhada *</label>
        <textarea name="description" required rows="5" placeholder="Descreva o escopo da obra, necessidades específicas, situação atual, expectativas..."></textarea>
      </div>

      <!-- PRAZO E URGÊNCIA -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px">Prazo e Urgência</h3>
      <div class="form-row">
        <div class="form-group"><label>Quando quer iniciar? *</label><input type="date" name="desired_start_date" required/></div>
        <div class="form-group"><label>Prazo Desejado *</label><input type="date" name="desired_deadline" required/></div>
      </div>

      <div class="form-group">
        <label>Nível de Urgência *</label>
        <select name="urgency" required>
          <option value="">— Selecione —</option>
          <option value="baixa">Baixa - Posso aguardar</option>
          <option value="media">Média - Algumas semanas</option>
          <option value="alta">Alta - Preciso em breve</option>
          <option value="critica">Crítica - Urgente</option>
        </select>
      </div>

      <!-- EXPECTATIVA FINANCEIRA -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px">Expectativa Financeira</h3>
      <div class="form-row">
        <div class="form-group">
          <label>Faixa de Orçamento (mínimo) *</label>
          <input type="text" id="bmin_display" placeholder="R$ 0,00" inputmode="numeric" autocomplete="off"
                 oninput="mascaraBRL(this,'budget_min')" required/>
          <input type="hidden" name="budget_min" id="budget_min"/>
        </div>
        <div class="form-group">
          <label>Faixa de Orçamento (máximo) *</label>
          <input type="text" id="bmax_display" placeholder="R$ 0,00" inputmode="numeric" autocomplete="off"
                 oninput="mascaraBRL(this,'budget_max')" required/>
          <input type="hidden" name="budget_max" id="budget_max"/>
        </div>
      </div>

      <!-- PREFERÊNCIAS ADICIONAIS -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px">Preferências Adicionais</h3>
      <div class="form-row">
        <div class="form-group">
          <label style="display:flex;align-items:center;gap:8px">
            <input type="checkbox" name="wants_invoice" value="1"/>
            Preciso de Nota Fiscal
          </label>
        </div>
        <div class="form-group">
          <label style="display:flex;align-items:center;gap:8px">
            <input type="checkbox" name="needs_art_rrt" value="1"/>
            Preciso de ART/RRT
          </label>
        </div>
      </div>

      <div class="form-group">
        <label>Preferência de Contratação *</label>
        <select name="hiring_preference" required>
          <option value="">— Selecione —</option>
          <option value="menor_preco">Menor Preço</option>
          <option value="melhor_qualidade">Melhor Qualidade</option>
          <option value="equilibrio">Equilíbrio Preço/Qualidade</option>
        </select>
      </div>

      <div class="form-group"><label>Observações Adicionais *</label><textarea name="notes" rows="3" placeholder="Informações adicionais, restrições, preferências..." required></textarea></div>

      <!-- EVIDÊNCIAS VISUAIS -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px">Evidências Visuais</h3>
      <div style="background:rgba(184,148,90,.08);border:1px solid rgba(184,148,90,.2);padding:16px;margin-bottom:16px;border-radius:6px">
        <strong style="color:var(--gold);font-size:.9rem">⚠️ CRÍTICO PARA BOM ORÇAMENTO</strong>
        <p style="margin:8px 0 0;font-size:.85rem;line-height:1.6;color:rgba(12,12,10,.7)">
          Fotos, vídeos e plantas são essenciais para que os parceiros possam elaborar orçamentos precisos. 
          Quanto mais informações visuais você fornecer, melhor será a qualidade das propostas recebidas.
        </p>
      </div>
      <div class="form-group">
        <label style="display:flex;align-items:center;justify-content:space-between">
          <span>Upload de Fotos / Vídeos / Plantas (OPCIONAL, mas recomendado)</span>
          <span id="fileCount" style="font-size:.8rem;color:var(--gold);font-weight:500"></span>
        </label>
        <div style="position:relative">
          <input type="file" name="files[]" id="filesInput" multiple accept=".pdf,.jpg,.jpeg,.png,.mp4,.mov,.dwg,.dxf,.doc,.docx,.zip" style="display:none"/>
          <button type="button" onclick="document.getElementById('filesInput').click()" style="width:100%;padding:16px;border:2px dashed rgba(184,148,90,.3);background:rgba(184,148,90,.05);color:var(--gold);font-size:.9rem;cursor:pointer;border-radius:4px;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .2s">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
              <circle cx="8.5" cy="8.5" r="1.5"/>
              <polyline points="21 15 16 10 5 21"/>
            </svg>
            Clique para selecionar arquivos (múltiplos)
          </button>
        </div>
        <div id="filesPreview" style="margin-top:12px;display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:8px"></div>
        <small style="font-size:.75rem;color:rgba(12,12,10,.4);display:block;margin-top:8px">
          📎 Você pode selecionar múltiplos arquivos de uma vez • PDF, imagens, vídeos, DWG, DOC — máx. 10MB por arquivo
        </small>
      </div>

      <div class="form-submit">
        <button type="submit" class="btn-cta">Enviar Solicitação</button>
      </div>
    </form>
  </div>
</section>

<script>
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

// Preview de arquivos
(function(){
  var input = document.getElementById('filesInput');
  var preview = document.getElementById('filesPreview');
  var count = document.getElementById('fileCount');
  if(!input || !preview || !count) return;
  
  input.addEventListener('change', function(){
    var files = Array.from(input.files);
    count.textContent = files.length ? files.length + ' arquivo(s) selecionado(s)' : '';
    preview.innerHTML = '';
    
    files.forEach(function(file){
      var item = document.createElement('div');
      item.style.cssText = 'position:relative;border:1px solid rgba(184,148,90,.2);border-radius:4px;overflow:hidden;background:rgba(184,148,90,.05);padding:8px;display:flex;flex-direction:column;gap:6px';
      
      if(file.type.startsWith('image/')){
        var img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.style.cssText = 'width:100%;height:80px;object-fit:cover;border-radius:2px';
        item.appendChild(img);
      } else if(file.type.startsWith('video/')){
        var video = document.createElement('video');
        video.src = URL.createObjectURL(file);
        video.style.cssText = 'width:100%;height:80px;object-fit:cover;border-radius:2px';
        item.appendChild(video);
      } else {
        var icon = document.createElement('div');
        icon.innerHTML = '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--gold);margin:12px auto"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>';
        icon.style.cssText = 'display:flex;align-items:center;justify-content:center;height:80px';
        item.appendChild(icon);
      }
      
      var name = document.createElement('div');
      name.textContent = file.name.length > 18 ? file.name.substring(0,15) + '...' : file.name;
      name.style.cssText = 'font-size:.7rem;color:rgba(12,12,10,.6);text-align:center;word-break:break-all';
      item.appendChild(name);
      
      var size = document.createElement('div');
      size.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
      size.style.cssText = 'font-size:.65rem;color:rgba(12,12,10,.4);text-align:center';
      item.appendChild(size);
      
      preview.appendChild(item);
    });
  });
})();
</script>
<script>
(function(){
  var f = document.querySelector('form[action="/abrir-demanda"]');
  if(!f) return;
  f.addEventListener('submit', function(e){
    // Validar senhas
    var p = f.querySelector('[name="password"]');
    var c = f.querySelector('[name="password_confirm"]');
    if(p && c && p.value !== c.value){ 
      e.preventDefault(); 
      c.setCustomValidity('As senhas não coincidem.'); 
      c.reportValidity(); 
      return;
    } else if(c) { 
      c.setCustomValidity(''); 
    }
    
    // Validar serviços (pelo menos um)
    var services = f.querySelectorAll('[name="services[]"]:checked');
    if(services.length === 0){
      e.preventDefault();
      alert('Por favor, selecione pelo menos um tipo de serviço.');
      return;
    }
    
    // Validar todos os campos obrigatórios
    var requiredFields = f.querySelectorAll('[required]');
    var firstInvalid = null;
    var invalidCount = 0;
    
    requiredFields.forEach(function(field){
      var isValid = true;
      
      if(field.type === 'checkbox' || field.type === 'radio'){
        // Para checkboxes/radios, verificar se pelo menos um do grupo está marcado
        var name = field.name;
        var group = f.querySelectorAll('[name="' + name + '"]');
        var hasChecked = Array.from(group).some(function(el){ return el.checked; });
        isValid = hasChecked;
      } else if(field.tagName === 'SELECT'){
        isValid = field.value !== '' && field.value !== null;
      } else {
        isValid = field.value.trim() !== '';
      }
      
      if(!isValid){
        invalidCount++;
        if(!firstInvalid) firstInvalid = field;
        field.style.borderColor = '#ef4444';
        field.style.borderWidth = '2px';
      } else {
        field.style.borderColor = '';
        field.style.borderWidth = '';
      }
    });
    
    if(invalidCount > 0){
      e.preventDefault();
      alert('Por favor, preencha todos os campos obrigatórios (' + invalidCount + ' campo(s) pendente(s)).');
      if(firstInvalid){
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(function(){ firstInvalid.focus(); }, 500);
      }
      return;
    }
  });
  
  // Remover destaque vermelho quando o usuário começar a preencher
  var allInputs = f.querySelectorAll('input, select, textarea');
  allInputs.forEach(function(input){
    input.addEventListener('input', function(){
      if(this.style.borderColor === 'rgb(239, 68, 68)'){
        this.style.borderColor = '';
        this.style.borderWidth = '';
      }
    });
    input.addEventListener('change', function(){
      if(this.style.borderColor === 'rgb(239, 68, 68)'){
        this.style.borderColor = '';
        this.style.borderWidth = '';
      }
    });
  });
})();
</script>
