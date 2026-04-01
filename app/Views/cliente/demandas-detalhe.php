<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Detalhe de uma demanda — painel do cliente
 * Variáveis: $demanda (array), $timeline (array), $propostas (array)
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
    <h1 class="section-title"><?php echo View::e($demanda['code']); ?> — <?php echo View::e($demanda['title']); ?></h1>
    <p class="section-subtitle">
      <span class="badge <?php echo $statusBadge[$demanda['status']] ?? 'badge-gray'; ?>">
        <?php echo View::e(I18n::t('status.' . $demanda['status']) ?: $demanda['status']); ?>
      </span>
      <span class="badge <?php echo $urgenciaBadge[$demanda['urgency'] ?? 'media'] ?? 'badge-gray'; ?>" style="margin-left:8px">
        <?php echo View::e(ucfirst($demanda['urgency'] ?? 'media')); ?>
      </span>
    </p>
  </div>
  <a href="/cliente/demandas" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<!-- Informações Gerais -->
<div class="cards-grid" style="grid-template-columns:1fr 1fr">
  <div class="card">
    <h3 class="card-title"><?php echo View::e(I18n::t('demanda.dados_obra')); ?></h3>
    <table style="width:100%;font-size:.88rem;margin-top:12px">
      <tr><td style="color:var(--text-muted);padding:6px 0;width:40%"><?php echo View::e(I18n::t('demanda.tipo_obra')); ?></td><td style="padding:6px 0"><?php echo View::e($demanda['work_type'] ?: ($demanda['category'] ?? '—')); ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.localizacao')); ?></td><td style="padding:6px 0"><?php echo View::e(($demanda['city'] ?? '') . ', ' . ($demanda['state'] ?? '')); ?></td></tr>
      <?php if (!empty($demanda['address'])): ?>
      <tr><td style="color:var(--text-muted);padding:6px 0">Endereço</td><td style="padding:6px 0"><?php echo View::e($demanda['address']); ?></td></tr>
      <?php endif; ?>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.metragem')); ?></td><td style="padding:6px 0"><?php echo $demanda['area_sqm'] ? View::e(number_format((float)$demanda['area_sqm'], 2, ',', '.')) . ' m²' : '—'; ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.prazo_desejado')); ?></td><td style="padding:6px 0"><?php echo $demanda['desired_deadline'] ? View::e(date('d/m/Y', strtotime($demanda['desired_deadline']))) : '—'; ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0">Criada em</td><td style="padding:6px 0"><?php echo View::e(date('d/m/Y H:i', strtotime($demanda['created_at']))); ?></td></tr>
    </table>
  </div>

  <div class="card">
    <h3 class="card-title"><?php echo View::e(I18n::t('demanda.orcamento')); ?></h3>
    <table style="width:100%;font-size:.88rem;margin-top:12px">
      <tr><td style="color:var(--text-muted);padding:6px 0;width:40%">Mínimo</td><td style="padding:6px 0"><?php echo $demanda['budget_min'] ? 'R$ ' . View::e(number_format((float)$demanda['budget_min'], 2, ',', '.')) : '—'; ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0">Máximo</td><td style="padding:6px 0"><?php echo $demanda['budget_max'] ? 'R$ ' . View::e(number_format((float)$demanda['budget_max'], 2, ',', '.')) : '—'; ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.tem_projeto')); ?></td><td style="padding:6px 0"><?php echo ($demanda['has_project'] ?? 0) ? View::e(I18n::t('geral.sim')) : View::e(I18n::t('geral.nao')); ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.tem_arquiteto')); ?></td><td style="padding:6px 0"><?php echo ($demanda['has_architect'] ?? 0) ? View::e(I18n::t('geral.sim')) : View::e(I18n::t('geral.nao')); ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:6px 0"><?php echo View::e(I18n::t('demanda.multiplas_prop')); ?></td><td style="padding:6px 0"><?php echo ($demanda['wants_multiple_proposals'] ?? 1) ? View::e(I18n::t('geral.sim')) : View::e(I18n::t('geral.nao')); ?></td></tr>
    </table>
  </div>
