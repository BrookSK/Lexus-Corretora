<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('logs.auditoria')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('logs.auditoria_desc')); ?></p>
  </div>
  <a href="/equipe/logs" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('logs.ator')); ?></th>
        <th><?php echo View::e(I18n::t('logs.acao')); ?></th>
        <th><?php echo View::e(I18n::t('logs.entidade')); ?></th>
        <th>ID</th>
        <th>IP</th>
        <th><?php echo View::e(I18n::t('geral.data')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr><td colspan="6"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($items as $item): ?>
      <tr>
        <td>
          <span class="badge badge-blue"><?php echo View::e($item['actor_type']); ?></span>
          <span style="font-size:.82rem">#<?php echo (int)($item['actor_id'] ?? 0); ?></span>
        </td>
        <td><?php echo View::e($item['action']); ?></td>
        <td><?php echo View::e($item['entity_type'] ?? '—'); ?></td>
        <td><?php echo (int)($item['entity_id'] ?? 0); ?></td>
        <td style="font-size:.78rem"><?php echo View::e($item['ip'] ?? '—'); ?></td>
        <td style="font-size:.78rem"><?php echo View::e($item['created_at']); ?></td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
