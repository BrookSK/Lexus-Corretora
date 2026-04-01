<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Lista de oportunidades recebidas pelo parceiro
 * Variáveis: $oportunidades (array)
 */

$statusBadge = [
    'enviado' => 'badge-gray', 'visualizado' => 'badge-blue', 'interessado' => 'badge-gold',
    'recusado' => 'badge-red', 'sem_resposta' => 'badge-gray', 'proposta_enviada' => 'badge-green',
];

$urgenciaBadge = [
    'baixa' => 'badge-gray', 'media' => 'badge-blue', 'alta' => 'badge-gold', 'critica' => 'badge-red',
];

$filtroStatus = $_GET['status'] ?? '';
$filtroCidade = $_GET['cidade'] ?? '';
$filtroUrgencia = $_GET['urgencia'] ?? '';
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_par.oportunidades')); ?></h1>
    <p class="section-subtitle">Oportunidades de demandas recebidas</p>
  </div>
</div>

<!-- Filtros -->
<div class="card" style="margin-bottom:24px;padding:20px 28px">
  <form method="GET" action="/parceiro/oportunidades" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
    <div class="form-group" style="margin-bottom:0;flex:1;min-width:150px">
      <label>Status</label>
      <select name="status">
        <option value=""><?php echo View::e(I18n::t('geral.todos')); ?></option>
        <option value="enviado" <?php echo $filtroStatus === 'enviado' ? 'selected' : ''; ?>>Enviado</option>
        <option value="visualizado" <?php echo $filtroStatus === 'visualizado' ? 'selected' : ''; ?>>Visualizado</option>
        <option value="interessado" <?php echo $filtroStatus === 'interessado' ? 'selected' : ''; ?>>Interessado</option>
        <option value="proposta_enviada" <?php echo $filtroStatus === 'proposta_enviada' ? 'selected' : ''; ?>>Proposta Enviada</option>
        <option value="recusado" <?php echo $filtroStatus === 'recusado' ? 'selected' : ''; ?>>Recusado</option>
      </select>
    </div>
    <div class="form-group" style="margin-bottom:0;flex:1;min-width:150px">
      <label>Cidade</label>
      <input type="text" name="cidade" value="<?php echo View::e($filtroCidade); ?>" placeholder="Filtrar por cidade"/>
    </div>
    <div class="form-group" style="margin-bottom:0;flex:1;min-width:150px">
      <label><?php echo View::e(I18n::t('demanda.urgencia')); ?></label>
      <select name="urgencia">
        <option value=""><?php echo View::e(I18n::t('geral.todos')); ?></option>
        <option value="baixa" <?php echo $filtroUrgencia === 'baixa' ? 'selected' : ''; ?>>Baixa</option>
        <option value="media" <?php echo $filtroUrgencia === 'media' ? 'selected' : ''; ?>>Média</option>
        <option value="alta" <?php echo $filtroUrgencia === 'alta' ? 'selected' : ''; ?>>Alta</option>
        <option value="critica" <?php echo $filtroUrgencia === 'critica' ? 'selected' : ''; ?>>Crítica</option>
      </select>
    </div>
    <div style="display:flex;gap:8px">
      <button type="submit" class="btn btn-primary btn-sm"><?php echo View::e(I18n::t('geral.filtrar')); ?></button>
      <a href="/parceiro/oportunidades" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.limpar')); ?></a>
    </div>
  </form>
</div>

<?php if (empty($oportunidades)): ?>
  <div class="card" style="text-align:center;padding:48px">
    <p style="color:var(--text-muted)"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></p>
  </div>
<?php else: ?>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Código</th>
          <th>Título</th>
          <th>Cidade</th>
          <th><?php echo View::e(I18n::t('demanda.orcamento')); ?></th>
          <th><?php echo View::e(I18n::t('demanda.urgencia')); ?></th>
          <th>Status</th>
          <th>Data</th>
          <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($oportunidades as $o): ?>
        <tr>
          <td><strong><?php echo View::e($o['demanda_code'] ?? '—'); ?></strong></td>
          <td><?php echo View::e($o['title'] ?? '—'); ?></td>
          <td><?php echo View::e($o['city'] ?? '—'); ?></td>
          <td>
            <?php if (!empty($o['budget_min']) || !empty($o['budget_max'])): ?>
              R$ <?php echo View::e(number_format((float)($o['budget_min'] ?? 0), 0, ',', '.')); ?>
              — R$ <?php echo View::e(number_format((float)($o['budget_max'] ?? 0), 0, ',', '.')); ?>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
          <td>
            <span class="badge <?php echo $urgenciaBadge[$o['urgency'] ?? 'media'] ?? 'badge-gray'; ?>">
              <?php echo View::e(ucfirst($o['urgency'] ?? 'media')); ?>
            </span>
          </td>
          <td>
            <span class="badge <?php echo $statusBadge[$o['status']] ?? 'badge-gray'; ?>">
              <?php echo View::e(ucfirst(str_replace('_', ' ', $o['status'] ?? 'enviado'))); ?>
            </span>
          </td>
          <td><?php echo View::e(date('d/m/Y', strtotime($o['sent_at'] ?? $o['created_at'] ?? 'now'))); ?></td>
          <td style="white-space:nowrap">
            <a href="/parceiro/oportunidades/<?php echo View::e((string)$o['id']); ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.visualizar')); ?></a>
            <?php if (($o['status'] ?? '') === 'enviado' || ($o['status'] ?? '') === 'visualizado'): ?>
            <form method="POST" action="/parceiro/oportunidades/<?php echo (int)$o['id']; ?>/interesse" style="display:inline">
              <?php echo Csrf::campo(); ?>
              <button type="submit" class="btn btn-primary btn-sm">Interesse</button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
