<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Formulário de criação de demanda — painel do cliente
 */
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('nav.abrir_demanda')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('demanda.dados_obra')); ?></p>
  </div>
  <a href="/cliente/demandas" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<form method="POST" action="/cliente/demandas/nova" enctype="multipart/form-data">
  <?php echo Csrf::campo(); ?>

  <!-- Dados da Obra -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('demanda.dados_obra')); ?></h2>

    <div class="form-group">
      <label>Título *</label>
      <input type="text" name="title" required placeholder="Ex: Reforma residencial completa"/>
    </div>

    <?php require __DIR__ . '/../_partials/categorias.php'; ?>
    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demanda.tipo_obra')); ?> *</label>
        <select name="category" required>
          <option value="">— Selecione a categoria —</option>
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

    <div class="form-group">
      <label><?php echo View::e(I18n::t('demanda.descricao')); ?> *</label>
      <textarea name="description" required placeholder="Descreva o escopo da obra, necessidades e expectativas..."></textarea>
    </div>
  </div>

  <!-- Localização -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('demanda.localizacao')); ?></h2>

    <div class="form-row">
      <?php
      $estadoSelecionado = '';
      $cidadeSelecionada = '';
      $obrigatorio = true;
      include __DIR__ . '/../_partials/campos-estado-cidade.php';
      ?>
    </div>

    <div class="form-group">
      <label>Endereço</label>
      <input type="text" name="address" placeholder="Rua, número, bairro (opcional)"/>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('demanda.metragem')); ?></label>
      <input type="number" name="area_sqm" step="0.01" min="0" placeholder="Ex: 150.00"/>
    </div>
  </div>

  <!-- Orçamento e Prazo -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('demanda.orcamento')); ?></h2>

    <div class="form-row">
      <div class="form-group">
        <label>Orçamento Mínimo (R$)</label>
        <input type="text" id="bmin_display" placeholder="R$ 0,00" inputmode="numeric" autocomplete="off"
               oninput="mascaraBRL(this,'budget_min')"/>
        <input type="hidden" name="budget_min" id="budget_min"/>
      </div>
      <div class="form-group">
        <label>Orçamento Máximo (R$)</label>
        <input type="text" id="bmax_display" placeholder="R$ 0,00" inputmode="numeric" autocomplete="off"
               oninput="mascaraBRL(this,'budget_max')"/>
        <input type="hidden" name="budget_max" id="budget_max"/>
      </div>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('demanda.prazo_desejado')); ?></label>
      <input type="date" name="desired_deadline"/>
    </div>
  </div>

  <!-- Opções -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px">Opções</h2>

    <div class="form-row">
      <div class="form-group">
        <label>
          <input type="checkbox" name="has_project" value="1"/>
          <?php echo View::e(I18n::t('demanda.tem_projeto')); ?>
        </label>
      </div>
      <div class="form-group">
        <label>
          <input type="checkbox" name="has_architect" value="1"/>
          <?php echo View::e(I18n::t('demanda.tem_arquiteto')); ?>
        </label>
      </div>
    </div>

    <div class="form-group">
      <label>
        <input type="checkbox" name="wants_multiple_proposals" value="1" checked/>
        <?php echo View::e(I18n::t('demanda.multiplas_prop')); ?>
      </label>
    </div>
  </div>

  <!-- Observações e Arquivos -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('demanda.observacoes')); ?></h2>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('demanda.observacoes')); ?></label>
      <textarea name="notes" placeholder="Informações adicionais, restrições, preferências..."></textarea>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('demanda.uploads')); ?></label>
      <input type="file" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.dwg,.doc,.docx,.xls,.xlsx"/>
      <small style="color:var(--text-muted);font-size:.75rem">PDF, imagens, DWG, DOC, XLS — máx. 10MB por arquivo</small>
    </div>
  </div>

  <div style="display:flex;gap:12px;justify-content:flex-end">
    <a href="/cliente/demandas" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.cancelar')); ?></a>
    <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.enviar')); ?></button>
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
