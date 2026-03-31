<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('qualificacao.avaliar')); ?></h1>
    <p class="section-subtitle"><?php echo View::e($qualificacao['parceiro_nome'] ?? ''); ?></p>
  </div>
  <a href="/equipe/qualificacao" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<!-- Info do parceiro -->
<div class="cards-grid" style="margin-bottom:24px">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar.parceiros')); ?></div>
    <div class="card-title"><?php echo View::e($qualificacao['parceiro_nome'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('parceiros.tipo')); ?></div>
    <div class="card-title"><?php echo View::e($qualificacao['parceiro_type'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.status')); ?></div>
    <div class="card-title"><span class="badge badge-gold"><?php echo View::e($qualificacao['status']); ?></span></div>
  </div>
  <div class="card">
    <div class="card-label">Score Atual</div>
    <div class="card-value"><?php echo (int)($qualificacao['overall_score'] ?? 0); ?></div>
  </div>
</div>

<div class="card">
  <form method="POST" action="/equipe/qualificacao/<?php echo (int)$qualificacao['id']; ?>/salvar">
    <?php echo Csrf::campo(); ?>

    <!-- Critérios de avaliação -->
    <h3 style="font-size:.95rem;font-weight:500;margin-bottom:20px"><?php echo View::e(I18n::t('qualificacao.criterios')); ?></h3>

    <?php
    $criterios = $itens ?? [
        ['criterio' => 'Portfólio e experiência', 'max_score' => 10],
        ['criterio' => 'Documentação e certificações', 'max_score' => 10],
        ['criterio' => 'Capacidade técnica', 'max_score' => 10],
        ['criterio' => 'Referências e reputação', 'max_score' => 10],
        ['criterio' => 'Estrutura e equipe', 'max_score' => 10],
        ['criterio' => 'Atendimento e comunicação', 'max_score' => 10],
        ['criterio' => 'Cumprimento de prazos', 'max_score' => 10],
        ['criterio' => 'Relação custo-benefício', 'max_score' => 10],
    ];
    ?>

    <?php foreach ($criterios as $i => $crit): ?>
    <div style="padding:16px 0;border-bottom:1px solid var(--border)">
      <div class="form-row">
        <div class="form-group" style="margin:0">
          <label><?php echo View::e($crit['criterio']); ?></label>
          <input type="hidden" name="criterios[<?php echo $i; ?>][criterio]" value="<?php echo View::e($crit['criterio']); ?>"/>
          <input type="hidden" name="criterios[<?php echo $i; ?>][max_score]" value="<?php echo (int)$crit['max_score']; ?>"/>
        </div>
        <div class="form-group" style="margin:0">
          <label>Score (0-<?php echo (int)$crit['max_score']; ?>)</label>
          <input type="number" name="criterios[<?php echo $i; ?>][score]" min="0" max="<?php echo (int)$crit['max_score']; ?>" value="<?php echo (int)($crit['score'] ?? 0); ?>" required/>
        </div>
      </div>
      <div class="form-group" style="margin-top:8px;margin-bottom:0">
        <label><?php echo View::e(I18n::t('demandas.observacoes')); ?></label>
        <input type="text" name="criterios[<?php echo $i; ?>][notes]" value="<?php echo View::e($crit['notes'] ?? ''); ?>"/>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- Parecer -->
    <div class="form-group" style="margin-top:24px">
      <label><?php echo View::e(I18n::t('qualificacao.parecer')); ?> *</label>
      <textarea name="parecer" rows="4" required><?php echo View::e($qualificacao['parecer'] ?? ''); ?></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label><?php echo View::e(I18n::t('qualificacao.resultado')); ?> *</label>
        <select name="status" required>
          <option value="em_analise" <?php echo ($qualificacao['status'] ?? '') === 'em_analise' ? 'selected' : ''; ?>>Em Análise</option>
          <option value="aprovado" <?php echo ($qualificacao['status'] ?? '') === 'aprovado' ? 'selected' : ''; ?>>Aprovado</option>
          <option value="reprovado" <?php echo ($qualificacao['status'] ?? '') === 'reprovado' ? 'selected' : ''; ?>>Reprovado</option>
          <option value="revisao" <?php echo ($qualificacao['status'] ?? '') === 'revisao' ? 'selected' : ''; ?>>Revisão</option>
        </select>
      </div>
      <div class="form-group">
        <label><?php echo View::e(I18n::t('qualificacao.conceder_vetriks')); ?></label>
        <select name="vetriks_granted">
          <option value="0"><?php echo View::e(I18n::t('geral.nao')); ?></option>
          <option value="1" <?php echo ($qualificacao['vetriks_granted'] ?? false) ? 'selected' : ''; ?>><?php echo View::e(I18n::t('geral.sim')); ?></option>
        </select>
      </div>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
