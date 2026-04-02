<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

$tarefa   = $tarefa   ?? [];
$usuarios = $usuarios ?? [];

$v = fn(string $field, mixed $default = '') => View::e($tarefa[$field] ?? $default);
?>
<div class="section-header">
  <div>
    <h1 class="section-title">Editar Tarefa</h1>
    <p class="section-subtitle"><?php echo View::e($tarefa['title'] ?? ''); ?></p>
  </div>
  <a href="/equipe/tarefas" class="btn btn-secondary">← Voltar</a>
</div>

<div class="card" style="max-width:680px">
  <form method="POST" action="/equipe/tarefas/<?php echo (int)($tarefa['id'] ?? 0); ?>/editar">
    <?php echo Csrf::campo(); ?>

    <div class="form-group">
      <label>Título <span style="color:#ef4444">*</span></label>
      <input type="text" name="title" required maxlength="255" value="<?php echo $v('title'); ?>"/>
    </div>

    <div class="form-group">
      <label>Descrição</label>
      <textarea name="description" rows="3"><?php echo $v('description'); ?></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Responsável</label>
        <select name="assigned_to">
          <option value="">— Sem responsável —</option>
          <?php foreach ($usuarios as $u): ?>
          <option value="<?php echo (int)$u['id']; ?>" <?php echo (string)($tarefa['assigned_to'] ?? '') === (string)$u['id'] ? 'selected' : ''; ?>>
            <?php echo View::e($u['name']); ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Data de Vencimento</label>
        <input type="date" name="due_date" value="<?php echo !empty($tarefa['due_date']) ? substr($tarefa['due_date'], 0, 10) : ''; ?>"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Prioridade</label>
        <select name="priority">
          <?php foreach (['baixa' => 'Baixa', 'normal' => 'Normal', 'alta' => 'Alta', 'urgente' => 'Urgente'] as $val => $lbl): ?>
          <option value="<?php echo $val; ?>" <?php echo ($tarefa['priority'] ?? '') === $val ? 'selected' : ''; ?>><?php echo $lbl; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Status</label>
        <select name="status">
          <?php foreach (['pendente' => 'Pendente', 'em_andamento' => 'Em andamento', 'concluida' => 'Concluída', 'cancelada' => 'Cancelada'] as $val => $lbl): ?>
          <option value="<?php echo $val; ?>" <?php echo ($tarefa['status'] ?? '') === $val ? 'selected' : ''; ?>><?php echo $lbl; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Entidade relacionada</label>
        <select name="related_type">
          <option value="">— Nenhuma —</option>
          <?php foreach (['lead' => 'Lead', 'demanda' => 'Demanda', 'cliente' => 'Cliente', 'parceiro' => 'Parceiro', 'proposta' => 'Proposta'] as $val => $lbl): ?>
          <option value="<?php echo $val; ?>" <?php echo ($tarefa['related_type'] ?? '') === $val ? 'selected' : ''; ?>><?php echo $lbl; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>ID da entidade</label>
        <input type="number" name="related_id" min="1" value="<?php echo !empty($tarefa['related_id']) ? (int)$tarefa['related_id'] : ''; ?>" placeholder="Ex: 42"/>
      </div>
    </div>

    <?php if (!empty($tarefa['completed_at'])): ?>
    <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:16px">
      Concluída em: <?php echo date('d/m/Y H:i', strtotime($tarefa['completed_at'])); ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($tarefa['criador_nome'])): ?>
    <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:16px">
      Criada por: <?php echo View::e($tarefa['criador_nome']); ?>
      <?php if (!empty($tarefa['created_at'])): ?>
      em <?php echo date('d/m/Y', strtotime($tarefa['created_at'])); ?>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <div style="display:flex;gap:10px;margin-top:8px;align-items:center">
      <button type="submit" class="btn btn-primary">Salvar Alterações</button>
      <a href="/equipe/tarefas" class="btn btn-secondary">Cancelar</a>
      <span style="flex:1"></span>
      <form method="POST" action="/equipe/tarefas/<?php echo (int)($tarefa['id'] ?? 0); ?>/excluir" style="display:inline" onsubmit="return confirm('Excluir esta tarefa permanentemente?')">
        <?php echo Csrf::campo(); ?>
        <button type="submit" class="btn btn-danger btn-sm">Excluir Tarefa</button>
      </form>
    </div>
  </form>
</div>
