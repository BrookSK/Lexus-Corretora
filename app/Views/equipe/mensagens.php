<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.mensagens')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('mensagens.subtitulo_lista')); ?></p>
  </div>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('mensagens.assunto')); ?></th>
        <th><?php echo View::e(I18n::t('mensagens.tipo')); ?></th>
        <th><?php echo View::e(I18n::t('sidebar.demandas')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th><?php echo View::e(I18n::t('geral.atualizado_em')); ?></th>
        <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr><td colspan="6"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($items as $item): ?>
      <tr>
        <td><?php echo View::e($item['subject'] ?? '(sem assunto)'); ?></td>
        <td>
          <?php
          $typeBadge = match($item['type'] ?? '') {
              'cliente_lexus' => 'badge-blue',
              'parceiro_lexus' => 'badge-gold',
              'interna' => 'badge-gray',
              default => 'badge-gray',
          };
          ?>
          <span class="badge <?php echo $typeBadge; ?>"><?php echo View::e($item['type']); ?></span>
        </td>
        <td>
          <?php if (!empty($item['demanda_id'])): ?>
            <a href="/equipe/demandas/<?php echo (int)$item['demanda_id']; ?>"><?php echo View::e($item['demanda_code'] ?? '#' . $item['demanda_id']); ?></a>
          <?php else: ?>—<?php endif; ?>
        </td>
        <td>
          <?php
          $stBadge = match($item['status'] ?? '') {
              'aberta' => 'badge-green',
              'fechada' => 'badge-gray',
              'arquivada' => 'badge-gray',
              default => 'badge-blue',
          };
          ?>
          <span class="badge <?php echo $stBadge; ?>"><?php echo View::e($item['status'] ?? 'aberta'); ?></span>
        </td>
        <td><?php echo View::e($item['updated_at']); ?></td>
        <td>
          <a href="/equipe/mensagens/<?php echo (int)$item['id']; ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('mensagens.abrir')); ?></a>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
