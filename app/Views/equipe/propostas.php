<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.propostas')); ?></h1>
    <p class="section-subtitle"><?php echo $total; ?> pré-orçamento(s) recebido(s)</p>
  </div>
</div>

<?php if (empty($agrupadas)): ?>
<div class="card" style="padding:32px;text-align:center;color:var(--text-muted)">
  <?php echo View::e(I18n::t('geral.nenhum_registro')); ?>
</div>
<?php else: ?>

<?php foreach ($agrupadas as $idx => $grupo): ?>
<?php $panelId = 'prop-group-' . $idx; ?>
<div class="card" style="margin-bottom:12px;padding:0;overflow:hidden">

  <!-- Cabeçalho da demanda (clicável) -->
  <button type="button" onclick="toggleGroup('<?php echo $panelId; ?>', this)"
    style="width:100%;display:flex;align-items:center;justify-content:space-between;
           background:none;border:none;padding:18px 24px;cursor:pointer;text-align:left;gap:16px">
    <div style="display:flex;align-items:center;gap:12px;min-width:0">
      <span style="font-size:.72rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;
                   color:var(--gold);white-space:nowrap">
        <?php echo View::e($grupo['demanda_code']); ?>
      </span>
      <span style="font-size:.93rem;font-weight:500;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
        <?php echo View::e($grupo['demanda_title']); ?>
      </span>
    </div>
    <div style="display:flex;align-items:center;gap:12px;flex-shrink:0">
      <span style="background:var(--gold);color:#fff;font-size:.72rem;font-weight:700;
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
