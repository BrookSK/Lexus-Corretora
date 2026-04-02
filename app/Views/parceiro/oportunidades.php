<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};

$catAccentColors = [
    'Construção residencial'=>'#b8945a','Construção comercial'=>'#c8843a',
    'Reforma residencial'=>'#e2b04a','Reforma comercial'=>'#d4a017',
    'Reforma industrial'=>'#9e9e9e','Interiores'=>'#e2b04a',
    'Paisagismo'=>'#5cb85c','Projeto arquitetônico'=>'#e94560',
    'Projeto estrutural'=>'#c8a832','Projeto elétrico'=>'#f0c040',
    'Projeto hidráulico'=>'#7fdbff','Pintura'=>'#c77dff',
    'Alvenaria'=>'#b0875a','Acabamento'=>'#e2b04a',
    'Impermeabilização'=>'#4db8ff','Telhados e coberturas'=>'#e05c5c',
    'Piscinas'=>'#00c4e0','Demolição'=>'#e04444',
    'Terraplanagem'=>'#c8a040','Instalações elétricas'=>'#f0c040',
    'Instalações hidráulicas'=>'#7fdbff','Ar condicionado e climatização'=>'#5bc8e0',
    'Automação residencial'=>'#7060f0','Energia solar'=>'#f5c800',
    'Serralheria'=>'#aaaaaa','Marcenaria'=>'#c8843a',
    'Vidraçaria'=>'#80d4ff','Gesso e drywall'=>'#e0d0c0',
    'Pisos e revestimentos'=>'#d4a870','Esquadrias'=>'#3ecfb0',
];
$urgenciaLabel = ['baixa'=>'Baixa','media'=>'Média','alta'=>'Alta','critica'=>'Crítica'];

$filtroEstado   = $filtroEstado   ?? '';
$filtroCidade   = $filtroCidade   ?? '';
$filtroValorMin = $filtroValorMin ?? '';
$filtroValorMax = $filtroValorMax ?? '';
$estados        = $estados        ?? [];
$categorias     = $categorias     ?? [];
$parceiroId     = $parceiroId     ?? 0;

// Kanban: agrupar por status
$kanbanCols = [
    'enviado'         => ['label'=>'Novo',           'color'=>'#64748b', 'items'=>[]],
    'visualizado'     => ['label'=>'Visualizado',    'color'=>'#3b82f6', 'items'=>[]],
    'interessado'     => ['label'=>'Interessado',    'color'=>'#b8945a', 'items'=>[]],
    'proposta_enviada'=> ['label'=>'Proposta Enviada','color'=>'#8b5cf6','items'=>[]],
    'recusado'        => ['label'=>'Recusado',       'color'=>'#ef4444', 'items'=>[]],
];
foreach ($oportunidades as $o) {
    $s = $o['status'] ?? 'enviado';
    if (isset($kanbanCols[$s])) $kanbanCols[$s]['items'][] = $o;
}

// Serializar dados de categoria para JS
$allCats = array_values($categorias);
?>
<style>
/* ── FILTRO BAR ────────────────────────────────── */
.kb-filtros{background:var(--white);border-bottom:1px solid var(--border);padding:10px 0;position:sticky;top:0;z-index:40}
.kb-filtros form{display:flex;gap:8px;align-items:flex-end;flex-wrap:wrap}
.kb-filtros .fg{margin:0;flex:1;min-width:110px}
.kb-filtros select,.kb-filtros input{height:36px;font-size:.78rem;padding:0 9px}

