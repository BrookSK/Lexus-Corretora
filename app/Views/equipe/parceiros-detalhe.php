<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e($parceiro['name']); ?></h1>
    <p class="section-subtitle">
      <span class="badge <?php echo ($parceiro['is_vetriks'] ?? false) ? 'badge-gold' : 'badge-gray'; ?>">
        <?php echo ($parceiro['is_vetriks'] ?? false) ? 'Vetriks ✓' : View::e($parceiro['status']); ?>
      </span>
      <span class="badge badge-blue"><?php echo View::e($parceiro['type']); ?></span>
    </p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="/equipe/parceiros/<?php echo (int)$parceiro['id']; ?>/editar" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.editar')); ?></a>
    <a href="/equipe/parceiros" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
  </div>
</div>

<div class="cards-grid">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('auth.email')); ?></div>
    <div class="card-title"><?php echo View::e($parceiro['email']); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.telefone')); ?></div>
    <div class="card-title"><?php echo View::e($parceiro['phone'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label">Score</div>
    <div class="card-value"><?php echo (int)($parceiro['score'] ?? 0); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('parceiros.taxa_resposta')); ?></div>
    <div class="card-value"><?php echo number_format((float)($parceiro['response_rate'] ?? 0), 1); ?>%</div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('parceiros.taxa_fechamento')); ?></div>
    <div class="card-value"><?php echo number_format((float)($parceiro['close_rate'] ?? 0), 1); ?>%</div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('parceiros.disponibilidade')); ?></div>
    <div class="card-title"><?php echo View::e($parceiro['availability'] ?? 'disponivel'); ?></div>
  </div>
  <div class="card">
    <div class="card-label">CREA/CAU</div>
    <div class="card-title"><?php echo View::e($parceiro['crea_cau'] ?? '—'); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('geral.cidade')); ?> / <?php echo View::e(I18n::t('geral.estado')); ?></div>
    <div class="card-title"><?php echo View::e(($parceiro['city'] ?? '') . ' / ' . ($parceiro['state'] ?? '')); ?></div>
  </div>
</div>

<!-- Qualificação -->
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('sidebar.qualificacao')); ?></h2></div>
</div>
<?php if (!empty($qualificacoes)): ?>
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th>Score</th>
        <th>Vetriks</th>
        <th><?php echo View::e(I18n::t('geral.data')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($qualificacoes as $q): ?>
      <tr>
        <td><span class="badge badge-gold"><?php echo View::e($q['status']); ?></span></td>
        <td><?php echo (int)$q['overall_score']; ?></td>
        <td><?php echo $q['vetriks_granted'] ? '✓' : '—'; ?></td>
        <td><?php echo View::e($q['created_at']); ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php else: ?>
<div class="card"><p style="color:var(--text-muted);font-size:.88rem"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></p></div>
<?php endif; ?>

<!-- Estatísticas -->
<div class="section-header" style="margin-top:32px">
  <div><h2 class="section-title"><?php echo View::e(I18n::t('geral.estatisticas')); ?></h2></div>
</div>
<div class="cards-grid">
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('parceiros.propostas_enviadas')); ?></div>
    <div class="card-value"><?php echo (int)($stats['propostas_enviadas'] ?? 0); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('parceiros.contratos_fechados')); ?></div>
    <div class="card-value"><?php echo (int)($stats['contratos_fechados'] ?? 0); ?></div>
  </div>
  <div class="card">
    <div class="card-label"><?php echo View::e(I18n::t('parceiros.comissoes_geradas')); ?></div>
    <div class="card-value"><?php echo I18n::formatarMoeda($stats['comissoes_geradas'] ?? 0); ?></div>
  </div>
</div>
