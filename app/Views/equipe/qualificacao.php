<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.qualificacao')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('qualificacao.subtitulo_lista')); ?></p>
  </div>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('sidebar.parceiros')); ?></th>
        <th><?php echo View::e(I18n::t('parceiros.tipo')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th>Score</th>
        <th>Vetriks</th>
        <th><?php echo View::e(I18n::t('geral.data')); ?></th>
        <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr><td colspan="7"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($items as $item): ?>
      <tr>
        <td><?php echo View::e($item['parceiro_nome'] ?? '—'); ?></td>
        <td><?php echo View::e($item['parceiro_type'] ?? '—'); ?></td>
        <td>
          <?php
          $badge = match($item['status'] ?? '') {
              'aprovado' => 'badge-green',
              'reprovado' => 'badge-red',
              default => 'badge-gold',
          };
          ?>
          <span class="badge <?php echo $badge; ?>"><?php echo View::e($item['status']); ?></span>
        </td>
        <td><?php echo (int)($item['overall_score'] ?? 0); ?></td>
        <td><?php echo ($item['vetriks_granted'] ?? false) ? '<span class="badge badge-gold">✓</span>' : '—'; ?></td>
        <td><?php echo View::e($item['created_at']); ?></td>
        <td>
          <a href="/equipe/qualificacao/<?php echo (int)$item['id']; ?>/avaliar" class="btn btn-primary btn-sm"><?php echo View::e(I18n::t('qualificacao.avaliar')); ?></a>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
