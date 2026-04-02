<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};

$colunas = [
    'novo'                  => ['label' => 'Novo',                'accent' => '#3b82f6'],
    'em_triagem'            => ['label' => 'Em Triagem',          'accent' => '#f59e0b'],
    'em_estruturacao'       => ['label' => 'Estruturação',        'accent' => '#8b5cf6'],
    'pronto_repasse'        => ['label' => 'Pronto p/ Repasse',   'accent' => '#06b6d4'],
    'distribuido'           => ['label' => 'Distribuído',         'accent' => '#0ea5e9'],
    'aguardando_respostas'  => ['label' => 'Aguard. Respostas',   'accent' => '#f97316'],
    'recebendo_propostas'   => ['label' => 'Propostas',           'accent' => '#ec4899'],
    'em_curadoria'          => ['label' => 'Em Curadoria',        'accent' => '#6366f1'],
    'apresentado_cliente'   => ['label' => 'Apresentado',         'accent' => '#14b8a6'],
    'em_negociacao'         => ['label' => 'Negociação',          'accent' => '#b8945a'],
    'contrato_formalizacao' => ['label' => 'Contrato',            'accent' => '#84cc16'],
    'fechado_ganho'         => ['label' => 'Fechado — Ganho',     'accent' => '#22c55e'],
    'fechado_perda'         => ['label' => 'Fechado — Perda',     'accent' => '#ef4444'],
    'pausado'               => ['label' => 'Pausado',             'accent' => '#94a3b8'],
    'cancelado'             => ['label' => 'Cancelado',           'accent' => '#64748b'],
];

$urgencyLabel = ['baixa' => 'Baixa', 'media' => 'Média', 'alta' => 'Alta', 'critica' => 'Crítica'];
$urgencyColor = ['baixa' => '#22c55e', 'media' => '#f59e0b', 'alta' => '#f97316', 'critica' => '#ef4444'];

$kanban = $kanban ?? [];
$busca  = $busca ?? '';
$total  = $total ?? 0;
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.crm')); ?></h1>
    <p class="section-subtitle"><?php echo $total; ?> demanda<?php echo $total !== 1 ? 's' : ''; ?> no pipeline</p>
  </div>
  <div style="display:flex;gap:10px;align-items:center">
    <form method="GET" action="/equipe/crm" style="display:flex;gap:8px;align-items:center">
      <input type="text" name="busca" value="<?php echo View::e($busca); ?>" placeholder="Buscar demanda..." style="padding:8px 12px;border:1px solid var(--border);background:var(--white);font-size:.82rem;outline:none;width:220px"/>
      <button type="submit" class="btn btn-secondary btn-sm">Buscar</button>
      <?php if ($busca !== ''): ?>
      <a href="/equipe/crm" class="btn btn-secondary btn-sm">Limpar</a>
      <?php endif; ?>
    </form>
    <a href="/equipe/demandas/criar" class="btn btn-primary">+ Nova Demanda</a>
  </div>
</div>

<div class="crm-kanban">
  <?php foreach ($colunas as $slug => $col):
    $cards = $kanban[$slug] ?? [];
    $count = count($cards);
  ?>
  <div class="crm-col">
    <div class="crm-col-head" style="border-top:3px solid <?php echo $col['accent']; ?>">
      <span class="crm-col-title"><?php echo View::e($col['label']); ?></span>
      <span class="crm-col-count" style="background:<?php echo $col['accent']; ?>20;color:<?php echo $col['accent']; ?>"><?php echo $count; ?></span>
    </div>
    <div class="crm-col-body">
      <?php if (empty($cards)): ?>
      <div class="crm-empty">—</div>
      <?php else: foreach ($cards as $c): ?>
      <a href="/equipe/demandas/<?php echo (int)$c['id']; ?>" class="crm-card">
        <div class="crm-card-code"><?php echo View::e($c['code']); ?></div>
        <div class="crm-card-title"><?php echo View::e($c['title']); ?></div>
        <?php if (!empty($c['cliente_nome'])): ?>
        <div class="crm-card-meta">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
          <?php echo View::e($c['cliente_nome']); ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($c['city'])): ?>
        <div class="crm-card-meta">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
          <?php echo View::e($c['city']); ?><?php echo !empty($c['state']) ? ', '.View::e($c['state']) : ''; ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($c['budget_max']) && $c['budget_max'] > 0): ?>
        <div class="crm-card-budget">
          <?php
          $cur = $c['currency_code'] ?? 'BRL';
          $sym = $cur === 'USD' ? 'US$' : ($cur === 'EUR' ? '€' : 'R$');
          echo $sym . ' ' . number_format((float)$c['budget_max'], 0, ',', '.');
          ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($c['urgency'])): ?>
        <div class="crm-card-foot">
          <span class="crm-urgency" style="color:<?php echo $urgencyColor[$c['urgency']] ?? '#94a3b8'; ?>">
            ● <?php echo View::e($urgencyLabel[$c['urgency']] ?? $c['urgency']); ?>
          </span>
          <span class="crm-card-date"><?php echo date('d/m', strtotime($c['created_at'])); ?></span>
        </div>
        <?php endif; ?>
      </a>
      <?php endforeach; endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>
