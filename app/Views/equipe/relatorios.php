<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};

$kpis             = $kpis              ?? [];
$fat_mensal       = $fat_mensal        ?? [];
$dem_mensais      = $dem_mensais       ?? [];
$dem_status       = $dem_status        ?? [];
$maiores_parceiros= $maiores_parceiros ?? [];
$comissoes_mens   = $comissoes_mens    ?? [];
$inicio           = $inicio            ?? date('Y-m-d', strtotime('-365 days'));
$fim              = $fim               ?? date('Y-m-d');
$periodo          = $periodo           ?? '365';

$fmt = fn(float $v) => 'R$ ' . number_format($v, 0, ',', '.');
$fmtN = fn(float $v) => number_format($v, 0, ',', '.');

$statusLabels = [
    'novo'=>'Novo','em_triagem'=>'Triagem','em_estruturacao'=>'Estruturação',
    'pronto_repasse'=>'Repasse','distribuido'=>'Distribuído',
    'aguardando_respostas'=>'Aguardando','recebendo_propostas'=>'Propostas',
    'em_curadoria'=>'Curadoria','apresentado_cliente'=>'Apresentado',
    'em_negociacao'=>'Negociação','contrato_formalizacao'=>'Contrato',
    'fechado_ganho'=>'Ganho','fechado_perda'=>'Perda',
    'pausado'=>'Pausado','cancelado'=>'Cancelado',
];

// — Build chart data arrays
$fatMeses    = array_column($fat_mensal,  'mes');
$fatValues   = array_map('floatval', array_column($fat_mensal, 'faturamento'));
$fatTickets  = array_map('floatval', array_column($fat_mensal, 'ticket_medio'));

$demMeses    = array_column($dem_mensais, 'mes');
$demValues   = array_map('intval',   array_column($dem_mensais,'total'));

$comMeses    = array_column($comissoes_mens,'mes');
$comValues   = array_map('floatval', array_column($comissoes_mens,'total'));

$stLabels    = array_map(fn($r) => $statusLabels[$r['status']] ?? $r['status'], $dem_status);
$stValues    = array_map('intval', array_column($dem_status,'total'));

$parcNomes   = array_column($maiores_parceiros,'parceiro');
$parcConts   = array_map('intval',   array_column($maiores_parceiros,'total_contratos'));
$parcFat     = array_map('floatval', array_column($maiores_parceiros,'faturamento_total'));
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="section-header" style="margin-bottom:16px">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.relatorios')); ?></h1>
    <p class="section-subtitle">
      Período: <?php echo date('d/m/Y', strtotime($inicio)); ?> — <?php echo date('d/m/Y', strtotime($fim)); ?>
    </p>
  </div>
  <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
    <?php foreach (['30'=>'30 dias','90'=>'90 dias','180'=>'6 meses','365'=>'1 ano'] as $p=>$l): ?>
    <a href="?periodo=<?php echo $p; ?>"
       class="btn btn-sm <?php echo $periodo===$p ? 'btn-primary' : 'btn-secondary'; ?>"><?php echo $l; ?></a>
    <?php endforeach; ?>
    <form method="GET" action="/equipe/relatorios" style="display:flex;gap:6px;align-items:center">
      <input type="date" name="inicio" value="<?php echo View::e($inicio); ?>"
             style="padding:6px 10px;border:1px solid var(--border);font-size:.78rem;background:var(--white)"/>
      <span style="color:var(--text-muted);font-size:.8rem">até</span>
      <input type="date" name="fim" value="<?php echo View::e($fim); ?>"
             style="padding:6px 10px;border:1px solid var(--border);font-size:.78rem;background:var(--white)"/>
      <button type="submit" class="btn btn-secondary btn-sm">Aplicar</button>
    </form>
  </div>
</div>

<!-- KPI CARDS -->
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:24px">
  <?php
  $kpiData = [
    ['label'=>'Demandas',       'value'=>$fmtN((float)($kpis['total_demandas']??0)),  'sub'=>'no período',        'color'=>'#3b82f6'],
    ['label'=>'Contratos',      'value'=>$fmtN((float)($kpis['total_contratos']??0)), 'sub'=>'formalizados',      'color'=>'#22c55e'],
    ['label'=>'Faturamento',    'value'=>$fmt((float)($kpis['faturamento']??0)),       'sub'=>'valor bruto',       'color'=>'#b8945a'],
    ['label'=>'Ticket Médio',   'value'=>$fmt((float)($kpis['ticket_medio']??0)),      'sub'=>'por contrato',      'color'=>'#8b5cf6'],
    ['label'=>'Comissões',      'value'=>$fmt((float)($kpis['comissoes']??0)),         'sub'=>'geradas',           'color'=>'#f59e0b'],
  ];
  foreach ($kpiData as $k): ?>
  <div class="card" style="padding:18px 20px;border-top:3px solid <?php echo $k['color']; ?>">
    <div style="font-size:.68rem;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);font-weight:500;margin-bottom:6px"><?php echo $k['label']; ?></div>
    <div style="font-family:'Cormorant Garamond',serif;font-size:1.9rem;font-weight:300;color:<?php echo $k['color']; ?>;line-height:1"><?php echo $k['value']; ?></div>
    <div style="font-size:.7rem;color:var(--text-muted);margin-top:5px"><?php echo $k['sub']; ?></div>
  </div>
  <?php endforeach; ?>
