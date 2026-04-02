<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

$filtros  = $filtros  ?? [];
$usuarios = $usuarios ?? [];
$total    = $total    ?? 0;

$prLabels = ['baixa' => 'Baixa', 'normal' => 'Normal', 'alta' => 'Alta', 'urgente' => 'Urgente'];
$stLabels = ['pendente' => 'Pendente', 'em_andamento' => 'Em andamento', 'concluida' => 'Concluída', 'cancelada' => 'Cancelada'];
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.tarefas')); ?></h1>
    <p class="section-subtitle"><?php echo $total; ?> tarefa<?php echo $total !== 1 ? 's' : ''; ?> encontrada<?php echo $total !== 1 ? 's' : ''; ?></p>
  </div>
  <a href="/equipe/tarefas/criar" class="btn btn-primary">+ Nova Tarefa</a>
</div>

<div class="card" style="margin-bottom:20px;padding:14px 18px">
  <form method="GET" action="/equipe/tarefas" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap">
    <div class="form-group" style="margin:0">
      <label>Status</label>
      <select name="status" style="min-width:140px">
        <option value="">Todos</option>
        <?php foreach ($stLabels as $v => $l): ?>
        <option value="<?php echo $v; ?>" <?php echo ($filtros['status'] ?? '') === $v ? 'selected' : ''; ?>><?php echo $l; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group" style="margin:0">
      <label>Prioridade</label>
      <select name="priority" style="min-width:130px">
        <option value="">Todas</option>
        <?php foreach ($prLabels as $v => $l): ?>
        <option value="<?php echo $v; ?>" <?php echo ($filtros['priority'] ?? '') === $v ? 'selected' : ''; ?>><?php echo $l; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group" style="margin:0">
      <label>Responsável</label>
      <select name="assigned_to" style="min-width:160px">
        <option value="">Todos</option>
        <?php foreach ($usuarios as $u): ?>
        <option value="<?php echo (int)$u['id']; ?>" <?php echo (string)($filtros['assigned_to'] ?? '') === (string)$u['id'] ? 'selected' : ''; ?>><?php echo View::e($u['name']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-secondary btn-sm">Filtrar</button>
    <?php if (!empty($filtros)): ?>
    <a href="/equipe/tarefas" class="btn btn-secondary btn-sm">Limpar</a>
    <?php endif; ?>
  </form>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Título</th>
        <th>Responsável</th>
        <th>Prioridade</th>
        <th>Status</th>
        <th>Vencimento</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr><td colspan="6" style="text-align:center;color:var(--text-muted);font-size:.82rem"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($items as $item): ?>
      <?php
        $prBadge = match($item['priority'] ?? '') {
            'urgente' => 'badge-red', 'alta' => 'badge-gold', 'baixa' => 'badge-gray', default => 'badge-blue',
        };
        $stBadge = match($item['status'] ?? '') {
            'concluida' => 'badge-green', 'cancelada' => 'badge-red', 'em_andamento' => 'badge-gold', default => 'badge-blue',
        };
        $vencido = !empty($item['due_date']) && $item['status'] !== 'concluida' && strtotime($item['due_date']) < time();
      ?>
      <tr style="<?php echo $vencido ? 'background:rgba(239,68,68,.03)' : ''; ?>">
        <td>
          <span style="font-weight:500"><?php echo View::e($item['title']); ?></span>
          <?php if (!empty($item['description'])): ?>
          <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px;max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?php echo View::e($item['description']); ?></div>
          <?php endif; ?>
        </td>
        <td style="font-size:.84rem"><?php echo View::e($item['responsavel_nome'] ?? '—'); ?></td>
        <td><span class="badge <?php echo $prBadge; ?>"><?php echo $prLabels[$item['priority'] ?? ''] ?? View::e($item['priority'] ?? 'normal'); ?></span></td>
        <td><span class="badge <?php echo $stBadge; ?>"><?php echo $stLabels[$item['status'] ?? ''] ?? View::e($item['status'] ?? 'pendente'); ?></span></td>
        <td>
          <?php if (!empty($item['due_date'])): ?>
            <span style="font-size:.82rem<?php echo $vencido ? ';color:#ef4444;font-weight:500' : ''; ?>">
              <?php echo date('d/m/Y', strtotime($item['due_date'])); ?>
              <?php if ($vencido): ?><span style="font-size:.7rem"> ● vencida</span><?php endif; ?>
            </span>
          <?php else: ?>—<?php endif; ?>
        </td>
        <td style="white-space:nowrap">
          <?php if ($item['status'] !== 'concluida'): ?>
          <form method="POST" action="/equipe/tarefas/<?php echo (int)$item['id']; ?>/status" style="display:inline">
            <?php echo Csrf::campo(); ?>
            <input type="hidden" name="status" value="concluida"/>
            <button type="submit" class="btn btn-secondary btn-sm" title="Concluir">✓</button>
          </form>
          <?php endif; ?>
          <a href="/equipe/tarefas/<?php echo (int)$item['id']; ?>/editar" class="btn btn-secondary btn-sm">Editar</a>
          <form method="POST" action="/equipe/tarefas/<?php echo (int)$item['id']; ?>/excluir" style="display:inline" onsubmit="return confirm('Excluir esta tarefa?')">
            <?php echo Csrf::campo(); ?>
            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
          </form>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
