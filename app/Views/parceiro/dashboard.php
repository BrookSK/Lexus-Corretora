<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Auth};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_par.dashboard')); ?></h1>
    <p class="section-subtitle">Olá, <?php echo View::e(Auth::parceiroNome()); ?>!</p>
  </div>
</div>

<div class="cards-grid">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar_par.oportunidades')); ?></div>
    <div class="card-value"><?php echo $oportunidadesRecebidas; ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar_par.propostas')); ?></div>
    <div class="card-value"><?php echo $propostasEnviadas; ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('sidebar_par.comissoes')); ?></div>
    <div class="card-value"><?php echo I18n::formatarMoeda($comissoesRecebidas); ?></div>
  </div>
</div>

<?php if (!empty($oportunidadesPendentes)): ?>
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title">Oportunidades Pendentes</h2></div>
  <a href="/parceiro/oportunidades" class="btn btn-secondary btn-sm">Ver todas</a>
</div>
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Código</th>
        <th>Título</th>
        <th>Cidade / Estado</th>
        <th>Orçamento</th>
        <th>Status</th>
        <th>Recebido em</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($oportunidadesPendentes as $op): ?>
      <tr>
        <td><strong><?php echo View::e($op['demanda_code'] ?? '—'); ?></strong></td>
        <td><?php echo View::e($op['title'] ?? '—'); ?></td>
        <td><?php echo View::e(($op['city'] ?? '—') . ' / ' . ($op['state'] ?? '—')); ?></td>
        <td>
          <?php if (!empty($op['budget_min']) || !empty($op['budget_max'])): ?>
            R$ <?php echo number_format((float)($op['budget_min'] ?? 0), 0, ',', '.'); ?>
            — R$ <?php echo number_format((float)($op['budget_max'] ?? 0), 0, ',', '.'); ?>
          <?php else: ?>—<?php endif; ?>
        </td>
        <td>
          <span class="badge <?php echo $op['status'] === 'enviado' ? 'badge-gray' : 'badge-blue'; ?>">
            <?php echo $op['status'] === 'enviado' ? 'Novo' : 'Visualizado'; ?>
          </span>
        </td>
        <td><?php echo View::e(date('d/m/Y H:i', strtotime($op['sent_at']))); ?></td>
        <td><a href="/parceiro/oportunidades/<?php echo (int)$op['id']; ?>" class="btn btn-primary btn-sm">Ver</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>
