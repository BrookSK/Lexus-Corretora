<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('comissoes.nova_comissao')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('comissoes.subtitulo_criar')); ?></p>
  </div>
  <a href="/equipe/comissoes" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/comissoes">
    <?php echo Csrf::campo(); ?>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('sidebar.demandas')); ?> *</label>
        <select name="demanda_id" required>
          <option value="">— <?php echo View::e(I18n::t('geral.selecione')); ?> —</option>
          <?php if (!empty($demandas)): foreach ($demandas as $d): ?>
          <option value="<?php echo (int)$d['id']; ?>"><?php echo View::e($d['code'] . ' — ' . $d['title']); ?></option>
          <?php endforeach; endif; ?>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('sidebar.parceiros')); ?> *</label>
        <select name="parceiro_id" required>
          <option value="">— <?php echo View::e(I18n::t('geral.selecione')); ?> —</option>
          <?php if (!empty($parceiros)): foreach ($parceiros as $pa): ?>
          <option value="<?php echo (int)$pa['id']; ?>"><?php echo View::e($pa['name']); ?></option>
          <?php endforeach; endif; ?>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('sidebar.contratos')); ?></label>
        <select name="contrato_id">
          <option value="">— <?php echo View::e(I18n::t('geral.selecione')); ?> —</option>
          <?php if (!empty($contratos)): foreach ($contratos as $ct): ?>
          <option value="<?php echo (int)$ct['id']; ?>">#<?php echo (int)$ct['id']; ?> — <?php echo I18n::formatarMoeda($ct['amount']); ?></option>
          <?php endforeach; endif; ?>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('sidebar.clientes')); ?></label>
        <select name="cliente_id">
          <option value="">— <?php echo View::e(I18n::t('geral.selecione')); ?> —</option>
          <?php if (!empty($clientes)): foreach ($clientes as $c): ?>
          <option value="<?php echo (int)$c['id']; ?>"><?php echo View::e($c['name']); ?></option>
          <?php endforeach; endif; ?>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('comissoes.valor_base')); ?> *</label>
        <input type="number" name="base_amount" step="0.01" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('comissoes.percentual')); ?> *</label>
        <input type="number" name="commission_pct" step="0.01" required/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('comissoes.valor_comissao')); ?> *</label>
        <input type="number" name="commission_amount" step="0.01" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('comissoes.data_prevista')); ?></label>
        <input type="date" name="expected_date"/>
      </div>
    </div>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('demandas.observacoes')); ?></label>
      <textarea name="notes" rows="3"></textarea>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
