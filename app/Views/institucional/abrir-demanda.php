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
      <div class="form-row">
        <div class="form-group"><label><?php echo View::e(I18n::t('demanda.tipo_obra')); ?></label>
          <select name="work_type"><option value="">Selecione</option><option value="construcao">Construção</option><option value="reforma">Reforma</option><option value="ampliacao">Ampliação</option><option value="acabamento">Acabamento</option><option value="projeto">Projeto</option><option value="outro">Outro</option></select>
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
        <div class="form-group"><label><?php echo View::e(I18n::t('demanda.orcamento')); ?> (mín)</label><input type="number" name="budget_min" step="0.01"/></div>
        <div class="form-group"><label><?php echo View::e(I18n::t('demanda.orcamento')); ?> (máx)</label><input type="number" name="budget_max" step="0.01"/></div>
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
