<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e($cliente['name']); ?></h1>
    <p class="section-subtitle">
      <?php if ($cliente['is_active']): ?>
        <span class="badge badge-green"><?php echo View::e(I18n::t('geral.ativo')); ?></span>
      <?php else: ?>
        <span class="badge badge-gray"><?php echo View::e(I18n::t('geral.inativo')); ?></span>
      <?php endif; ?>
    </p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="/equipe/clientes/<?php echo (int)$cliente['id']; ?>/editar" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.editar')); ?></a>
    <a href="/equipe/clientes" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
  </div>
</div>

<div class="cards-grid">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('auth.email')); ?></div>
    <div class="card-title"><?php echo View::e($cliente['email']); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.telefone')); ?></div>
    <div class="card-title"><?php echo View::e($cliente['phone'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.whatsapp')); ?></div>
    <div class="card-title"><?php echo View::e($cliente['whatsapp'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.empresa')); ?></div>
    <div class="card-title"><?php echo View::e($cliente['company'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.documento')); ?></div>
    <div class="card-title"><?php echo View::e($cliente['document'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.cidade')); ?> / <?php echo View::e(I18n::t('geral.estado')); ?></div>
    <div class="card-title"><?php echo View::e(($cliente['city'] ?? '') . ' / ' . ($cliente['state'] ?? '')); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.pais')); ?></div>
    <div class="card-title"><?php echo View::e($cliente['country'] ?? 'Brasil'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.criado_em')); ?></div>
    <div class="card-title"><?php echo View::e($cliente['created_at'] ?? '—'); ?></div>
  </div>
</div>

<!-- Demandas do cliente -->
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('sidebar.demandas')); ?></h2></div>
</div>
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('demandas.codigo')); ?></th>
        <th><?php echo View::e(I18n::t('geral.titulo')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th><?php echo View::e(I18n::t('geral.criado_em')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($demandas)): ?>
      <tr><td colspan="4"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($demandas as $d): ?>
      <tr>
        <td><a href="/equipe/demandas/<?php echo (int)$d['id']; ?>"><?php echo View::e($d['code']); ?></a></td>
        <td><?php echo View::e($d['title']); ?></td>
        <td><span class="badge badge-gold"><?php echo View::e($d['status']); ?></span></td>
        <td><?php echo View::e($d['created_at']); ?></td>
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
      <span style="font-size:.72rem;color:var(--text-muted)"><?php echo View::e($evento['created_at']); ?></span>
      <p style="font-size:.88rem;margin-top:4px"><?php echo View::e($evento['description']); ?></p>
    </div>
  <?php endforeach; endif; ?>
</div>