</div>

<!-- ROW 1: Faturamento + Demandas por status -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:16px">
  <div class="card" style="padding:20px 22px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
      <div>
        <div style="font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);font-weight:500">Faturamento Mensal</div>
        <div style="font-size:.82rem;color:var(--text-muted);margin-top:2px">Contratos formalizados por mês</div>
      </div>
    </div>
    <canvas id="chartFaturamento" height="90"></canvas>
  </div>
  <div class="card" style="padding:20px 22px">
    <div style="font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);font-weight:500;margin-bottom:4px">Demandas por Status</div>
    <div style="font-size:.82rem;color:var(--text-muted);margin-bottom:14px">Distribuição no período</div>
    <canvas id="chartStatus" height="160"></canvas>
  </div>
</div>

<!-- ROW 2: Maiores Parceiros + Ticket Médio -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
  <div class="card" style="padding:20px 22px">
    <div style="font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);font-weight:500;margin-bottom:4px">Maiores Parceiros</div>
    <div style="font-size:.82rem;color:var(--text-muted);margin-bottom:14px">Por número de contratos</div>
    <canvas id="chartParceiros" height="200"></canvas>
  </div>
  <div class="card" style="padding:20px 22px">
    <div style="font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);font-weight:500;margin-bottom:4px">Ticket Médio por Mês</div>
    <div style="font-size:.82rem;color:var(--text-muted);margin-bottom:14px">Valor médio dos contratos</div>
    <canvas id="chartTicket" height="200"></canvas>
  </div>
</div>

<!-- ROW 3: Demandas Mensais + Comissões -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px">
  <div class="card" style="padding:20px 22px">
    <div style="font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);font-weight:500;margin-bottom:4px">Volume de Demandas</div>
    <div style="font-size:.82rem;color:var(--text-muted);margin-bottom:14px">Novas demandas por mês</div>
    <canvas id="chartDemandas" height="130"></canvas>
  </div>
  <div class="card" style="padding:20px 22px">
    <div style="font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);font-weight:500;margin-bottom:4px">Comissões Mensais</div>
    <div style="font-size:.82rem;color:var(--text-muted);margin-bottom:14px">Comissões geradas por mês</div>
    <canvas id="chartComissoes" height="130"></canvas>
  </div>
</div>

<!-- TABELA: Maiores parceiros -->
<?php if (!empty($maiores_parceiros)): ?>
<div class="card" style="padding:0;margin-bottom:24px">
  <div style="padding:16px 22px;border-bottom:1px solid var(--border)">
    <span style="font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);font-weight:500">Ranking de Parceiros — Detalhado</span>
  </div>
  <div style="overflow-x:auto">
    <table style="width:100%;border-collapse:collapse">
      <thead>
        <tr style="background:var(--bg)">
          <th style="padding:10px 18px;text-align:left;font-size:.7rem;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border)">#</th>
          <th style="padding:10px 18px;text-align:left;font-size:.7rem;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border)">Parceiro</th>
          <th style="padding:10px 18px;text-align:left;font-size:.7rem;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border)">Tipo</th>
          <th style="padding:10px 18px;text-align:right;font-size:.7rem;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border)">Contratos</th>
          <th style="padding:10px 18px;text-align:right;font-size:.7rem;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border)">Faturamento</th>
          <th style="padding:10px 18px;text-align:right;font-size:.7rem;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border)">Ticket Médio</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($maiores_parceiros as $i => $p): ?>
        <tr style="border-bottom:1px solid var(--border)">
          <td style="padding:12px 18px;font-size:.82rem;color:var(--text-muted);font-weight:600"><?php echo $i+1; ?></td>
          <td style="padding:12px 18px;font-size:.88rem;font-weight:500"><?php echo View::e($p['parceiro']); ?></td>
          <td style="padding:12px 18px;font-size:.82rem;color:var(--text-muted)"><?php echo View::e($p['type'] ?? '—'); ?></td>
          <td style="padding:12px 18px;font-size:.88rem;text-align:right;font-weight:600;color:#22c55e"><?php echo (int)$p['total_contratos']; ?></td>
          <td style="padding:12px 18px;font-size:.88rem;text-align:right;font-weight:500;color:var(--gold)"><?php echo $fmt((float)$p['faturamento_total']); ?></td>
          <td style="padding:12px 18px;font-size:.88rem;text-align:right;color:var(--text-muted)"><?php echo $fmt((float)$p['ticket_medio']); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<script>
