<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Lista de demandas do cliente
 * Variáveis: $demandas (array)
 */

$statusBadge = [
    'novo' => 'badge-gray', 'cadastrado' => 'badge-gray', 'rascunho' => 'badge-gray', 'pendente' => 'badge-gray',
    'em_triagem' => 'badge-blue', 'em_estruturacao' => 'badge-blue', 'em_analise' => 'badge-blue', 'em_qualificacao' => 'badge-blue',
    'distribuido' => 'badge-gold', 'enviada' => 'badge-gold', 'pronto_repasse' => 'badge-gold',
    'aprovado' => 'badge-green', 'vetriks_ativo' => 'badge-green', 'selecionada' => 'badge-green', 'confirmada' => 'badge-green', 'recebida' => 'badge-green',
    'fechado_perda' => 'badge-red', 'reprovado' => 'badge-red', 'cancelado' => 'badge-red', 'descartada' => 'badge-red',
];

$urgenciaBadge = [
    'baixa' => 'badge-gray', 'media' => 'badge-blue', 'alta' => 'badge-gold', 'critica' => 'badge-red',
];
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_cli.demandas')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('demanda.titulo')); ?></p>
  </div>
  <a href="/cliente/demandas/nova" class="btn btn-primary"><?php echo View::e(I18n::t('nav.abrir_demanda')); ?></a>
</div>

<?php if (empty($demandas)): ?>
  <div class="card" style="text-align:center;padding:48px">
    <p style="color:var(--text-muted)"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></p>
    <a href="/cliente/demandas/nova" class="btn btn-primary" style="margin-top:16px"><?php echo View::e(I18n::t('nav.abrir_demanda')); ?></a>
  </div>
<?php else: ?>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Código</th>
          <th>Título</th>
          <th>Status</th>
          <th><?php echo View::e(I18n::t('demanda.urgencia')); ?></th>
          <th>Data</th>
          <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($demandas as $d): ?>
        <tr>
          <td><strong><?php echo View::e($d['code']); ?></strong></td>
          <td><?php echo View::e($d['title']); ?></td>
          <td>
            <span class="badge <?php echo $statusBadge[$d['status']] ?? 'badge-gray'; ?>">
              <?php echo View::e(I18n::t('status.' . $d['status']) ?: $d['status']); ?>
            </span>
          </td>
          <td>
            <span class="badge <?php echo $urgenciaBadge[$d['urgency']] ?? 'badge-gray'; ?>">
              <?php echo View::e(ucfirst($d['urgency'] ?? 'media')); ?>
            </span>
          </td>
          <td><?php echo View::e(date('d/m/Y', strtotime($d['created_at']))); ?></td>
          <td>
            <a href="/cliente/demandas/<?php echo View::e($d['code']); ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.detalhes')); ?></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
