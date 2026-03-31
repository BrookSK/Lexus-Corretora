<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Lista de comissões do parceiro
 * Variáveis: $comissoes (array)
 */

$statusBadge = [
    'prevista' => 'badge-gray', 'aguardando_confirmacao' => 'badge-blue',
    'confirmada' => 'badge-green', 'faturada' => 'badge-gold',
    'recebida' => 'badge-green', 'atrasada' => 'badge-red', 'cancelada' => 'badge-red',
];

$totalPrevisto = 0;
$totalRecebido = 0;
foreach ($comissoes as $c) {
    $totalPrevisto += (float)($c['commission_amount'] ?? 0);
    if (($c['status'] ?? '') === 'recebida') {
        $totalRecebido += (float)($c['commission_amount'] ?? 0);
    }
}
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_par.comissoes')); ?></h1>
    <p class="section-subtitle">Acompanhe suas comissões por demanda</p>
  </div>
</div>

<!-- Totais -->
<div class="cards-grid">
  <div class="card">
    <div class="card-label">Total Previsto</div>
    <div class="card-value">R$ <?php echo View::e(number_format($totalPrevisto, 2, ',', '.')); ?></div>
  </div>
  <div class="card">
    <div class="card-label">Total Recebido</div>
    <div class="card-value">R$ <?php echo View::e(number_format($totalRecebido, 2, ',', '.')); ?></div>
  </div>
  <div class="card">
    <div class="card-label">Pendente</div>
    <div class="card-value">R$ <?php echo View::e(number_format($totalPrevisto - $totalRecebido, 2, ',', '.')); ?></div>
  </div>
</div>

<?php if (empty($comissoes)): ?>
  <div class="card" style="text-align:center;padding:48px">
    <p style="color:var(--text-muted)"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></p>
  </div>
<?php else: ?>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Demanda</th>
          <th>Valor Base</th>
          <th>%</th>
          <th>Comissão</th>
          <th>Status</th>
          <th>Previsão</th>
          <th>Recebimento</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($comissoes as $c): ?>
        <tr>
          <td><strong><?php echo View::e($c['demanda_code'] ?? '—'); ?></strong></td>
          <td>R$ <?php echo View::e(number_format((float)($c['base_amount'] ?? 0), 2, ',', '.')); ?></td>
          <td><?php echo View::e(number_format((float)($c['commission_pct'] ?? 0), 1, ',', '.')); ?>%</td>
          <td>R$ <?php echo View::e(number_format((float)($c['commission_amount'] ?? 0), 2, ',', '.')); ?></td>
          <td>
            <span class="badge <?php echo $statusBadge[$c['status']] ?? 'badge-gray'; ?>">
              <?php echo View::e(I18n::t('status_com.' . ($c['status'] ?? 'prevista')) ?: ($c['status'] ?? 'prevista')); ?>
            </span>
          </td>
          <td><?php echo !empty($c['expected_date']) ? View::e(date('d/m/Y', strtotime($c['expected_date']))) : '—'; ?></td>
          <td><?php echo !empty($c['received_date']) ? View::e(date('d/m/Y', strtotime($c['received_date']))) : '—'; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" style="text-align:right;font-weight:500">Total</td>
          <td style="font-weight:500">R$ <?php echo View::e(number_format($totalPrevisto, 2, ',', '.')); ?></td>
          <td colspan="3"></td>
        </tr>
      </tfoot>
    </table>
  </div>
<?php endif; ?>
