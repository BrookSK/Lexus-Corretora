<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

$catColors = [
    'Arquitetura'       => ['#1a1a2e','#e94560'],
    'Interiores'        => ['#0f3460','#e2b04a'],
    'Construção Civil'  => ['#16213e','#b8945a'],
    'Marcenaria'        => ['#2d1b00','#c8843a'],
    'Elétrica'          => ['#0a1628','#f0c040'],
    'Hidráulica'        => ['#001f3f','#7fdbff'],
    'Paisagismo'        => ['#1a2e1a','#5cb85c'],
    'Design'            => ['#1e0a28','#c77dff'],
    'default'           => ['#141414','#b8945a'],
];
$urgenciaLabel = ['baixa'=>'Baixa','media'=>'Média','alta'=>'Alta','critica'=>'Crítica'];
$statusLabel   = ['enviado'=>'Novo','visualizado'=>'Visualizado','interessado'=>'Interessado','recusado'=>'Recusado','proposta_enviada'=>'Proposta Enviada'];

$filtroEstado    = $filtroEstado    ?? '';
$filtroCidade    = $filtroCidade    ?? '';
$filtroCategoria = $filtroCategoria ?? '';
$filtroStatus    = $filtroStatus    ?? '';
$estados         = $estados         ?? [];
$categorias      = $categorias      ?? [];
?>

<?php
// Agrupar por categoria
$porCategoria = [];
foreach ($oportunidades as $o) {
    $cat = !empty($o['category']) ? $o['category'] : 'Outros';
    $porCategoria[$cat][] = $o;
}
?>

