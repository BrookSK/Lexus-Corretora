<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('demandas.nova_demanda')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('demandas.subtitulo_criar')); ?></p>
  </div>
  <a href="/equipe/demandas" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/demandas/nova">
    <?php echo Csrf::campo(); ?>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.titulo')); ?> *</label>
        <input type="text" name="title" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.origem')); ?></label>
        <select name="origin">
          <option value="cliente">Cliente</option>
          <option value="parceiro">Parceiro</option>
          <option value="arquiteto">Arquiteto</option>
          <option value="equipe">Equipe</option>
          <option value="lead">Lead</option>
          <option value="importacao">Importação</option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('sidebar.clientes')); ?></label>
        <select name="cliente_id">
          <option value="">— <?php echo View::e(I18n::t('geral.selecione')); ?> —</option>
          <?php if (!empty($clientes)): foreach ($clientes as $c): ?>
          <option value="<?php echo (int)$c['id']; ?>"><?php echo View::e($c['name']); ?></option>
          <?php endforeach; endif; ?>
        </select>
      </div>
      <div class="form-group">
        <?php require __DIR__ . '/../_partials/categorias.php'; ?>
        <label><?php echo View::e(I18n::t('demandas.categoria')); ?></label>
        <select name="category">
          <option value="">— Selecione —</option>
          <?php foreach ($CATEGORIAS_NICHO as $cat): ?>
          <option value="<?php echo View::e($cat); ?>"><?php echo View::e($cat); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('geral.descricao')); ?> *</label>
      <textarea name="description" required rows="4"></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.subcategoria')); ?></label>
        <input type="text" name="subcategory"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.tipo_obra')); ?></label>
        <input type="text" name="work_type"/>
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
      <label><?php echo View::e(I18n::t('geral.endereco')); ?></label>
      <input type="text" name="address"/>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.area_m2')); ?></label>
        <input type="number" name="area_sqm" step="0.01"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.fase_atual')); ?></label>
        <input type="text" name="current_phase"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.prazo_desejado')); ?></label>
        <input type="date" name="desired_deadline"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.urgencia')); ?></label>
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
        <label><?php echo View::e(I18n::t('demandas.orcamento_min')); ?></label>
        <input type="number" name="budget_min" step="0.01"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.orcamento_max')); ?></label>
        <input type="number" name="budget_max" step="0.01"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.complexidade')); ?></label>
        <select name="complexity">
          <option value="simples">Simples</option>
          <option value="moderada" selected>Moderada</option>
          <option value="complexa">Complexa</option>
          <option value="muito_complexa">Muito Complexa</option>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.tipo_contratacao')); ?></label>
        <input type="text" name="hiring_type"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.tem_projeto')); ?></label>
        <select name="has_project">
          <option value="0"><?php echo View::e(I18n::t('geral.nao')); ?></option>
          <option value="1"><?php echo View::e(I18n::t('geral.sim')); ?></option>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.tem_arquiteto')); ?></label>
        <select name="has_architect">
          <option value="0"><?php echo View::e(I18n::t('geral.nao')); ?></option>
          <option value="1"><?php echo View::e(I18n::t('geral.sim')); ?></option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('demandas.observacoes')); ?></label>
      <textarea name="notes" rows="3"></textarea>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('demandas.notas_internas')); ?></label>
      <textarea name="internal_notes" rows="3"></textarea>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
