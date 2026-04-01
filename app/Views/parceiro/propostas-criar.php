<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Formulário de criação de proposta — painel do parceiro
 * Variáveis: $demanda (array — a demanda para a qual a proposta será enviada)
 */
?>
<div class="section-header">
  <div>
    <h1 class="section-title">Enviar Proposta</h1>
    <p class="section-subtitle">Demanda <?php echo View::e($demanda['code']); ?> — <?php echo View::e($demanda['title']); ?></p>
  </div>
  <a href="/parceiro/oportunidades" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<!-- Resumo da Demanda -->
<div class="card" style="margin-bottom:24px;padding:24px">
  <h3 class="card-title">Resumo da Demanda</h3>
  <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-top:12px;font-size:.88rem">
    <div>
      <span style="color:var(--text-muted);font-size:.72rem;text-transform:uppercase;letter-spacing:.08em"><?php echo View::e(I18n::t('demanda.tipo_obra')); ?></span>
      <div style="margin-top:4px"><?php echo View::e($demanda['work_type'] ?? '—'); ?></div>
    </div>
    <div>
      <span style="color:var(--text-muted);font-size:.72rem;text-transform:uppercase;letter-spacing:.08em"><?php echo View::e(I18n::t('demanda.localizacao')); ?></span>
      <div style="margin-top:4px"><?php echo View::e(($demanda['city'] ?? '') . ', ' . ($demanda['state'] ?? '')); ?></div>
    </div>
    <div>
      <span style="color:var(--text-muted);font-size:.72rem;text-transform:uppercase;letter-spacing:.08em"><?php echo View::e(I18n::t('demanda.orcamento')); ?></span>
      <div style="margin-top:4px">
        <?php if (!empty($demanda['budget_min']) || !empty($demanda['budget_max'])): ?>
          R$ <?php echo View::e(number_format((float)($demanda['budget_min'] ?? 0), 0, ',', '.')); ?>
          — R$ <?php echo View::e(number_format((float)($demanda['budget_max'] ?? 0), 0, ',', '.')); ?>
        <?php else: ?>
          —
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<form method="POST" action="/parceiro/propostas/nova" enctype="multipart/form-data">
  <?php echo Csrf::campo(); ?>
  <input type="hidden" name="demanda_id" value="<?php echo (int)($demanda['id'] ?? 0); ?>"/>

  <!-- Valores -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px">Valores e Prazo</h2>

    <div class="form-row">
      <div class="form-group">
        <label>Valor da Proposta *</label>
        <input type="number" name="amount" step="0.01" min="0" required placeholder="0.00"/>
      </div>
      <div class="form-group">
        <label>Moeda</label>
        <select name="currency_code">
          <option value="BRL">BRL — Real</option>
          <option value="USD">USD — Dólar</option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Prazo de Execução (dias) *</label>
        <input type="number" name="deadline_days" min="1" required placeholder="Ex: 90"/>
      </div>
      <div class="form-group">
        <label>Validade da Proposta (dias)</label>
        <input type="number" name="validity_days" min="1" value="30" placeholder="30"/>
      </div>
    </div>
  </div>

  <!-- Detalhes -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('geral.detalhes')); ?></h2>

    <div class="form-group">
      <label><?php echo View::e(I18n::t('demanda.descricao')); ?> *</label>
      <textarea name="description" required placeholder="Descreva o escopo da proposta, etapas, materiais inclusos..."></textarea>
    </div>

    <div class="form-group">
      <label>Diferenciais</label>
      <textarea name="differentials" placeholder="O que diferencia sua proposta? Experiência, garantias, equipe..."></textarea>
    </div>

    <div class="form-group">
      <label>Condições</label>
      <textarea name="conditions" placeholder="Condições de pagamento, reajustes, exclusões..."></textarea>
    </div>
  </div>

  <!-- Anexos -->
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px">Anexos</h2>

    <div class="form-group">
      <label>Arquivos da Proposta</label>
      <input type="file" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"/>
      <small style="color:var(--text-muted);font-size:.75rem">PDF, imagens, DOC, XLS — máx. 10MB por arquivo</small>
    </div>
  </div>

  <div style="display:flex;gap:12px;justify-content:flex-end">
    <a href="/parceiro/oportunidades" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.cancelar')); ?></a>
    <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.enviar')); ?> Proposta</button>
  </div>
</form>
