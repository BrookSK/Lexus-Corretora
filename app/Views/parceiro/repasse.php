<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
$statusBadge = [
    'novo'          => 'badge-blue',
    'em_analise'    => 'badge-gold',
    'em_distribuicao' => 'badge-gold',
    'concluido'     => 'badge-green',
    'cancelado'     => 'badge-red',
];
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_par.repasse')); ?></h1>
    <p class="section-subtitle">Demandas que você indicou para a Lexus</p>
  </div>
  <a href="/parceiro/repasse/nova" class="btn btn-primary">+ Nova Indicação</a>
</div>

<?php if (empty($repasses)): ?>
<div class="card" style="padding:48px;text-align:center">
  <p style="color:var(--text-muted);margin-bottom:20px">Você ainda não fez nenhuma indicação de demanda.</p>
  <a href="/parceiro/repasse/nova" class="btn btn-primary">Indicar minha primeira demanda</a>
</div>
<?php else: ?>
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Código</th>
        <th>Título</th>
        <th>Categoria</th>
        <th>Localização</th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th><?php echo View::e(I18n::t('geral.data')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($repasses as $r): ?>
      <tr>
        <td style="font-weight:600;color:var(--gold)"><?php echo View::e($r['code']); ?></td>
        <td><?php echo View::e($r['title']); ?></td>
        <td><?php echo View::e($r['category'] ?? '—'); ?></td>
        <td><?php echo View::e(trim(($r['city'] ?? '') . ', ' . ($r['state'] ?? ''), ', ') ?: '—'); ?></td>
        <td>
          <span class="badge <?php echo $statusBadge[$r['status']] ?? 'badge-blue'; ?>">
            <?php echo View::e($r['status']); ?>
          </span>
        </td>
        <td style="font-size:.8rem;color:var(--text-muted)"><?php echo View::e(substr($r['created_at'], 0, 10)); ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>