Chart.defaults.font.family = "'Outfit', sans-serif";
Chart.defaults.font.size   = 11;
Chart.defaults.color       = 'rgba(12,12,10,.5)';

const gold  = '#B8945A';
const blue  = '#3b82f6';
const green = '#22c55e';
const purp  = '#8b5cf6';
const amber = '#f59e0b';
const border= 'rgba(0,0,0,.06)';

const fatMeses   = <?php echo json_encode($fatMeses); ?>;
const fatValues  = <?php echo json_encode($fatValues); ?>;
const fatTickets = <?php echo json_encode($fatTickets); ?>;
const demMeses   = <?php echo json_encode($demMeses); ?>;
const demValues  = <?php echo json_encode($demValues); ?>;
const comMeses   = <?php echo json_encode($comMeses); ?>;
const comValues  = <?php echo json_encode($comValues); ?>;
const stLabels   = <?php echo json_encode($stLabels); ?>;
const stValues   = <?php echo json_encode($stValues); ?>;
const parcNomes  = <?php echo json_encode($parcNomes); ?>;
const parcConts  = <?php echo json_encode($parcConts); ?>;
const parcFat    = <?php echo json_encode($parcFat); ?>;

const tickOpts = {ticks:{callback:v=>'R$ '+v.toLocaleString('pt-BR')},grid:{color:border}};
const noGrid   = {grid:{color:border}};
const noLegend = {plugins:{legend:{display:false}}};

// Faturamento Mensal — bar
new Chart(document.getElementById('chartFaturamento'),{
  type:'bar',
  data:{
    labels:fatMeses,
    datasets:[
      {label:'Faturamento',data:fatValues,backgroundColor:gold+'99',borderColor:gold,borderWidth:1.5,yAxisID:'y'},
      {label:'Ticket Médio',data:fatTickets,type:'line',borderColor:purp,backgroundColor:'transparent',borderWidth:2,pointRadius:3,yAxisID:'y1'},
    ]
  },
  options:{responsive:true,interaction:{mode:'index'},scales:{
    y:{...tickOpts,position:'left'},
    y1:{...tickOpts,position:'right',grid:{display:false}},
    x:noGrid
  }}
});

// Demandas por Status — donut
const stColors=['#3b82f6','#f59e0b','#8b5cf6','#06b6d4','#0ea5e9','#f97316','#ec4899','#6366f1','#14b8a6','#b8945a','#84cc16','#22c55e','#ef4444','#94a3b8','#64748b'];
new Chart(document.getElementById('chartStatus'),{
  type:'doughnut',
  data:{labels:stLabels,datasets:[{data:stValues,backgroundColor:stColors.slice(0,stLabels.length),borderWidth:1,borderColor:'#F5F2ED'}]},
  options:{responsive:true,cutout:'65%',plugins:{legend:{position:'bottom',labels:{font:{size:10},boxWidth:10,padding:8}}}}
});

// Maiores Parceiros — horizontal bar
new Chart(document.getElementById('chartParceiros'),{
  type:'bar',
  data:{
    labels:parcNomes,
    datasets:[
      {label:'Contratos',data:parcConts,backgroundColor:green+'99',borderColor:green,borderWidth:1.5,yAxisID:'y'},
    ]
  },
  options:{indexAxis:'y',responsive:true,...noLegend,scales:{
    x:{grid:{color:border},ticks:{stepSize:1}},
    y:{grid:{display:false},ticks:{font:{size:10}}}
  }}
});

// Ticket Médio — line
new Chart(document.getElementById('chartTicket'),{
  type:'line',
  data:{labels:fatMeses,datasets:[{label:'Ticket Médio',data:fatTickets,borderColor:purp,backgroundColor:purp+'18',fill:true,borderWidth:2,pointRadius:4,tension:.3}]},
  options:{responsive:true,...noLegend,scales:{y:{...tickOpts},x:noGrid}}
});

// Demandas Mensais — area
new Chart(document.getElementById('chartDemandas'),{
  type:'line',
  data:{labels:demMeses,datasets:[{label:'Demandas',data:demValues,borderColor:blue,backgroundColor:blue+'20',fill:true,borderWidth:2,pointRadius:3,tension:.3}]},
  options:{responsive:true,...noLegend,scales:{y:{...noGrid,ticks:{stepSize:1}},x:noGrid}}
});

// Comissões Mensais — bar
new Chart(document.getElementById('chartComissoes'),{
  type:'bar',
  data:{labels:comMeses,datasets:[{label:'Comissões',data:comValues,backgroundColor:amber+'99',borderColor:amber,borderWidth:1.5}]},
  options:{responsive:true,...noLegend,scales:{y:{...tickOpts},x:noGrid}}
});
</script>
