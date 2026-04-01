<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
require __DIR__ . '/../_partials/categorias.php';
$f = $filtros ?? [];
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.propostas')); ?></h1>
    <p class="section-subtitle"><?php echo (int)$total; ?> pré-orçamento(s) recebido(s)</p>
  </div>
</div>

<!-- Filtros -->
<form method="GET" action="/equipe/propostas" class="card" style="margin-bottom:20px;padding:20px">
  <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr 1fr 1fr auto;gap:12px;align-items:flex-end">
    <div>
      <label style="font-size:.75rem;color:var(--text-muted);display:block;margin-bottom:4px">De</label>
      <input type="date" name="date_from" value="<?php echo View::e($f['date_from'] ?? ''); ?>"
             style="width:100%;font-size:.83rem"/>
    </div>
    <div>
      <label style="font-size:.75rem;color:var(--text-muted);display:block;margin-bottom:4px">Até</label>
      <input type="date" name="date_to" value="<?php echo View::e($f['date_to'] ?? ''); ?>"
             style="width:100%;font-size:.83rem"/>
    </div>
    <div>
      <label style="font-size:.75rem;color:var(--text-muted);display:block;margin-bottom:4px">Categoria</label>
      <select name="category" style="width:100%;font-size:.83rem">
        <option value="">Todas</option>
        <?php foreach ($CATEGORIAS_NICHO as $cat): ?>
        <option value="<?php echo View::e($cat); ?>" <?php echo ($f['category'] ?? '') === $cat ? 'selected' : ''; ?>>
          <?php echo View::e($cat); ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label style="font-size:.75rem;color:var(--text-muted);display:block;margin-bottom:4px">Estado (UF)</label>
      <input type="text" name="state" value="<?php echo View::e($f['state'] ?? ''); ?>"
             placeholder="Ex: SP" maxlength="2" style="width:100%;font-size:.83rem;text-transform:uppercase"/>
    </div>
    <div>
      <label style="font-size:.75rem;color:var(--text-muted);display:block;margin-bottom:4px">Cidade</label>
      <input type="text" name="city" value="<?php echo View::e($f['city'] ?? ''); ?>"
             placeholder="Ex: São Paulo" style="width:100%;font-size:.83rem"/>
    </div>
    <div>
      <label style="font-size:.75rem;color:var(--text-muted);display:block;margin-bottom:4px">Status</label>
      <select name="status" style="width:100%;font-size:.83rem">
        <option value="">Todos</option>
        <?php foreach (['enviada','shortlist','selecionada','descartada'] as $s): ?>
        <option value="<?php echo $s; ?>" <?php echo ($f['status'] ?? '') === $s ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div style="display:flex;gap:6px">
      <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
      <a href="/equipe/propostas" class="btn btn-secondary btn-sm">Limpar</a>
    </div>
  </div>
</form>

<?php if (empty($agrupadas)): ?>
<div class="card" style="padding:32px;text-align:center;color:var(--text-muted)">
  <?php echo View::e(I18n::t('geral.nenhum_registro')); ?>
</div>
<?php else: ?>

