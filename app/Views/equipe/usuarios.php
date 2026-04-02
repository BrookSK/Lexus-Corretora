<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.usuarios')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('usuarios.subtitulo_lista')); ?></p>
  </div>
  <a href="/equipe/usuarios/novo" class="btn btn-primary"><?php echo View::e(I18n::t('usuarios.novo_usuario')); ?></a>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('geral.nome')); ?></th>
        <th><?php echo View::e(I18n::t('auth.email')); ?></th>
        <th><?php echo View::e(I18n::t('usuarios.papel')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th><?php echo View::e(I18n::t('usuarios.ultimo_login')); ?></th>
        <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr><td colspan="6"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($items as $item): ?>
      <tr>
        <td><?php echo View::e($item['name']); ?></td>
        <td><?php echo View::e($item['email']); ?></td>
        <td><?php echo !empty($item['role_name']) ? '<span class="badge badge-gold">'.View::e($item['role_name']).'</span>' : '<span style="color:var(--text-muted);font-size:.78rem">—</span>'; ?></td>
        <td>
          <?php if ($item['is_active'] ?? true): ?>
            <span class="badge badge-green"><?php echo View::e(I18n::t('geral.ativo')); ?></span>
          <?php else: ?>
            <span class="badge badge-gray"><?php echo View::e(I18n::t('geral.inativo')); ?></span>
          <?php endif; ?>
        </td>
        <td style="font-size:.8rem;color:var(--text-muted)"><?php echo View::e($item['last_login_at'] ?? '—'); ?></td>
        <td>
          <a href="/equipe/usuarios/<?php echo (int)$item['id']; ?>/editar" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.editar')); ?></a>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
