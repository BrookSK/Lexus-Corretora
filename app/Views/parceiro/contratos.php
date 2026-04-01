<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};

$statusBadge = [
    'em_formalizacao'       => 'badge-gold',
    'pendente_confirmacao'  => 'badge-blue',
    'formalizado'           => 'badge-green',
    'cancelado'             => 'badge-red',
];
$statusLabel = [
    'em_formalizacao'       => 'Em formalização',
    'pendente_confirmacao'  => 'Pendente confirmação',
    'formalizado'           => 'Formalizado',
    'cancelado'             => 'Cancelado',
];
?>
<div class="section-header">
  <div>
    <h1 class="section-title">Meus Contratos</h1>
    <p class="section-subtitle">Contratos gerados a partir de propostas aprovadas</p>
  </div>
</div>

<?php if (empty($contratos)): ?>
  <div class="card" style="text-align:center;padding:48px">
    <p style="color:var(--text-muted)">Nenhum contrato encontrado.</p>
  </div>
<?php else: ?>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Projeto</th>
          <th>Cliente / Empresa</th>
          <th>Localização</th>
          <th>Valor</th>
          <th>Status</th>
          <th>Data</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($contratos as $c): ?>
        <tr>
          <td><strong>#<?php echo (int)$c['id']; ?></strong></td>
          <td>
            <strong><?php echo View::e($c['demanda_code'] ?? '—'); ?></strong><br>
            <span style="font-size:.82rem;color:var(--text-muted)"><?php echo View::e($c['demanda_title'] ?? ''); ?></span>
          </td>
          <td>
            <?php echo View::e($c['cliente_nome'] ?? '—'); ?>
            <?php if (!empty($c['cliente_company'])): ?>
              <br><span style="font-size:.82rem;color:var(--text-muted)"><?php echo View::e($c['cliente_company']); ?></span>
            <?php endif; ?>
          </td>
          <td><?php echo View::e(($c['demanda_city'] ?? '—') . ' / ' . ($c['demanda_state'] ?? '—')); ?></td>
          <td><?php echo I18n::formatarMoeda($c['amount']); ?></td>
          <td>
            <span class="badge <?php echo $statusBadge[$c['status']] ?? 'badge-gray'; ?>">
              <?php echo $statusLabel[$c['status']] ?? View::e($c['status']); ?>
            </span>
          </td>
          <td><?php echo View::e(date('d/m/Y', strtotime($c['created_at']))); ?></td>
          <td>
            <a href="/parceiro/contratos/<?php echo (int)$c['id']; ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.visualizar')); ?></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
