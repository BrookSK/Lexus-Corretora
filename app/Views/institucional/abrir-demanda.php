<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<section class="inst-hero">
  <span class="lbl reveal"><?php echo View::e(I18n::t('demanda.titulo')); ?></span>
  <h1 class="disp reveal d1">Conte-nos sobre sua <em>obra</em></h1>
  <p class="reveal d2">Preencha o formulário abaixo e nossa equipe entrará em contato para estruturar sua demanda.</p>
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

      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin-bottom:20px"><?php echo View::e(I18n::t('demanda.dados_pessoais')); ?></h3>
      <div class="form-row">
        <div class="form-group"><label><?php echo View::e(I18n::t('auth.nome')); ?></label><input type="text" name="name" required/></div>
        <div class="form-group"><label><?php echo View::e(I18n::t('auth.email')); ?></label><input type="email" name="email" required/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Telefone / WhatsApp</label><input type="tel" name="phone"/></div>
        <div class="form-group"><label>Empresa (opcional)</label><input type="text" name="company"/></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Senha de acesso <small style="font-weight:400;color:rgba(12,12,10,.4)">(mín. 8 caracteres)</small></label><input type="password" name="password" required minlength="8"/></div>
        <div class="form-group"><label>Confirmar senha</label><input type="password" name="password_confirm" required minlength="8"/></div>
      </div>
      <div class="form-row">
        <?php
        $estadoSelecionado = '';
        $cidadeSelecionada = '';
        $obrigatorio = true;
        include __DIR__ . '/../_partials/campos-estado-cidade.php';
        ?>
      </div>

      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px"><?php echo View::e(I18n::t('demanda.dados_obra')); ?></h3>
      <div class="form-group"><label>Título da Demanda</label><input type="text" name="title" required/></div>
      <?php require __DIR__ . '/../_partials/categorias.php'; ?>
      <div class="form-row">
        <div class="form-group">
          <label><?php echo View::e(I18n::t('demanda.tipo_obra')); ?></label>
          <select name="category" required>
            <option value="">— Selecione a categoria —</option>
            <?php foreach ($CATEGORIAS_NICHO as $cat): ?>
            <option value="<?php echo View::e($cat); ?>"><?php echo View::e($cat); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group"><label><?php echo View::e(I18n::t('demanda.urgencia')); ?></label>
          <select name="urgency"><option value="baixa">Baixa</option><option value="media" selected>Média</option><option value="alta">Alta</option><option value="critica">Crítica</option></select>
        </div>
      </div>
      <div class="form-group"><label>Endereço da obra</label><input type="text" name="address" placeholder="Rua, número, bairro"/></div>
      <div class="form-row">
        <div class="form-group"><label><?php echo View::e(I18n::t('demanda.metragem')); ?></label><input type="number" name="area_sqm" step="0.01"/></div>
        <div class="form-group"><label><?php echo View::e(I18n::t('demanda.prazo_desejado')); ?></label><input type="date" name="desired_deadline"/></div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label><?php echo View::e(I18n::t('demanda.orcamento')); ?> Estimado (mín)</label>
          <input type="text" id="bmin_display" placeholder="R$ 0,00" inputmode="numeric" autocomplete="off"
                 oninput="mascaraBRL(this,'budget_min')"/>
          <input type="hidden" name="budget_min" id="budget_min"/>
        </div>
        <div class="form-group">
          <label><?php echo View::e(I18n::t('demanda.orcamento')); ?> Estimado (máx)</label>
          <input type="text" id="bmax_display" placeholder="R$ 0,00" inputmode="numeric" autocomplete="off"
                 oninput="mascaraBRL(this,'budget_max')"/>
          <input type="hidden" name="budget_max" id="budget_max"/>
        </div>
      </div>
      <div class="form-group"><label><?php echo View::e(I18n::t('demanda.descricao')); ?></label><textarea name="description" required></textarea></div>
      <div class="form-group"><label><?php echo View::e(I18n::t('demanda.observacoes')); ?></label><textarea name="notes"></textarea></div>

      <h3 style="font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold);margin:32px 0 20px"><?php echo View::e(I18n::t('demanda.uploads')); ?></h3>
      <div class="form-group"><label>Projeto / Planta / Memorial / Fotos</label><input type="file" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.dwg,.dxf,.doc,.docx,.zip"/></div>

      <div class="form-submit">
        <button type="submit" class="btn-cta"><?php echo View::e(I18n::t('geral.enviar')); ?></button>
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
</script>
<script>
(function(){
  var f = document.querySelector('form[action="/abrir-demanda"]');
  if(!f) return;
  f.addEventListener('submit', function(e){
    var p = f.querySelector('[name="password"]');
    var c = f.querySelector('[name="password_confirm"]');
    if(p.value !== c.value){ e.preventDefault(); c.setCustomValidity('As senhas não coincidem.'); c.reportValidity(); }
    else { c.setCustomValidity(''); }
  });
})();
</script>
