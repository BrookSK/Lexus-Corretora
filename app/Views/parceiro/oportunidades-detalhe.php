<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Detalhe de uma oportunidade — painel do parceiro
 * Variáveis: $oportunidade (array), $demanda (array)
 */

$urgenciaBadge = [
    'baixa' => 'badge-gray', 'media' => 'badge-blue', 'alta' => 'badge-gold', 'critica' => 'badge-red',
];
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e($demanda['code']); ?> — <?php echo View::e($demanda['title']); ?></h1>
    <p class="section-subtitle">
      <span class="badge <?php echo $urgenciaBadge[$demanda['urgency'] ?? 'media'] ?? 'badge-gray'; ?>">
        <?php echo View::e(ucfirst($demanda['urgency'] ?? 'media')); ?>
      </span>
    </p>
  </div>
  <a href="/parceiro/oportunidades" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<!-- Briefing -->
<div class="cards-grid" style="grid-template-columns:1fr 1fr">
  <div class="card">
    <h3 class="card-title">Briefing</h3>
    <table style="width:100%;font-size:.88rem;margin-top:12px">
      <tr><td style="color:var(--text-muted);padding:6px 0;width:40%"><?php echo View::e(I18n::t('demanda.tipo_obra')); ?></td><td style="padding:6px 0"><?php echo View::e($demanda['work_type'] ?? '—'); ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.metragem')); ?></td><td style="padding:6px 0"><?php echo $demanda['area_sqm'] ? View::e(number_format((float)$demanda['area_sqm'], 2, ',', '.')) . ' m²' : '—'; ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.tem_projeto')); ?></td><td style="padding:6px 0"><?php echo ($demanda['has_project'] ?? 0) ? View::e(I18n::t('geral.sim')) : View::e(I18n::t('geral.nao')); ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.tem_arquiteto')); ?></td><td style="padding:6px 0"><?php echo ($demanda['has_architect'] ?? 0) ? View::e(I18n::t('geral.sim')) : View::e(I18n::t('geral.nao')); ?></td></tr>
    </table>
  </div>

  <div class="card">
    <h3 class="card-title"><?php echo View::e(I18n::t('demanda.localizacao')); ?> & <?php echo View::e(I18n::t('demanda.orcamento')); ?></h3>
    <table style="width:100%;font-size:.88rem;margin-top:12px">
      <tr><td style="color:var(--text-muted);padding:6px 0;width:40%">Cidade</td><td style="padding:6px 0"><?php echo View::e(($demanda['city'] ?? '') . ', ' . ($demanda['state'] ?? '')); ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.orcamento')); ?></td>
        <td style="padding:6px 0">
          <?php if (!empty($demanda['budget_min']) || !empty($demanda['budget_max'])): ?>
            R$ <?php echo View::e(number_format((float)($demanda['budget_min'] ?? 0), 2, ',', '.')); ?>
            — R$ <?php echo View::e(number_format((float)($demanda['budget_max'] ?? 0), 2, ',', '.')); ?>
          <?php else: ?>
            —
          <?php endif; ?>
        </td>
      </tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.prazo_desejado')); ?></td><td style="padding:6px 0"><?php echo $demanda['desired_deadline'] ? View::e(date('d/m/Y', strtotime($demanda['desired_deadline']))) : '—'; ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.urgencia')); ?></td>
        <td style="padding:6px 0">
          <span class="badge <?php echo $urgenciaBadge[$demanda['urgency'] ?? 'media'] ?? 'badge-gray'; ?>">
            <?php echo View::e(ucfirst($demanda['urgency'] ?? 'media')); ?>
          </span>
        </td>
      </tr>
    </table>
  </div>
</div>

<!-- Escopo / Descrição -->
<?php if (!empty($demanda['description'])): ?>
<div class="card" style="margin-bottom:24px">
  <h3 class="card-title">Escopo</h3>
  <p style="margin-top:12px;font-size:.88rem;line-height:1.6;white-space:pre-wrap"><?php echo View::e($demanda['description']); ?></p>
</div>
<?php endif; ?>

<!-- Notas da Lexus -->
<?php if (!empty($oportunidade['notes'])): ?>
<div class="card" style="margin-bottom:24px">
  <h3 class="card-title">Notas da Lexus</h3>
  <p style="margin-top:12px;font-size:.88rem;line-height:1.6;white-space:pre-wrap"><?php echo View::e($oportunidade['notes']); ?></p>
</div>
<?php endif; ?>

<!-- Anexos -->
<?php if (!empty($demanda['arquivos'])): ?>
<div class="card" style="margin-bottom:24px">
  <h3 class="card-title"><?php echo View::e(I18n::t('demanda.uploads')); ?></h3>
  <div style="margin-top:12px;display:flex;flex-direction:column;gap:8px">
    <?php foreach ($demanda['arquivos'] as $arq): ?>
    <a href="<?php echo View::e($arq['file_path']); ?>" target="_blank" style="font-size:.88rem;color:var(--gold);text-decoration:none">
      <?php echo View::e($arq['name']); ?>
      <?php if (!empty($arq['file_size'])): ?>
        <span style="color:var(--text-muted);font-size:.75rem">(<?php echo View::e(number_format($arq['file_size'] / 1024, 0, ',', '.')); ?> KB)</span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- Ações -->
<div class="card" style="padding:32px">
  <h3 class="card-title" style="margin-bottom:20px"><?php echo View::e(I18n::t('geral.acoes')); ?></h3>
  <div style="display:flex;gap:12px;flex-wrap:wrap">
    <?php if (($oportunidade['status'] ?? '') !== 'proposta_enviada' && ($oportunidade['status'] ?? '') !== 'recusado'): ?>
    <form method="POST" action="/parceiro/oportunidades/<?php echo View::e((string)$oportunidade['id']); ?>/interesse" style="display:inline">
      <?php echo Csrf::campo(); ?>
      <input type="hidden" name="type" value="aceitar"/>
      <button type="submit" class="btn btn-primary">Tenho Interesse</button>
    </form>
    <form method="POST" action="/parceiro/oportunidades/<?php echo View::e((string)$oportunidade['id']); ?>/interesse" style="display:inline">
      <?php echo Csrf::campo(); ?>
      <input type="hidden" name="type" value="recusar"/>
      <button type="submit" class="btn btn-danger">Recusar</button>
    </form>
    <?php endif; ?>
    <?php if (($oportunidade['status'] ?? '') === 'interessado'): ?>
    <a href="/parceiro/propostas/criar/<?php echo View::e((string)($demanda['id'] ?? '')); ?>" class="btn btn-primary">Enviar Proposta</a>
    <?php endif; ?>
  </div>
</div>