/* ── CATEGORIA POPUP ───────────────────────────── */
.kb-cat-btn{position:relative;flex-shrink:0;align-self:flex-end}
.kb-cat-dot{position:absolute;top:-3px;right:-3px;width:8px;height:8px;background:#b8945a;border-radius:50%;display:none}
.kb-cat-panel{
  display:none;position:absolute;top:calc(100% + 6px);right:0;
  background:var(--white);border:1px solid var(--border);
  min-width:240px;max-height:360px;overflow-y:auto;z-index:200;
  box-shadow:0 8px 32px rgba(0,0,0,.12);padding:0
}
.kb-cat-panel.open{display:block}
.kb-cat-panel-head{
  padding:12px 14px 10px;
  display:flex;justify-content:space-between;align-items:center;
  border-bottom:1px solid var(--border);position:sticky;top:0;background:var(--white)
}
.kb-cat-panel-head span{font-size:.68rem;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);font-weight:600}
.kb-cat-panel-actions{display:flex;gap:8px;padding:8px 14px;border-top:1px solid var(--border);background:var(--white);position:sticky;bottom:0}
.kb-cat-item{
  display:flex;align-items:center;gap:10px;
  padding:9px 14px;cursor:pointer;
  border-bottom:1px solid rgba(0,0,0,.04);
  font-size:.82rem;transition:background .1s
}
.kb-cat-item:hover{background:var(--bg)}
.kb-cat-item input[type=checkbox]{accent-color:#b8945a;width:14px;height:14px;cursor:pointer}
.kb-cat-swatch{width:8px;height:8px;border-radius:50%;flex-shrink:0}

/* ── KANBAN BOARD ──────────────────────────────── */
.kb-board{
  display:flex;gap:14px;overflow-x:auto;
  padding:16px 0 24px;
  align-items:flex-start;
  min-height:60vh
}
.kb-board::-webkit-scrollbar{height:6px}
.kb-board::-webkit-scrollbar-thumb{background:var(--border);border-radius:3px}
.kb-col{
  flex:0 0 240px;
  background:var(--bg);
  border-radius:6px;
  border:1px solid var(--border);
  display:flex;flex-direction:column;
  max-height:calc(100vh - 210px)
}
.kb-col-head{
  padding:10px 14px;
  display:flex;align-items:center;gap:8px;
  border-bottom:1px solid var(--border);
  background:var(--white);border-radius:6px 6px 0 0;
  position:sticky;top:0
}
.kb-col-accent{width:3px;height:20px;border-radius:2px;flex-shrink:0}
.kb-col-label{font-size:.72rem;letter-spacing:.07em;text-transform:uppercase;font-weight:600;color:var(--text)}
.kb-col-count{margin-left:auto;background:var(--border);color:var(--text-muted);font-size:.65rem;font-weight:700;padding:2px 6px;border-radius:10px;min-width:20px;text-align:center}
.kb-col-body{padding:10px 10px 12px;overflow-y:auto;flex:1;display:flex;flex-direction:column;gap:8px}
.kb-col-body::-webkit-scrollbar{width:3px}
.kb-col-body::-webkit-scrollbar-thumb{background:var(--border)}

/* ── KANBAN CARD ───────────────────────────────── */
.kb-card{
  background:var(--white);border:1px solid var(--border);
  border-left:3px solid #b8945a;
  padding:11px 12px;text-decoration:none;color:inherit;
  display:block;transition:box-shadow .15s,transform .12s;
  cursor:pointer
}
.kb-card:hover{box-shadow:0 4px 16px rgba(0,0,0,.1);transform:translateY(-1px)}
.kb-card-top{display:flex;align-items:center;gap:5px;margin-bottom:7px}
.kb-card-urg{font-size:.58rem;padding:2px 6px;border-radius:2px;font-weight:700;text-transform:uppercase;letter-spacing:.04em}
.kb-card-new{background:#b8945a;color:#000;font-size:.58rem;font-weight:700;padding:2px 6px;border-radius:2px;text-transform:uppercase}
.kb-card-code{font-size:.6rem;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);margin-left:auto}
.kb-card-title{font-size:.82rem;font-weight:600;line-height:1.3;color:var(--text);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:5px}
.kb-card-cat{font-size:.68rem;color:var(--text-muted);margin-bottom:5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.kb-card-loc{font-size:.7rem;color:var(--text-muted);display:flex;align-items:center;gap:3px;margin-bottom:4px}
.kb-card-budget{font-size:.86rem;font-weight:700;color:var(--text);margin-top:4px}
.kb-card-budget-sub{font-size:.68rem;color:var(--text-muted);font-weight:400}
.urg-baixa{background:rgba(100,100,100,.15);color:#888}
.urg-media{background:rgba(59,130,246,.15);color:#3b82f6}
.urg-alta{background:rgba(184,148,90,.18);color:#b8945a}
.urg-critica{background:rgba(239,68,68,.18);color:#ef4444}
.kb-empty{text-align:center;padding:28px 12px;color:var(--text-muted);font-size:.75rem;opacity:.6}
</style>

<!-- HEADER -->
<div class="section-header" style="margin-bottom:8px">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_par.oportunidades')); ?></h1>
    <p class="section-subtitle" id="kb-count-label">
      <?php echo count($oportunidades); ?> oportunidade<?php echo count($oportunidades)!==1?'s':''; ?> disponíve<?php echo count($oportunidades)!==1?'is':'l'; ?>
    </p>
  </div>
</div>

<!-- FILTROS FIXOS -->
<div class="kb-filtros">
  <form method="GET" action="/parceiro/oportunidades">
    <div class="fg form-group">
      <label style="font-size:.68rem">Estado</label>
      <select name="estado">
        <option value="">Todos</option>
        <?php foreach ($estados as $uf): ?>
        <option value="<?php echo View::e($uf); ?>" <?php echo $filtroEstado===$uf?'selected':''; ?>><?php echo View::e($uf); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="fg form-group">
      <label style="font-size:.68rem">Cidade</label>
      <input type="text" name="cidade" value="<?php echo View::e($filtroCidade); ?>" placeholder="Ex: São Paulo"/>
    </div>
    <div class="fg form-group" style="max-width:130px">
      <label style="font-size:.68rem">Valor mín. (R$)</label>
      <input type="number" name="valor_min" value="<?php echo View::e($filtroValorMin); ?>" placeholder="0" min="0" step="1000"/>
    </div>
    <div class="fg form-group" style="max-width:130px">
      <label style="font-size:.68rem">Valor máx. (R$)</label>
      <input type="number" name="valor_max" value="<?php echo View::e($filtroValorMax); ?>" placeholder="sem limite" min="0" step="1000"/>
    </div>
    <div style="display:flex;gap:6px;align-self:flex-end;flex-shrink:0">
      <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
      <?php if ($filtroEstado||$filtroCidade||$filtroValorMin||$filtroValorMax): ?>
      <a href="/parceiro/oportunidades" class="btn btn-secondary btn-sm">Limpar</a>
      <?php endif; ?>
    </div>
    <!-- BOTÃO CATEGORIAS -->
    <div class="kb-cat-btn" style="align-self:flex-end">
      <button type="button" class="btn btn-secondary btn-sm" id="kb-cat-toggle" style="gap:6px">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        Categorias
      </button>
      <span class="kb-cat-dot" id="kb-cat-dot"></span>
      <div class="kb-cat-panel" id="kb-cat-panel">
        <div class="kb-cat-panel-head">
          <span>Categorias visíveis</span>
          <button type="button" id="kb-cat-close" style="background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1rem;line-height:1">✕</button>
        </div>
        <?php foreach ($allCats as $cat):
          $acc = $catAccentColors[$cat] ?? '#b8945a';
        ?>
        <label class="kb-cat-item">
          <input type="checkbox" class="kb-cat-cb" value="<?php echo View::e($cat); ?>" checked/>
          <span class="kb-cat-swatch" style="background:<?php echo $acc; ?>"></span>
          <?php echo View::e($cat); ?>
        </label>
        <?php endforeach; ?>
        <div class="kb-cat-panel-actions">
          <button type="button" id="kb-select-all" class="btn btn-secondary btn-sm" style="font-size:.68rem;padding:6px 10px">Todas</button>
          <button type="button" id="kb-clear-all" class="btn btn-secondary btn-sm" style="font-size:.68rem;padding:6px 10px">Nenhuma</button>
        </div>
      </div>
    </div>
  </form>
</div>

<?php if (empty($oportunidades)): ?>
<div class="card" style="text-align:center;padding:60px 32px;margin-top:20px">
  <p style="font-size:1.6rem;margin-bottom:10px">🔍</p>
  <p style="color:var(--text-muted)">Nenhuma oportunidade encontrada com esses filtros.</p>
  <a href="/parceiro/oportunidades" class="btn btn-secondary" style="margin-top:16px">Limpar filtros</a>
</div>
<?php else: ?>

<!-- KANBAN BOARD -->
<div class="kb-board" id="kb-board">
  <?php foreach ($kanbanCols as $colKey => $col): ?>
  <div class="kb-col" id="kb-col-<?php echo $colKey; ?>">
    <div class="kb-col-head">
      <span class="kb-col-accent" style="background:<?php echo $col['color']; ?>"></span>
      <span class="kb-col-label"><?php echo $col['label']; ?></span>
      <span class="kb-col-count" id="kb-cnt-<?php echo $colKey; ?>"><?php echo count($col['items']); ?></span>
    </div>
    <div class="kb-col-body">
      <?php if (empty($col['items'])): ?>
      <div class="kb-empty">Nenhuma oportunidade</div>
      <?php else: foreach ($col['items'] as $o):
        $urgency = $o['urgency'] ?? 'media';
        $isNew   = $colKey === 'enviado';
        $bmin    = (float)($o['budget_min'] ?? 0);
        $bmax    = (float)($o['budget_max'] ?? 0);
        $cat     = !empty($o['category']) ? $o['category'] : 'Outros';
        $acc     = $catAccentColors[$cat] ?? '#b8945a';
      ?>
      <a href="/parceiro/oportunidades/<?php echo (int)$o['id']; ?>"
         class="kb-card" data-cat="<?php echo View::e($cat); ?>"
         style="border-left-color:<?php echo $acc; ?>">
        <div class="kb-card-top">
          <span class="kb-card-urg urg-<?php echo View::e($urgency); ?>"><?php echo $urgenciaLabel[$urgency] ?? ucfirst($urgency); ?></span>
          <?php if ($isNew): ?><span class="kb-card-new">Novo</span><?php endif; ?>
          <span class="kb-card-code"><?php echo View::e($o['demanda_code'] ?? ''); ?></span>
        </div>
        <div class="kb-card-title"><?php echo View::e($o['title'] ?? '—'); ?></div>
        <div class="kb-card-cat"><?php echo View::e($cat); ?></div>
        <?php if (!empty($o['city']) || !empty($o['state'])): ?>
        <div class="kb-card-loc">
          <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <?php echo View::e(($o['city']??'').($o['state']?' — '.$o['state']:'')); ?>
        </div>
        <?php endif; ?>
        <?php if ($bmin || $bmax): ?>
        <div class="kb-card-budget">
          <?php if ($bmin): ?>R$ <?php echo number_format($bmin,0,',','.'); ?><?php endif; ?>
          <?php if ($bmax && $bmax > $bmin): ?>
          <span class="kb-card-budget-sub"> — R$ <?php echo number_format($bmax,0,',','.'); ?></span>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </a>
      <?php endforeach; endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php endif; ?>

<script>
(function(){
  const STORAGE_KEY = 'kb_cats_<?php echo (int)$parceiroId; ?>';
  const allCats     = <?php echo json_encode($allCats); ?>;

  // Load saved cats from localStorage (default: all visible)
  function loadSaved(){
    try {
      const s = localStorage.getItem(STORAGE_KEY);
      return s ? JSON.parse(s) : allCats;
    } catch(e){ return allCats; }
  }
  function save(cats){ try{ localStorage.setItem(STORAGE_KEY, JSON.stringify(cats)); }catch(e){} }

  const panel     = document.getElementById('kb-cat-panel');
  const toggleBtn = document.getElementById('kb-cat-toggle');
  const closeBtn  = document.getElementById('kb-cat-close');
  const dot       = document.getElementById('kb-cat-dot');
  const cbs       = document.querySelectorAll('.kb-cat-cb');

  // init checkboxes from saved state
  const saved = loadSaved();
  cbs.forEach(cb => { cb.checked = saved.includes(cb.value); });
  applyFilter();

  // toggle panel
  toggleBtn.addEventListener('click', e => {
    e.stopPropagation();
    panel.classList.toggle('open');
  });
  closeBtn.addEventListener('click', () => panel.classList.remove('open'));
  document.addEventListener('click', e => {
    if (!panel.contains(e.target) && e.target !== toggleBtn) panel.classList.remove('open');
  });

  // checkbox change
  cbs.forEach(cb => cb.addEventListener('change', () => { applyFilter(); save(getChecked()); }));

  document.getElementById('kb-select-all').addEventListener('click', () => {
    cbs.forEach(cb => cb.checked = true); applyFilter(); save(allCats);
  });
  document.getElementById('kb-clear-all').addEventListener('click', () => {
    cbs.forEach(cb => cb.checked = false); applyFilter(); save([]);
  });

  function getChecked(){ return [...cbs].filter(c=>c.checked).map(c=>c.value); }

  function applyFilter(){
    const active = getChecked();
    const all    = active.length === allCats.length;
    dot.style.display = all ? 'none' : 'block';

    // update counts per column
    document.querySelectorAll('.kb-col').forEach(col => {
      const colKey = col.id.replace('kb-col-','');
      const cards  = col.querySelectorAll('.kb-card');
      let vis = 0;
      cards.forEach(card => {
        const show = active.includes(card.dataset.cat);
        card.style.display = show ? 'block' : 'none';
        if (show) vis++;
      });
      const cnt = document.getElementById('kb-cnt-' + colKey);
      if (cnt) cnt.textContent = vis;
      // show empty placeholder if all hidden
      const empty = col.querySelector('.kb-empty');
      if (empty) empty.style.display = (vis === 0 && cards.length > 0) ? 'block' : 'none';
    });
  }
})();
</script>
