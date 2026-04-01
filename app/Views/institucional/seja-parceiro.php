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

      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:20px"><?php echo View::e(I18n::t('parceiro.dados_empresa')); ?></h3>
      <div class="form-row">
        <div class="form-group"><label>Nome / Razão Social</label><input type="text" name="name" required/></div>
        <div class="form-group"><label>Nome Fantasia</label><input type="text" name="trade_name"/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label><?php echo View::e(I18n::t('parceiro.tipo')); ?></label>
          <select name="type"><option value="construtora">Construtora</option><option value="arquiteto">Arquiteto</option><option value="engenheiro">Engenheiro</option><option value="empreiteira">Empreiteira</option><option value="prestador">Prestador</option><option value="fornecedor">Fornecedor</option></select>
        </div>
        <div class="form-group"><label>CPF/CNPJ</label><input type="text" name="document"/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label><?php echo View::e(I18n::t('auth.email')); ?></label><input type="email" name="email" required/></div>
        <div class="form-group"><label>WhatsApp</label><input type="tel" name="whatsapp"/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Site</label><input type="text" name="website" placeholder="www.suaempresa.com.br"/></div>
        <div class="form-group"><label>Instagram</label><input type="text" name="instagram"/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Senha de acesso <small style="font-weight:400;color:rgba(12,12,10,.4)">(mín. 8 caracteres)</small></label><input type="password" name="password" required minlength="8"/></div>
        <div class="form-group"><label>Confirmar senha</label><input type="password" name="password_confirm" required minlength="8"/></div>
      </div>

      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px"><?php echo View::e(I18n::t('parceiro.dados_prof')); ?></h3>
      <?php require __DIR__ . '/../_partials/categorias.php'; ?>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('parceiro.especialidades')); ?></label>
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
      <div class="form-row">
        <?php
        $estadoSelecionado = '';
        $cidadeSelecionada = '';
        $obrigatorio = false;
        include __DIR__ . '/../_partials/campos-estado-cidade.php';
        ?>
      </div>
      <div class="form-group"><label><?php echo View::e(I18n::t('parceiro.tempo_mercado')); ?></label><input type="number" name="years_in_market"/></div>
      <div class="form-group"><label>Descrição da Empresa</label><textarea name="description"></textarea></div>

      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px"><?php echo View::e(I18n::t('parceiro.qualificacao')); ?></h3>
      <div class="form-group"><label>Portfólio (PDF ou Fotos)</label><input type="file" name="portfolio[]" multiple accept=".pdf,.jpg,.jpeg,.png,.webp"/><small style="font-size:.75rem;color:rgba(12,12,10,.4);display:block;margin-top:4px">Envie 1 PDF ou no mínimo 6 fotos de trabalhos realizados</small></div>
      <div class="form-group">
        <label>Certidão de CNPJ ativo</label>
        <div style="background:rgba(184,148,90,.06);border:1px solid rgba(184,148,90,.15);padding:12px;margin-bottom:8px;font-size:.82rem;line-height:1.5">
          Para emitir, acesse: <a href="https://solucoes.receita.fazenda.gov.br/servicos/cnpjreva/cnpjreva_solicitacao.asp" target="_blank" rel="noopener" style="color:var(--gold)">Receita Federal</a>. Insira seu CNPJ, faça o download do cartão e anexe abaixo.
        </div>
        <input type="file" name="certidao_cnpj" accept=".pdf,.jpg,.jpeg,.png"/>
      </div>

      <div class="form-submit">
        <button type="submit" class="btn-cta"><?php echo View::e(I18n::t('geral.enviar')); ?></button>
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
</script>
<script>
(function(){
  var f = document.querySelector('form[action="/seja-parceiro"]');
  if(!f) return;
  f.addEventListener('submit', function(e){
    var p = f.querySelector('[name="password"]');
    var c = f.querySelector('[name="password_confirm"]');
    if(p.value !== c.value){ e.preventDefault(); c.setCustomValidity('As senhas não coincidem.'); c.reportValidity(); }
    else { c.setCustomValidity(''); }
  });
})();
</script>
