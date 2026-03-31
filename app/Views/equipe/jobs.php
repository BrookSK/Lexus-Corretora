<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.jobs')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('jobs.subtitulo')); ?></p>
  </div>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo View::e(I18n::t('jobs.tipo')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th><?php echo View::e(I18n::t('jobs.tentativas')); ?></th>
        <th><?php echo View::e(I18n::t('jobs.erro')); ?></th>
        <th><?php echo View::e(I18n::t('jobs.agendado')); ?></th>
        <th><?php echo View::e(I18n::t('geral.criado_em')); ?></th>
        <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr><td colspan="8"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($items as $item): ?>
      <tr>
        <td><?php echo (int)$item['id']; ?></td>
        <td style="font-size:.82rem"><?php echo View::e($item['type']); ?></td>
        <td>
          <?php
          $badge = match($item['status'] ?? '') {
              'completed' => 'badge-green',
              'failed' => 'badge-red',
              'processing' => 'badge-gold',
              default => 'badge-blue',
          };
          ?>
          <span class="badge <?php echo $badge; ?>"><?php echo View::e($item['status']); ?></span>
        </td>
        <td><?php echo (int)($item['attempts'] ?? 0); ?></td>
        <td style="font-size:.78rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?php echo View::e($item['error'] ?? '—'); ?></td>
        <td style="font-size:.78rem"><?php echo View::e($item['run_at'] ?? '—'); ?></td>
        <td style="font-size:.78rem"><?php echo View::e($item['created_at']); ?></td>
        <td>
          <?php if (($item['status'] ?? '') === 'failed'): ?>
          <form method="POST" action="/equipe/jobs/<?php echo (int)$item['id']; ?>/retry" style="display:inline">
            <?php echo Csrf::campo(); ?>
            <button type="submit" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('jobs.retentar')); ?></button>
          </form>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
