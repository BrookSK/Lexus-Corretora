<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Lista de propostas enviadas pelo parceiro
 * Variáveis: $propostas (array)
 */

$statusBadge = [
    'novo' => 'badge-gray', 'cadastrado' => 'badge-gray', 'rascunho' => 'badge-gray', 'pendente' => 'badge-gray',
    'em_triagem' => 'badge-blue', 'em_estruturacao' => 'badge-blue', 'em_analise' => 'badge-blue', 'em_qualificacao' => 'badge-blue',
    'distribuido' => 'badge-gold', 'enviada' => 'badge-gold', 'pronto_repasse' => 'badge-gold',
    'aprovado' => 'badge-green', 'vetriks_ativo' => 'badge-green', 'selecionada' => 'badge-green', 'confirmada' => 'badge-green', 'recebida' => 'badge-green',
    'fechado_perda' => 'badge-red', 'reprovado' => 'badge-red', 'cancelado' => 'badge-red', 'descartada' => 'badge-red',
];
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_par.propostas')); ?></h1>
    <p class="section-subtitle">Propostas enviadas para demandas</p>
  </div>
</div>

<?php if (empty($propostas)): ?>
  <div class="card" style="text-align:center;padding:48px">
    <p style="color:var(--text-muted)"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></p>
  </div>
<?php else: ?>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Demanda</th>
          <th>Valor</th>
          <th>Prazo</th>
          <th>Status</th>
          <th>Data</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($propostas as $p): ?>
        <tr>
          <td><strong><?php echo View::e($p['demanda_code'] ?? '—'); ?></strong></td>
          <td>R$ <?php echo View::e(number_format((float)$p['amount'], 2, ',', '.')); ?></td>
          <td><?php echo $p['deadline_days'] ? View::e($p['deadline_days']) . ' dias' : '—'; ?></td>
          <td>
            <span class="badge <?php echo $statusBadge[$p['status']] ?? 'badge-gray'; ?>">
              <?php echo View::e(I18n::t('status_prop.' . $p['status']) ?: $p['status']); ?>
            </span>
          </td>
          <td><?php echo View::e(date('d/m/Y', strtotime($p['created_at']))); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
