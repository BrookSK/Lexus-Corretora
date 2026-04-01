<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
$statusBadge = match($demanda['status'] ?? '') {
    'fechado_ganho' => 'badge-green',
    'fechado_perda', 'cancelado' => 'badge-red',
    'novo', 'em_triagem' => 'badge-blue',
    'pausado' => 'badge-gray',
    default => 'badge-gold',
};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e($demanda['code']); ?> — <?php echo View::e($demanda['title']); ?></h1>
    <p class="section-subtitle">
      <span class="badge <?php echo $statusBadge; ?>"><?php echo View::e($demanda['status']); ?></span>
    </p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="/equipe/demandas/<?php echo (int)$demanda['id']; ?>/editar" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.editar')); ?></a>
    <a href="/equipe/distribuicao/<?php echo (int)$demanda['id']; ?>" class="btn btn-primary"><?php echo View::e(I18n::t('demandas.distribuir')); ?></a>
    <a href="/equipe/demandas" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
  </div>
</div>

<div class="cards-grid">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar.clientes')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['cliente_nome'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('demandas.origem')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['origin']); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('demandas.urgencia')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['urgency'] ?? 'media'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('demandas.complexidade')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['complexity'] ?? 'moderada'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.cidade')); ?> / <?php echo View::e(I18n::t('geral.estado')); ?></div>
    <div class="card-title"><?php echo View::e(($demanda['city'] ?? '') . ' / ' . ($demanda['state'] ?? '')); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('demandas.area_m2')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['area_sqm'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('demandas.orcamento')); ?></div>
    <div class="card-title">
      <?php if (!empty($demanda['budget_min']) || !empty($demanda['budget_max'])): ?>
        <?php echo I18n::formatarMoeda($demanda['budget_min'] ?? 0); ?> — <?php echo I18n::formatarMoeda($demanda['budget_max'] ?? 0); ?>
      <?php else: ?>—<?php endif; ?>
    </div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('demandas.prazo_desejado')); ?></div>
    <div class="card-title"><?php echo View::e($demanda['desired_deadline'] ?? '—'); ?></div>
  </div>
</div>

<div class="card" style="margin-bottom:24px">
  <div class="card-label" style="margin-bottom:8px"><?php echo View::e(I18n::t('geral.descricao')); ?></div>
  <p style="font-size:.88rem;line-height:1.6"><?php echo nl2br(View::e($demanda['description'] ?? '')); ?></p>
</div>

<?php if (!empty($demanda['internal_notes'])): ?>
<div class="card" style="margin-bottom:24px;border-left:3px solid var(--gold)">
  <div class="card-label" style="margin-bottom:8px"><?php echo View::e(I18n::t('demandas.notas_internas')); ?></div>
  <p style="font-size:.88rem;line-height:1.6"><?php echo nl2br(View::e($demanda['internal_notes'])); ?></p>
</div>
<?php endif; ?>

<!-- Anexos -->
<?php if (!empty($demanda['arquivos'])): ?>
<div class="card" style="margin-bottom:24px">
  <div class="card-label" style="margin-bottom:12px"><?php echo View::e(I18n::t('demanda.uploads')); ?></div>
  <div style="display:flex;flex-direction:column;gap:8px">
    <?php foreach ($demanda['arquivos'] as $arq): ?>
    <a href="/<?php echo View::e(ltrim($arq['file_path'], '/')); ?>" target="_blank"
       style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--gold);text-decoration:none">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      <?php echo View::e($arq['name']); ?>
      <?php if (!empty($arq['file_size'])): ?>
        <span style="color:var(--text-muted);font-size:.75rem">(<?php echo View::e(number_format($arq['file_size'] / 1024, 0, ',', '.')); ?> KB)</span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- Distribuição -->
<?php if (!empty($distribuicoes)): ?>
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('demandas.distribuicao')); ?></h2></div>
</div>
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('sidebar.parceiros')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th><?php echo View::e(I18n::t('geral.enviado_em')); ?></th>
        <th><?php echo View::e(I18n::t('geral.respondido_em')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($distribuicoes as $dist): ?>
      <tr>
        <td><?php echo View::e($dist['parceiro_nome'] ?? '—'); ?></td>
        <td><span class="badge badge-gold"><?php echo View::e($dist['status']); ?></span></td>
        <td><?php echo View::e($dist['sent_at'] ?? '—'); ?></td>
        <td><?php echo View::e($dist['responded_at'] ?? '—'); ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<!-- Propostas -->
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('sidebar.propostas')); ?></h2></div>
  <?php if (!empty($propostas) && count($propostas) > 1): ?>
  <a href="/equipe/propostas/comparar?demanda_id=<?php echo (int)$demanda['id']; ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('propostas.comparar')); ?></a>
  <?php endif; ?>
</div>
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('sidebar.parceiros')); ?></th>
        <th><?php echo View::e(I18n::t('propostas.valor')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th><?php echo View::e(I18n::t('geral.data')); ?></th>
        <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($propostas)): ?>
      <tr><td colspan="5"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($propostas as $p): ?>
      <tr>
        <td><?php echo View::e($p['parceiro_nome'] ?? '—'); ?></td>
        <td><?php echo I18n::formatarMoeda($p['amount']); ?></td>
        <td><span class="badge badge-gold"><?php echo View::e($p['status']); ?></span></td>
        <td><?php echo View::e($p['created_at']); ?></td>
        <td><a href="/equipe/propostas/<?php echo (int)$p['id']; ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.ver')); ?></a></td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<!-- Timeline -->
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('geral.timeline')); ?></h2></div>
</div>
<div class="card">
  <?php if (empty($timeline)): ?>
    <p style="color:var(--text-muted);font-size:.88rem"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></p>
  <?php else: foreach ($timeline as $evento): ?>
    <div style="padding:12px 0;border-bottom:1px solid var(--border)">
      <span style="font-size:.72rem;color:var(--text-muted)"><?php echo View::e($evento['created_at']); ?> — <?php echo View::e($evento['actor_type'] ?? 'sistema'); ?></span>
      <p style="font-size:.88rem;margin-top:4px"><?php echo View::e($evento['description']); ?></p>
    </div>
  <?php endforeach; endif; ?>
</div>