<style>
/* ── Filtro ─────────────────────────────────── */
.oport-filtro {
  background: var(--bg-card);
  border-bottom: 1px solid var(--border-color);
  padding: 14px 0 14px;
  margin-bottom: 32px;
  position: sticky;
  top: 0;
  z-index: 20;
}
.oport-filtro form {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  align-items: flex-end;
}
.oport-filtro .form-group { margin-bottom: 0; flex: 1; min-width: 130px; }
.oport-filtro select,
.oport-filtro input[type=text] {
  height: 38px;
  font-size: .82rem;
  padding: 0 10px;
}
/* ── Seção por categoria ─────────────────────── */
.oport-secao { margin-bottom: 40px; }
.oport-secao-titulo {
  font-size: 1.05rem;
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.oport-secao-titulo::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--border-color);
}
/* ── Scroll horizontal ───────────────────────── */
.oport-row {
  display: flex;
  gap: 16px;
  overflow-x: auto;
  padding-bottom: 10px;
  scroll-snap-type: x mandatory;
  -webkit-overflow-scrolling: touch;
}
.oport-row::-webkit-scrollbar { height: 4px; }
.oport-row::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 2px; }
/* ── Card ────────────────────────────────────── */
.oport-card {
  flex: 0 0 220px;
  scroll-snap-align: start;
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: 6px;
  overflow: hidden;
  cursor: pointer;
  transition: box-shadow .15s, transform .15s;
  display: flex;
  flex-direction: column;
  text-decoration: none;
  color: inherit;
}
.oport-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.22); transform: translateY(-2px); }
.oport-card-img {
  height: 138px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  flex-shrink: 0;
}
.oport-card-img-letter {
  font-size: 3rem;
  font-weight: 300;
  font-family: 'Cormorant Garamond', serif;
  color: rgba(255,255,255,.85);
  line-height: 1;
}
.oport-card-img-code {
  position: absolute;
  bottom: 8px;
  left: 10px;
  font-size: .66rem;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: rgba(255,255,255,.55);
}
.oport-card-urg {
  position: absolute;
  top: 8px;
  left: 8px;
  font-size: .62rem;
  padding: 2px 7px;
  border-radius: 3px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: .05em;
}
.oport-card-new {
  position: absolute;
  top: 8px;
  right: 8px;
  background: var(--gold);
  color: #000;
  font-size: .6rem;
  font-weight: 700;
  padding: 2px 6px;
  border-radius: 3px;
  text-transform: uppercase;
  letter-spacing: .06em;
}
.oport-card-body { padding: 12px 14px; flex: 1; display: flex; flex-direction: column; gap: 4px; }
.oport-card-title {
  font-size: .88rem;
  font-weight: 600;
  color: var(--text-primary);
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.oport-card-loc {
  font-size: .74rem;
  color: var(--text-muted);
  display: flex;
  align-items: center;
  gap: 4px;
  margin-top: 2px;
}
.oport-card-budget {
  font-size: .9rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-top: 6px;
}
.oport-card-budget-label {
  font-size: .7rem;
  color: var(--text-muted);
  font-weight: 400;
}
.oport-card-status {
  font-size: .68rem;
  margin-top: 4px;
}
/* urgency colors inline */
.urg-baixa  { background: rgba(120,120,120,.18); color: #888; }
.urg-media  { background: rgba(59,130,246,.18);  color: #60a5fa; }
.urg-alta   { background: rgba(184,148,90,.2);   color: var(--gold); }
.urg-critica{ background: rgba(239,68,68,.2);    color: #f87171; }
</style>

<!-- Header -->
<div class="section-header" style="margin-bottom:0">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_par.oportunidades')); ?></h1>
    <p class="section-subtitle"><?php echo count($oportunidades); ?> oportunidade<?php echo count($oportunidades) !== 1 ? 's' : ''; ?> disponíve<?php echo count($oportunidades) !== 1 ? 'is' : 'l'; ?></p>
  </div>
</div>

<!-- Filtro fixo no topo -->
<div class="oport-filtro">
  <form method="GET" action="/parceiro/oportunidades">
    <div class="form-group">
      <label style="font-size:.72rem">Estado</label>
      <select name="estado">
        <option value="">Todos os estados</option>
        <?php foreach ($estados as $uf): ?>
          <option value="<?php echo View::e($uf); ?>" <?php echo $filtroEstado === $uf ? 'selected' : ''; ?>><?php echo View::e($uf); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label style="font-size:.72rem">Cidade</label>
      <input type="text" name="cidade" value="<?php echo View::e($filtroCidade); ?>" placeholder="Ex: São Paulo"/>
    </div>
    <div class="form-group">
      <label style="font-size:.72rem">Categoria</label>
      <select name="categoria">
        <option value="">Todas</option>
        <?php foreach ($categorias as $cat): ?>
          <option value="<?php echo View::e($cat); ?>" <?php echo $filtroCategoria === $cat ? 'selected' : ''; ?>><?php echo View::e($cat); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label style="font-size:.72rem">Status</label>
      <select name="status">
        <option value="">Todos</option>
        <option value="enviado"          <?php echo $filtroStatus==='enviado'          ?'selected':''; ?>>Novo</option>
        <option value="visualizado"      <?php echo $filtroStatus==='visualizado'      ?'selected':''; ?>>Visualizado</option>
        <option value="interessado"      <?php echo $filtroStatus==='interessado'      ?'selected':''; ?>>Interessado</option>
        <option value="proposta_enviada" <?php echo $filtroStatus==='proposta_enviada' ?'selected':''; ?>>Proposta Enviada</option>
        <option value="recusado"         <?php echo $filtroStatus==='recusado'         ?'selected':''; ?>>Recusado</option>
      </select>
    </div>
    <div style="display:flex;gap:8px;flex-shrink:0;align-self:flex-end">
      <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
      <a href="/parceiro/oportunidades" class="btn btn-secondary btn-sm">Limpar</a>
    </div>
  </form>
</div>

<?php if (empty($oportunidades)): ?>
  <div class="card" style="text-align:center;padding:64px 32px">
    <p style="font-size:2rem;margin-bottom:12px">🔍</p>
    <p style="color:var(--text-muted)">Nenhuma oportunidade encontrada.</p>
    <a href="/parceiro/oportunidades" class="btn btn-secondary" style="margin-top:16px">Limpar filtros</a>
  </div>
<?php else: ?>

  <?php foreach ($porCategoria as $catNome => $itens):
    $colors = $catColors[$catNome] ?? $catColors['default'];
  ?>
  <div class="oport-secao">
    <h2 class="oport-secao-titulo">
      Oportunidades em <span style="color:var(--gold);margin-left:4px"><?php echo View::e($catNome); ?></span>
      <span style="font-size:.78rem;font-weight:400;color:var(--text-muted);margin-left:4px">(<?php echo count($itens); ?>)</span>
    </h2>

    <div class="oport-row">
      <?php foreach ($itens as $o):
        $status  = $o['status']  ?? 'enviado';
        $urgency = $o['urgency'] ?? 'media';
        $isNew   = in_array($status, ['enviado'], true);
        $bmin    = (float)($o['budget_min'] ?? 0);
        $bmax    = (float)($o['budget_max'] ?? 0);
        $letra   = mb_strtoupper(mb_substr($o['title'] ?? 'D', 0, 1));
      ?>
      <a href="/parceiro/oportunidades/<?php echo (int)$o['id']; ?>" class="oport-card">

        <!-- Imagem / placeholder -->
        <div class="oport-card-img" style="background:linear-gradient(135deg,<?php echo $colors[0]; ?> 0%,<?php echo $colors[1]; ?> 100%)">
          <span class="oport-card-img-letter"><?php echo $letra; ?></span>
          <span class="oport-card-img-code"><?php echo View::e($o['demanda_code'] ?? ''); ?></span>
          <span class="oport-card-urg urg-<?php echo View::e($urgency); ?>">
            <?php echo $urgenciaLabel[$urgency] ?? ucfirst($urgency); ?>
          </span>
          <?php if ($isNew): ?>
            <span class="oport-card-new">Novo</span>
          <?php endif; ?>
        </div>

        <!-- Corpo -->
        <div class="oport-card-body">
          <div class="oport-card-title"><?php echo View::e($o['title'] ?? '—'); ?></div>

          <?php if (!empty($o['city']) || !empty($o['state'])): ?>
          <div class="oport-card-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <?php echo View::e(($o['city'] ?? '') . ($o['state'] ? ' — ' . $o['state'] : '')); ?>
          </div>
          <?php endif; ?>

          <?php if ($bmin): ?>
          <div class="oport-card-budget">
            R$ <?php echo number_format($bmin, 0, ',', '.'); ?>
            <?php if ($bmax && $bmax > $bmin): ?>
              <span class="oport-card-budget-label"> — R$ <?php echo number_format($bmax, 0, ',', '.'); ?></span>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <div class="oport-card-status">
            <?php
            $stColors = ['enviado'=>'badge-gray','visualizado'=>'badge-blue','interessado'=>'badge-gold','recusado'=>'badge-red','proposta_enviada'=>'badge-green'];
            ?>
            <span class="badge <?php echo $stColors[$status] ?? 'badge-gray'; ?>" style="font-size:.64rem">
              <?php echo $statusLabel[$status] ?? ucfirst($status); ?>
            </span>
          </div>
        </div>

      </a>
      <?php endforeach; ?>
    </div><!-- /.oport-row -->
  </div><!-- /.oport-secao -->
  <?php endforeach; ?>

<?php endif; ?>
