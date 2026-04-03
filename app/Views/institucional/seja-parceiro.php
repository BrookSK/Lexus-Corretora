<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<section class="inst-hero">
  <span class="lbl reveal"><?php echo View::e(I18n::t('parceiro.titulo')); ?></span>
  <h1 class="disp reveal d1">Faça parte da rede <em>Lexus</em></h1>
  <p class="reveal d2">Cadastre-se e receba oportunidades qualificadas compatíveis com seu perfil.</p>
</section>

<section class="form-section">
  <div class="form-container">
    <?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash-msg flash-<?php echo View::e($_SESSION['flash']['type']); ?>" style="margin-bottom:24px">
      <?php echo View::e($_SESSION['flash']['message']); ?>
    </div>
    <?php unset($_SESSION['flash']); endif; ?>

    <form method="POST" action="/seja-parceiro" enctype="multipart/form-data">
      <?php echo Csrf::campo(); ?>

      <!-- DADOS DA EMPRESA -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:20px">Dados da Empresa</h3>
      <div class="form-row">
        <div class="form-group"><label>Nome / Razão Social *</label><input type="text" name="name" required/></div>
        <div class="form-group"><label>Nome Fantasia</label><input type="text" name="trade_name"/></div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Tipo de Parceiro *</label>
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
        <div class="form-group"><label>CPF/CNPJ *</label><input type="text" name="document" required/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>E-mail *</label><input type="email" name="email" required/></div>
        <div class="form-group"><label>WhatsApp *</label><input type="tel" name="whatsapp" required/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Telefone Fixo</label><input type="tel" name="phone"/></div>
        <div class="form-group"><label>CREA/CAU</label><input type="text" name="crea_cau" placeholder="Ex: CREA-SP 123456"/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Site</label><input type="text" name="website" placeholder="www.suaempresa.com.br"/></div>
        <div class="form-group"><label>Instagram</label><input type="text" name="instagram" placeholder="@suaempresa"/></div>
      </div>
      <div class="form-group"><label>LinkedIn</label><input type="text" name="linkedin" placeholder="linkedin.com/company/suaempresa"/></div>
      
      <div class="form-row">
        <div class="form-group"><label>Senha de acesso <small style="font-weight:400;color:rgba(12,12,10,.4)">(mín. 8 caracteres)</small></label><input type="password" name="password" required minlength="8"/></div>
        <div class="form-group"><label>Confirmar senha</label><input type="password" name="password_confirm" required minlength="8"/></div>
      </div>

      <!-- LOCALIZAÇÃO E ATUAÇÃO -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px">Localização e Área de Atuação</h3>
      <div class="form-row">
        <?php
        $estadoSelecionado = '';
        $cidadeSelecionada = '';
        $obrigatorio = true;
        include __DIR__ . '/../_partials/campos-estado-cidade.php';
        ?>
      </div>
      <div class="form-group"><label>Endereço Completo</label><input type="text" name="address" placeholder="Rua, número, bairro, CEP"/></div>

      <!-- ESPECIALIDADES E SERVIÇOS -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px">Especialidades e Serviços</h3>
      <?php require __DIR__ . '/../_partials/categorias.php'; ?>
      <div class="form-group">
        <label>Especialidades *</label>
        <div class="mc-wrap" id="mc-esp-sp">
          <button type="button" class="mc-toggle" onclick="mcOpen('mc-esp-sp')">
            <span class="mc-label" id="mc-esp-sp-lbl">Selecione suas especialidades</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="mc-panel" id="mc-esp-sp-panel">
            <input type="text" class="mc-search" placeholder="Buscar..." oninput="mcFilter('mc-esp-sp',this.value)">
            <div class="mc-list" id="mc-esp-sp-list">
              <?php foreach ($CATEGORIAS_NICHO as $cat): ?>
              <label class="mc-item">
                <input type="checkbox" name="specialties[]" value="<?php echo View::e($cat); ?>" onchange="mcUpdate('mc-esp-sp')">
                <?php echo View::e($cat); ?>
              </label>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- CAPACIDADE E ESTRUTURA -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px">Capacidade e Estrutura</h3>
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
          <input type="text" id="ticket_min_display" placeholder="R$ 0,00" inputmode="numeric" autocomplete="off"
                 oninput="mascaraBRL(this,'average_ticket_min')"/>
          <input type="hidden" name="average_ticket_min" id="average_ticket_min"/>
        </div>
        <div class="form-group">
          <label>Ticket Médio Máximo (R$)</label>
          <input type="text" id="ticket_max_display" placeholder="R$ 0,00" inputmode="numeric" autocomplete="off"
                 oninput="mascaraBRL(this,'average_ticket_max')"/>
          <input type="hidden" name="average_ticket_max" id="average_ticket_max"/>
        </div>
      </div>

      <div class="form-group">
        <label>Descrição da Empresa *</label>
        <textarea name="description" required rows="4" placeholder="Conte sobre sua empresa, experiência, diferenciais..."></textarea>
      </div>

      <!-- PORTFÓLIO E QUALIFICAÇÃO -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px">Portfólio e Qualificação</h3>
      
      <div style="background:rgba(184,148,90,.08);border:1px solid rgba(184,148,90,.2);padding:16px;margin-bottom:16px;border-radius:6px">
        <strong style="color:var(--gold);font-size:.9rem">📸 Portfólio (OPCIONAL, mas recomendado)</strong>
        <p style="margin:8px 0 0;font-size:.85rem;line-height:1.6;color:rgba(12,12,10,.7)">
          Envie fotos de obras similares para aumentar suas chances de receber oportunidades. 
          Quanto melhor seu portfólio, maior a confiança dos clientes.
        </p>
      </div>

      <div class="form-group">
        <label>Portfólio (PDF ou Fotos)</label>
        <input type="file" name="portfolio[]" multiple accept=".pdf,.jpg,.jpeg,.png,.webp"/>
        <small style="font-size:.75rem;color:rgba(12,12,10,.4);display:block;margin-top:4px">
          Envie 1 PDF ou no mínimo 6 fotos de trabalhos realizados
        </small>
      </div>

      <div class="form-group"><label>Link do Portfólio Online</label><input type="text" name="portfolio_url" placeholder="https://..."/></div>

      <!-- DOCUMENTAÇÃO -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px">Documentação</h3>
      
      <div class="form-group">
        <label>Certidão de CNPJ Ativo</label>
        <div style="background:rgba(184,148,90,.06);border:1px solid rgba(184,148,90,.15);padding:12px;margin-bottom:8px;font-size:.82rem;line-height:1.5">
          Para emitir, acesse: <a href="https://solucoes.receita.fazenda.gov.br/servicos/cnpjreva/cnpjreva_solicitacao.asp" target="_blank" rel="noopener" style="color:var(--gold)">Receita Federal</a>. 
          Insira seu CNPJ, faça o download do cartão e anexe abaixo.
        </div>
        <input type="file" name="certidao_cnpj" accept=".pdf,.jpg,.jpeg,.png"/>
      </div>

      <div class="form-group">
        <label>Outros Documentos (Certificações, Licenças, etc.)</label>
        <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png"/>
      </div>

      <!-- PREFERÊNCIAS DE REPASSE -->
      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px">Preferências de Repasse</h3>
      
      <div class="form-group">
        <label style="display:flex;align-items:center;gap:8px">
          <input type="checkbox" name="accepts_referral" value="1" checked/>
          Aceito receber demandas por repasse de outros parceiros
        </label>
        <small style="font-size:.75rem;color:rgba(12,12,10,.4);display:block;margin-top:4px">
          Você poderá receber oportunidades indicadas por outros profissionais da rede
        </small>
      </div>

      <div class="form-group">
        <label>Comissão de Repasse (%)</label>
        <input type="number" name="referral_commission_pct" min="0" max="100" step="0.1" placeholder="Ex: 10" value="10"/>
        <small style="font-size:.75rem;color:rgba(12,12,10,.4);display:block;margin-top:4px">
          Percentual que você está disposto a pagar em comissão por demandas repassadas
        </small>
      </div>

      <div class="form-submit">
        <button type="submit" class="btn-cta">Enviar Cadastro</button>
      </div>
    </form>
  </div>
