<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('contratos.novo_contrato')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('contratos.subtitulo_criar')); ?></p>
  </div>
  <a href="/equipe/contratos" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="card">
  <form method="POST" action="/equipe/contratos">
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
        <label><?php echo View::e(I18n::t('sidebar.propostas')); ?></label>
        <select name="proposta_id">
          <option value="">— <?php echo View::e(I18n::t('geral.selecione')); ?> —</option>
          <?php if (!empty($propostas)): foreach ($propostas as $p): ?>
          <option value="<?php echo (int)$p['id']; ?>">#<?php echo (int)$p['id']; ?> — <?php echo I18n::formatarMoeda($p['amount']); ?></option>
          <?php endforeach; endif; ?>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('sidebar.clientes')); ?> *</label>
        <select name="cliente_id" required>
          <option value="">— <?php echo View::e(I18n::t('geral.selecione')); ?> —</option>
          <?php if (!empty($clientes)): foreach ($clientes as $c): ?>
          <option value="<?php echo (int)$c['id']; ?>"><?php echo View::e($c['name']); ?></option>
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
        <label><?php echo View::e(I18n::t('propostas.valor')); ?> *</label>
        <input type="number" name="amount" step="0.01" required/>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('contratos.data_formalizacao')); ?></label>
        <input type="date" name="formalized_at"/>
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
