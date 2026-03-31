<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.permissoes')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('permissoes.subtitulo')); ?></p>
  </div>
</div>

<div class="card">
  <form method="POST" action="/equipe/permissoes/salvar">
    <?php echo Csrf::campo(); ?>

    <div class="table-wrap" style="border:none">
      <table>
        <thead>
          <tr>
            <th><?php echo View::e(I18n::t('permissoes.permissao')); ?></th>
            <?php if (!empty($roles)): foreach ($roles as $role): ?>
            <th style="text-align:center"><?php echo View::e($role['name']); ?></th>
            <?php endforeach; endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($permissions)): ?>
          <tr><td colspan="<?php echo 1 + count($roles ?? []); ?>"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
          <?php else: ?>
            <?php
            $currentGroup = '';
            foreach ($permissions as $perm):
              if (($perm['group_name'] ?? '') !== $currentGroup):
                $currentGroup = $perm['group_name'] ?? '';
            ?>
            <tr>
              <td colspan="<?php echo 1 + count($roles ?? []); ?>" style="background:var(--bg);font-weight:500;font-size:.78rem;letter-spacing:.06em;text-transform:uppercase;color:var(--gold)">
                <?php echo View::e($currentGroup ?: 'Geral'); ?>
              </td>
            </tr>
            <?php endif; ?>
            <tr>
              <td><?php echo View::e($perm['name']); ?></td>
              <?php if (!empty($roles)): foreach ($roles as $role): ?>
              <td style="text-align:center">
                <input type="checkbox"
                  name="perms[<?php echo (int)$role['id']; ?>][]"
                  value="<?php echo (int)$perm['id']; ?>"
                  <?php echo in_array((int)$perm['id'], $rolePermissions[$role['id']] ?? []) ? 'checked' : ''; ?>
                />
              </td>
              <?php endforeach; endif; ?>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div style="margin-top:24px">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
