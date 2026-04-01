<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};

$badge = match($contrato['status'] ?? '') {
    'formalizado'          => 'badge-green',
    'cancelado'            => 'badge-red',
    'pendente_confirmacao' => 'badge-blue',
    default                => 'badge-gold',
};
$statusLabel = [
    'em_formalizacao'      => 'Em formalização',
    'pendente_confirmacao' => 'Pendente confirmação',
    'formalizado'          => 'Formalizado',
    'cancelado'            => 'Cancelado',
];
?>
<div class="section-header">
  <div>
    <h1 class="section-title">Contrato #<?php echo (int)$contrato['id']; ?></h1>
    <p class="section-subtitle">
      <span class="badge <?php echo $badge; ?>"><?php echo $statusLabel[$contrato['status']] ?? View::e($contrato['status']); ?></span>
      &nbsp;·&nbsp; criado em <?php echo View::e(date('d/m/Y H:i', strtotime($contrato['created_at']))); ?>
    </p>
  </div>
  <a href="/parceiro/contratos" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<!-- Resumo financeiro -->
<div class="cards-grid" style="margin-bottom:24px">
  <div class="card">
    <div class="card-label">Valor do Contrato</div>
    <div class="card-value"><?php echo I18n::formatarMoeda($contrato['amount']); ?></div>
  </div>
  <?php if (!empty($contrato['proposta_prazo'])): ?>
  <div class="card">
    <div class="card-label">Prazo (dias)</div>
    <div class="card-value"><?php echo (int)$contrato['proposta_prazo']; ?></div>
  </div>
  <?php endif; ?>
  <div class="card">
    <div class="card-label">Formalização</div>
    <div class="card-title"><?php echo $contrato['formalized_at'] ? View::e(date('d/m/Y', strtotime($contrato['formalized_at']))) : '—'; ?></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px">

  <!-- Projeto -->
  <div class="card">
    <h3 class="card-title" style="margin-bottom:16px">Projeto / Demanda</h3>
    <table style="font-size:.88rem;width:100%">
      <tr><td style="color:var(--text-muted);width:130px;padding:5px 0">Código</td>
          <td><?php echo View::e($contrato['demanda_code'] ?? '—'); ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:5px 0">Título</td>
          <td><?php echo View::e($contrato['demanda_title'] ?? '—'); ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:5px 0">Categoria</td>
          <td><?php echo View::e($contrato['demanda_category'] ?? '—'); ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:5px 0">Localização</td>
          <td><?php echo View::e(($contrato['demanda_city'] ?? '—') . ' / ' . ($contrato['demanda_state'] ?? '—')); ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:5px 0">Orçamento</td>
          <td><?php
            $bmin = $contrato['demanda_budget_min'] ?? 0;
            $bmax = $contrato['demanda_budget_max'] ?? 0;
            echo ($bmin || $bmax)
              ? 'R$ ' . number_format((float)$bmin, 0, ',', '.') . ' — R$ ' . number_format((float)$bmax, 0, ',', '.')
              : '—';
          ?></td></tr>
      <tr><td style="color:var(--text-muted);padding:5px 0">Urgência</td>
          <td><?php echo View::e(ucfirst($contrato['demanda_urgency'] ?? '—')); ?></td></tr>
    </table>
    <?php if (!empty($contrato['demanda_description'])): ?>
    <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border-color)">
      <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:6px">Descrição</div>
      <p style="font-size:.85rem;line-height:1.6"><?php echo nl2br(View::e($contrato['demanda_description'])); ?></p>
    </div>
    <?php endif; ?>
  </div>

  <!-- Cliente -->
  <div class="card">
    <h3 class="card-title" style="margin-bottom:16px">Cliente / Empresa</h3>
    <table style="font-size:.88rem;width:100%">
      <tr><td style="color:var(--text-muted);width:130px;padding:5px 0">Nome</td>
          <td><?php echo View::e($contrato['cliente_nome'] ?? '—'); ?></td></tr>
      <?php if (!empty($contrato['cliente_company'])): ?>
      <tr><td style="color:var(--text-muted);padding:5px 0">Empresa</td>
          <td><?php echo View::e($contrato['cliente_company']); ?></td></tr>
      <?php endif; ?>
      <tr><td style="color:var(--text-muted);padding:5px 0">E-mail</td>
          <td><?php echo View::e($contrato['cliente_email'] ?? '—'); ?></td></tr>
      <?php if (!empty($contrato['cliente_phone'])): ?>
      <tr><td style="color:var(--text-muted);padding:5px 0">Telefone</td>
          <td><?php echo View::e($contrato['cliente_phone']); ?></td></tr>
      <?php endif; ?>
    </table>
  </div>

  <!-- Proposta -->
  <?php if ($contrato['proposta_id']): ?>
  <div class="card" style="grid-column:span 2">
    <h3 class="card-title" style="margin-bottom:16px">Proposta Aprovada</h3>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
      <div>
        <table style="font-size:.88rem;width:100%">
          <tr><td style="color:var(--text-muted);width:120px;padding:5px 0">Proposta #</td>
              <td><?php echo (int)$contrato['proposta_id']; ?></td></tr>
          <tr><td style="color:var(--text-muted);padding:5px 0">Valor</td>
              <td><?php echo I18n::formatarMoeda($contrato['amount']); ?></td></tr>
          <?php if (!empty($contrato['proposta_prazo'])): ?>
          <tr><td style="color:var(--text-muted);padding:5px 0">Prazo</td>
              <td><?php echo (int)$contrato['proposta_prazo']; ?> dias</td></tr>
          <?php endif; ?>
        </table>
      </div>
      <?php if (!empty($contrato['proposta_descricao'])): ?>
      <div>
        <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:6px">Descrição</div>
        <p style="font-size:.85rem;line-height:1.6"><?php echo nl2br(View::e($contrato['proposta_descricao'])); ?></p>
      </div>
      <?php endif; ?>
    </div>
    <?php if (!empty($contrato['proposta_condicoes'])): ?>
    <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border-color)">
      <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:4px">Condições</div>
      <p style="font-size:.85rem;line-height:1.6"><?php echo nl2br(View::e($contrato['proposta_condicoes'])); ?></p>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div>