</div>

<!-- Descrição -->
<?php if (!empty($demanda['description'])): ?>
<div class="card" style="margin-bottom:24px">
  <h3 class="card-title"><?php echo View::e(I18n::t('demanda.descricao')); ?></h3>
  <p style="margin-top:12px;font-size:.88rem;line-height:1.6;white-space:pre-wrap"><?php echo View::e($demanda['description']); ?></p>
</div>
<?php endif; ?>

<!-- Observações -->
<?php if (!empty($demanda['notes'])): ?>
<div class="card" style="margin-bottom:24px">
  <h3 class="card-title"><?php echo View::e(I18n::t('demanda.observacoes')); ?></h3>
  <p style="margin-top:12px;font-size:.88rem;line-height:1.6;white-space:pre-wrap"><?php echo View::e($demanda['notes']); ?></p>
</div>
<?php endif; ?>

<!-- Anexos -->
<?php if (!empty($demanda['arquivos'])): ?>
<div class="card" style="margin-bottom:24px">
  <h3 class="card-title"><?php echo View::e(I18n::t('demanda.uploads')); ?></h3>
  <div style="margin-top:12px;display:flex;flex-direction:column;gap:8px">
    <?php foreach ($demanda['arquivos'] as $arq): ?>
    <a href="/<?php echo View::e(ltrim($arq['file_path'], '/')); ?>" target="_blank"
       style="display:flex;align-items:center;gap:8px;font-size:.88rem;color:var(--gold);text-decoration:none">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      <?php echo View::e($arq['name']); ?>
      <?php if (!empty($arq['file_size'])): ?>
        <span style="color:var(--text-muted);font-size:.75rem">(<?php echo View::e(number_format($arq['file_size'] / 1024, 0, ',', '.')); ?> KB)</span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- Propostas apresentadas ao cliente -->
<?php if (!empty($propostas)): ?>
<div style="margin-bottom:24px">
  <h2 class="section-title" style="margin-bottom:16px"><?php echo View::e(I18n::t('sidebar_cli.propostas')); ?></h2>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Parceiro</th>
          <th>Valor</th>
          <th>Prazo</th>
          <th>Status</th>
          <th>Data</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($propostas as $p): ?>
          <?php if (!empty($p['presented_to_client'])): ?>
          <tr>
            <td><?php echo View::e($p['parceiro_nome'] ?? '—'); ?></td>
            <td>R$ <?php echo View::e(number_format((float)$p['amount'], 2, ',', '.')); ?></td>
            <td><?php echo $p['deadline_days'] ? View::e($p['deadline_days']) . ' dias' : '—'; ?></td>
            <td>
              <span class="badge <?php echo $statusBadge[$p['status']] ?? 'badge-gray'; ?>">
                <?php echo View::e(I18n::t('status_prop.' . $p['status']) ?: $p['status']); ?>
              </span>
            </td>
            <td><?php echo View::e(date('d/m/Y', strtotime($p['created_at']))); ?></td>
          </tr>
          <?php endif; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<!-- Timeline -->
<?php if (!empty($timeline)): ?>
<div class="card">
  <h3 class="card-title">Timeline</h3>
  <div style="margin-top:16px">
    <?php foreach ($timeline as $event): ?>
    <div style="display:flex;gap:16px;padding:12px 0;border-bottom:1px solid var(--border)">
      <div style="flex-shrink:0;width:100px;font-size:.75rem;color:var(--text-muted)">
        <?php echo View::e(date('d/m/Y H:i', strtotime($event['created_at']))); ?>
      </div>
      <div style="font-size:.88rem">
        <strong style="font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;color:var(--gold)"><?php echo View::e($event['event_type']); ?></strong>
        <?php if (!empty($event['description'])): ?>
          <p style="margin-top:4px;color:var(--text-muted)"><?php echo View::e($event['description']); ?></p>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>
