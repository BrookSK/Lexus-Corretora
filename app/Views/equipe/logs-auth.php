<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('logs.auth')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('logs.auth_desc')); ?></p>
  </div>
  <a href="/equipe/logs" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('logs.tipo_usuario')); ?></th>
        <th><?php echo View::e(I18n::t('auth.email')); ?></th>
        <th><?php echo View::e(I18n::t('logs.acao')); ?></th>
        <th>IP</th>
        <th><?php echo View::e(I18n::t('geral.data')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr><td colspan="5"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($items as $item): ?>
      <tr>
        <td><span class="badge badge-blue"><?php echo View::e($item['user_type']); ?></span></td>
        <td><?php echo View::e($item['email'] ?? '—'); ?></td>
        <td>
          <?php
          $badge = match($item['action'] ?? '') {
              'login' => 'badge-green',
              'login_failed' => 'badge-red',
              'logout' => 'badge-gray',
              default => 'badge-gold',
          };
          ?>
          <span class="badge <?php echo $badge; ?>"><?php echo View::e($item['action']); ?></span>
        </td>
        <td style="font-size:.78rem"><?php echo View::e($item['ip'] ?? '—'); ?></td>
        <td style="font-size:.78rem"><?php echo View::e($item['created_at']); ?></td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
