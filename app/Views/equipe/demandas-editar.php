<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('demandas.editar_demanda')); ?></h1>
    <p class="section-subtitle"><?php echo View::e($demanda['code']); ?> — <?php echo View::e($demanda['title']); ?></p>
  </div>
  <a href="/equipe/demandas/<?php echo (int)$demanda['id']; ?>" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/demandas/<?php echo (int)$demanda['id']; ?>/editar">
    <?php echo Csrf::campo(); ?>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.titulo')); ?> *</label>
        <input type="text" name="title" value="<?php echo View::e($demanda['title']); ?>" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('geral.status')); ?></label>
        <select name="status">
          <?php foreach (['novo','em_triagem','em_estruturacao','pronto_repasse','distribuido','aguardando_respostas','recebendo_propostas','em_curadoria','apresentado_cliente','em_negociacao','contrato_formalizacao','fechado_ganho','fechado_perda','pausado','cancelado'] as $s): ?>
          <option value="<?php echo $s; ?>" <?php echo ($demanda['status'] ?? '') === $s ? 'selected' : ''; ?>><?php echo ucfirst(str_replace('_', ' ', $s)); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.origem')); ?></label>
        <select name="origin">
          <?php foreach (['cliente','parceiro','arquiteto','equipe','lead','importacao'] as $o): ?>
          <option value="<?php echo $o; ?>" <?php echo ($demanda['origin'] ?? '') === $o ? 'selected' : ''; ?>><?php echo ucfirst($o); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('sidebar.clientes')); ?></label>
        <select name="cliente_id">
          <option value="">— <?php echo View::e(I18n::t('geral.selecione')); ?> —</option>
          <?php if (!empty($clientes)): foreach ($clientes as $c): ?>
          <option value="<?php echo (int)$c['id']; ?>" <?php echo ((int)($demanda['cliente_id'] ?? 0)) === (int)$c['id'] ? 'selected' : ''; ?>><?php echo View::e($c['name']); ?></option>
          <?php endforeach; endif; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('geral.descricao')); ?> *</label>
      <textarea name="description" required rows="4"><?php echo View::e($demanda['description'] ?? ''); ?></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.categoria')); ?></label>
        <input type="text" name="category" value="<?php echo View::e($demanda['category'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.tipo_obra')); ?></label>
        <input type="text" name="work_type" value="<?php echo View::e($demanda['work_type'] ?? ''); ?>"/>
      </div>
    </div>

    <div class="form-row">
      <?php
      $estadoSelecionado = $demanda['state'] ?? '';
      $cidadeSelecionada = $demanda['city'] ?? '';
      $obrigatorio = false;
      include __DIR__ . '/../_partials/campos-estado-cidade.php';
      ?>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.orcamento_min')); ?></label>
        <input type="number" name="budget_min" step="0.01" value="<?php echo View::e($demanda['budget_min'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.orcamento_max')); ?></label>
        <input type="number" name="budget_max" step="0.01" value="<?php echo View::e($demanda['budget_max'] ?? ''); ?>"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.urgencia')); ?></label>
        <select name="urgency">
          <?php foreach (['baixa','media','alta','critica'] as $u): ?>
          <option value="<?php echo $u; ?>" <?php echo ($demanda['urgency'] ?? '') === $u ? 'selected' : ''; ?>><?php echo ucfirst($u); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.complexidade')); ?></label>
        <select name="complexity">
          <?php foreach (['simples','moderada','complexa','muito_complexa'] as $cx): ?>
          <option value="<?php echo $cx; ?>" <?php echo ($demanda['complexity'] ?? '') === $cx ? 'selected' : ''; ?>><?php echo ucfirst(str_replace('_', ' ', $cx)); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.prazo_desejado')); ?></label>
        <input type="date" name="desired_deadline" value="<?php echo View::e($demanda['desired_deadline'] ?? ''); ?>"/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('demandas.prioridade')); ?></label>
        <select name="priority">
          <?php foreach (['baixa','normal','alta','urgente'] as $pr): ?>
          <option value="<?php echo $pr; ?>" <?php echo ($demanda['priority'] ?? '') === $pr ? 'selected' : ''; ?>><?php echo ucfirst($pr); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('demandas.notas_internas')); ?></label>
      <textarea name="internal_notes" rows="3"><?php echo View::e($demanda['internal_notes'] ?? ''); ?></textarea>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