<?php foreach ($agrupadas as $idx => $grupo): ?>
<?php $panelId = 'prop-group-' . $idx; ?>
<div class="card" style="margin-bottom:10px;padding:0;overflow:hidden">

  <!-- Cabeçalho da demanda (clicável) -->
  <button type="button" onclick="toggleGroup('<?php echo $panelId; ?>', this)"
    style="width:100%;display:flex;align-items:center;justify-content:space-between;
           background:none;border:none;padding:16px 24px;cursor:pointer;text-align:left;gap:16px">
    <div style="display:flex;align-items:center;gap:12px;min-width:0">
      <span style="font-size:.7rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;
                   color:var(--gold);white-space:nowrap;flex-shrink:0">
        <?php echo View::e($grupo['demanda_code']); ?>
      </span>
      <span style="font-size:.9rem;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
        <?php echo View::e($grupo['cliente_nome'] ?: '—'); ?>
        <span style="color:var(--text-muted);font-weight:400"> — </span>
        <?php echo View::e($grupo['demanda_title']); ?>
      </span>
      <?php if (!empty($grupo['demanda_city']) || !empty($grupo['demanda_state'])): ?>
      <span style="font-size:.75rem;color:var(--text-muted);white-space:nowrap;flex-shrink:0">
        <?php echo View::e(trim(($grupo['demanda_city'] ?? '') . ', ' . ($grupo['demanda_state'] ?? ''), ', ')); ?>
      </span>
      <?php endif; ?>
      <?php if (!empty($grupo['demanda_category'])): ?>
      <span style="font-size:.7rem;background:rgba(184,148,90,.12);color:var(--gold);
                   border-radius:4px;padding:2px 7px;white-space:nowrap;flex-shrink:0">
        <?php echo View::e($grupo['demanda_category']); ?>
      </span>
      <?php endif; ?>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-shrink:0">
      <span style="background:var(--gold);color:#fff;font-size:.7rem;font-weight:700;
                   border-radius:999px;padding:2px 9px;white-space:nowrap">
        <?php echo count($grupo['propostas']); ?> proposta(s)
      </span>
      <svg class="chevron-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
           stroke="var(--text-muted)" stroke-width="2" style="transition:transform .2s;flex-shrink:0">
        <polyline points="6 9 12 15 18 9"/>
      </svg>
    </div>
  </button>

  <!-- Tabela de propostas (colapsável) -->
  <div id="<?php echo $panelId; ?>" style="display:none;border-top:1px solid var(--border)">
    <div style="display:flex;justify-content:flex-end;padding:10px 24px 0;gap:8px">
      <a href="/equipe/demandas/<?php echo (int)$grupo['demanda_id']; ?>"
         class="btn btn-secondary btn-sm">Ver Demanda</a>
      <?php if (count($grupo['propostas']) > 1): ?>
      <a href="/equipe/propostas/comparar?demanda_id=<?php echo (int)$grupo['demanda_id']; ?>"
         class="btn btn-secondary btn-sm">Comparar</a>
      <?php endif; ?>
    </div>
    <div class="table-wrap" style="margin:0">
      <table>
        <thead>
          <tr>
            <th><?php echo View::e(I18n::t('sidebar.parceiros')); ?></th>
            <th><?php echo View::e(I18n::t('propostas.valor')); ?></th>
            <th>Prazo (dias)</th>
            <th><?php echo View::e(I18n::t('geral.status')); ?></th>
            <th><?php echo View::e(I18n::t('geral.data')); ?></th>
            <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($grupo['propostas'] as $p): ?>
          <?php
          $badge = match($p['status'] ?? '') {
              'selecionada', 'convertida' => 'badge-green',
              'descartada', 'perdida'     => 'badge-red',
              'shortlist'                 => 'badge-gold',
              default                     => 'badge-blue',
          };
          ?>
          <tr>
            <td><?php echo View::e($p['parceiro_nome'] ?? '—'); ?>
              <?php if (!empty($p['is_shortlisted'])): ?>
                <span class="badge badge-gold" style="margin-left:4px;font-size:.65rem">★</span>
              <?php endif; ?>
            </td>
            <td><?php echo I18n::formatarMoeda($p['amount']); ?></td>
            <td><?php echo (int)($p['deadline_days'] ?? 0); ?></td>
            <td><span class="badge <?php echo $badge; ?>"><?php echo View::e($p['status']); ?></span></td>
            <td style="font-size:.8rem;color:var(--text-muted)"><?php echo View::e(substr($p['created_at'], 0, 10)); ?></td>
            <td>
              <a href="/equipe/propostas/<?php echo (int)$p['id']; ?>"
                 class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.ver')); ?></a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
<?php endforeach; ?>
<?php endif; ?>

<script>
function toggleGroup(id, btn) {
  var panel = document.getElementById(id);
  var icon  = btn.querySelector('.chevron-icon');
  var open  = panel.style.display !== 'none';
  panel.style.display = open ? 'none' : 'block';
  if (icon) icon.style.transform = open ? '' : 'rotate(180deg)';
}
</script>