</section>

<style>
.mc-wrap{position:relative;z-index:1}.mc-wrap.mc-open{z-index:9999;isolation:isolate}
.mc-toggle{width:100%;display:flex;align-items:center;justify-content:space-between;background:#fff;border:1px solid #d0c9b8;border-radius:4px;padding:9px 12px;cursor:pointer;font-size:.85rem;color:#1a1a16;text-align:left}
.mc-toggle:hover{border-color:#b8945a}
.mc-panel{display:none;position:absolute;top:calc(100% + 4px);left:0;min-width:100%;width:max-content;max-width:360px;background:#fff;border:1px solid #d0c9b8;border-radius:4px;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,.15);transform:translateZ(0)}
.mc-panel.open{display:block}
.mc-search{width:100%;border:none;border-bottom:1px solid #e8e0d4;background:transparent;padding:8px 12px;font-size:.82rem;color:#1a1a16;outline:none;box-sizing:border-box}
.mc-list{max-height:220px;overflow-y:auto;padding:4px 0}
.mc-item{display:flex;align-items:center;gap:8px;padding:7px 14px;font-size:.83rem;color:#1a1a16;cursor:pointer;user-select:none}
.mc-item:hover{background:rgba(184,148,90,.08)}
.mc-item input[type=checkbox]{accent-color:#b8945a;width:14px;height:14px;flex-shrink:0}
.mc-item.mc-hidden{display:none}
</style>

<script>
function mcOpen(id){var w=document.getElementById(id),p=document.getElementById(id+'-panel'),o=p.classList.contains('open');document.querySelectorAll('.mc-panel.open').forEach(function(x){x.classList.remove('open')});document.querySelectorAll('.mc-wrap.mc-open').forEach(function(x){x.classList.remove('mc-open')});if(!o){p.classList.add('open');if(w)w.classList.add('mc-open');var s=p.querySelector('.mc-search');if(s){s.value='';mcFilter(id,'');s.focus()}}}
function mcUpdate(id){var c=document.querySelectorAll('#'+id+' input[type=checkbox]:checked'),l=document.getElementById(id+'-lbl');if(l)l.textContent=c.length?c.length+' selecionada(s)':'Selecione suas especialidades'}
function mcFilter(id,q){q=(q||'').toLowerCase();document.querySelectorAll('#'+id+'-list .mc-item').forEach(function(i){i.classList.toggle('mc-hidden',q!==''&&i.textContent.toLowerCase().indexOf(q)===-1)})}
document.addEventListener('click',function(e){if(!e.target.closest('.mc-wrap')){document.querySelectorAll('.mc-panel.open').forEach(function(p){p.classList.remove('open')});document.querySelectorAll('.mc-wrap.mc-open').forEach(function(w){w.classList.remove('mc-open')})}});

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
</script>

<script>
(function(){
  var f = document.querySelector('form[action="/seja-parceiro"]');
  if(!f) return;
  f.addEventListener('submit', function(e){
    var p = f.querySelector('[name="password"]');
    var c = f.querySelector('[name="password_confirm"]');
    if(p && c && p.value !== c.value){ 
      e.preventDefault(); 
      c.setCustomValidity('As senhas não coincidem.'); 
      c.reportValidity(); 
    } else if(c) { 
      c.setCustomValidity(''); 
    }
  });
})();
</script>
