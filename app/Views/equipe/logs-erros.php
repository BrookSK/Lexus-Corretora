<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('logs.erros')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('logs.erros_desc')); ?></p>
  </div>
  <a href="/equipe/logs" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>HTTP</th>
        <th><?php echo View::e(I18n::t('logs.tipo')); ?></th>
        <th><?php echo View::e(I18n::t('logs.mensagem')); ?></th>
        <th>URL</th>
        <th>IP</th>
        <th><?php echo View::e(I18n::t('geral.data')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr><td colspan="7"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($items as $item): ?>
      <tr>
        <td><span style="font-family:monospace;font-size:.78rem"><?php echo View::e($item['error_id']); ?></span></td>
        <td>
          <?php
          $code = (int)($item['http_code'] ?? 0);
          $badge = $code >= 500 ? 'badge-red' : ($code >= 400 ? 'badge-gold' : 'badge-gray');
          ?>
          <span class="badge <?php echo $badge; ?>"><?php echo $code; ?></span>
        </td>
        <td style="font-size:.82rem"><?php echo View::e($item['type'] ?? '—'); ?></td>
        <td style="font-size:.82rem;max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?php echo View::e($item['message'] ?? '—'); ?></td>
        <td style="font-size:.78rem"><?php echo View::e($item['url'] ?? '—'); ?></td>
        <td style="font-size:.78rem"><?php echo View::e($item['ip'] ?? '—'); ?></td>
        <td style="font-size:.78rem"><?php echo View::e($item['created_at']); ?></td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
