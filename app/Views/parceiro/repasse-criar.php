<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
require __DIR__ . '/../_partials/categorias.php';
?>
<div class="section-header">
  <div>
    <h1 class="section-title">Nova Indicação de Demanda</h1>
    <p class="section-subtitle">Indique um cliente ou oportunidade para a equipe Lexus</p>
  </div>
  <a href="/parceiro/repasse" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<form method="POST" action="/parceiro/repasse/nova" enctype="multipart/form-data">
  <?php echo Csrf::campo(); ?>

  <!-- Dados do Cliente Indicado -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px">Dados do Cliente Indicado</h2>
    <div class="form-row">
      <div class="form-group">
        <label>Nome do Cliente *</label>
        <input type="text" name="client_nome" placeholder="Nome completo ou razão social" required/>
      </div>
      <div class="form-group">
        <label>WhatsApp / Telefone</label>
        <input type="tel" name="client_telefone" placeholder="(11) 99999-9999"/>
      </div>
    </div>
  </div>

  <!-- Dados da Demanda -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px">Dados da Demanda</h2>

    <div class="form-group">
      <label>Título / Descrição Resumida *</label>
      <input type="text" name="title" required placeholder="Ex: Reforma de apartamento 120m²"/>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demanda.tipo_obra')); ?> *</label>
        <select name="category" required>
          <option value="">— Selecione —</option>
          <?php foreach ($CATEGORIAS_NICHO as $cat): ?>
          <option value="<?php echo View::e($cat); ?>"><?php echo View::e($cat); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demanda.urgencia')); ?></label>
        <select name="urgency">
          <option value="baixa">Baixa</option>
          <option value="media" selected>Média</option>
          <option value="alta">Alta</option>
          <option value="critica">Crítica</option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demanda.metragem')); ?></label>
        <input type="number" name="area_sqm" step="0.01" placeholder="Ex: 120"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demanda.prazo_desejado')); ?></label>
        <input type="date" name="desired_deadline"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Orçamento Estimado (mín)</label>
        <input type="text" id="bmin_display" placeholder="R$ 0,00" inputmode="numeric" autocomplete="off"
               oninput="mascaraBRL(this,'budget_min')"/>
        <input type="hidden" name="budget_min" id="budget_min"/>
      </div>
      <div class="form-group">
        <label>Orçamento Estimado (máx)</label>
        <input type="text" id="bmax_display" placeholder="R$ 0,00" inputmode="numeric" autocomplete="off"
               oninput="mascaraBRL(this,'budget_max')"/>
        <input type="hidden" name="budget_max" id="budget_max"/>
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

    <div class="form-group">
      <label>Endereço da Obra</label>
      <input type="text" name="address" placeholder="Rua, número, bairro"/>
    </div>

    <div class="form-group">
      <label>Descrição Detalhada</label>
      <textarea name="description" rows="4" placeholder="Descreva o escopo, necessidades específicas, situação atual..."></textarea>
    </div>

    <div class="form-row">
      <div class="form-group" style="flex-direction:row;align-items:center;gap:10px">
        <input type="checkbox" name="has_project" value="1" id="has_project"/>
        <label for="has_project" style="margin:0"><?php echo View::e(I18n::t('demanda.tem_projeto')); ?></label>
      </div>
      <div class="form-group" style="flex-direction:row;align-items:center;gap:10px">
        <input type="checkbox" name="has_architect" value="1" id="has_architect"/>
        <label for="has_architect" style="margin:0"><?php echo View::e(I18n::t('demanda.tem_arquiteto')); ?></label>
      </div>
    </div>
  </div>

  <!-- Anexos -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px">Documentos e Projetos</h2>
    <div class="form-group">
      <label>Arquivos (plantas, fotos, projetos)</label>
      <input type="file" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.dwg,.dxf,.doc,.docx,.xls,.xlsx,.zip"/>
      <small style="color:var(--text-muted);font-size:.75rem">PDF, imagens, DWG, DOC, XLS — máx. 10MB por arquivo</small>
    </div>
  </div>

  <div style="display:flex;gap:12px;justify-content:flex-end">
    <a href="/parceiro/repasse" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.cancelar')); ?></a>
    <button type="submit" class="btn btn-primary">Enviar Indicação</button>
  </div>
</form>

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
