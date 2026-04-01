<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.tarefas')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('tarefas.subtitulo_lista')); ?></p>
  </div>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('geral.titulo')); ?></th>
        <th><?php echo View::e(I18n::t('tarefas.responsavel')); ?></th>
        <th><?php echo View::e(I18n::t('tarefas.prioridade')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th><?php echo View::e(I18n::t('tarefas.vencimento')); ?></th>
        <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr><td colspan="6"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($items as $item): ?>
      <tr>
        <td><?php echo View::e($item['title']); ?></td>
        <td><?php echo View::e($item['assigned_nome'] ?? '—'); ?></td>
        <td>
          <?php
          $prBadge = match($item['priority'] ?? '') {
              'urgente' => 'badge-red', 'alta' => 'badge-gold',
              'baixa' => 'badge-gray', default => 'badge-blue',
          };
          ?>
          <span class="badge <?php echo $prBadge; ?>"><?php echo View::e($item['priority'] ?? 'normal'); ?></span>
        </td>
        <td>
          <?php
          $stBadge = match($item['status'] ?? '') {
              'concluida' => 'badge-green',
              'cancelada' => 'badge-red',
              'em_andamento' => 'badge-gold',
              default => 'badge-blue',
          };
          ?>
          <span class="badge <?php echo $stBadge; ?>"><?php echo View::e($item['status'] ?? 'pendente'); ?></span>
        </td>
        <td>
          <?php if (!empty($item['due_date'])): ?>
            <span style="font-size:.82rem"><?php echo View::e($item['due_date']); ?></span>
          <?php else: ?>—<?php endif; ?>
        </td>
        <td>
          <form method="POST" action="/equipe/tarefas/<?php echo (int)$item['id']; ?>/status" style="display:inline">
            <?php echo Csrf::campo(); ?>
            <input type="hidden" name="status" value="concluida"/>
            <button type="submit" class="btn btn-secondary btn-sm" <?php echo ($item['status'] ?? '') === 'concluida' ? 'disabled' : ''; ?>><?php echo View::e(I18n::t('tarefas.concluir')); ?></button>
          </form>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
