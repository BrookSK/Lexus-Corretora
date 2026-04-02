<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

$usuarios = $usuarios ?? [];
?>
<div class="section-header">
  <div>
    <h1 class="section-title">Nova Tarefa</h1>
    <p class="section-subtitle">Preencha os dados da tarefa</p>
  </div>
  <a href="/equipe/tarefas" class="btn btn-secondary">← Voltar</a>
</div>

<div class="card" style="max-width:680px">
  <form method="POST" action="/equipe/tarefas/criar">
    <?php echo Csrf::campo(); ?>

    <div class="form-group">
      <label>Título <span style="color:#ef4444">*</span></label>
      <input type="text" name="title" required maxlength="255" placeholder="Descreva brevemente a tarefa"/>
    </div>

    <div class="form-group">
      <label>Descrição</label>
      <textarea name="description" rows="3" placeholder="Detalhes adicionais..."></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Responsável</label>
        <select name="assigned_to">
          <option value="">— Sem responsável —</option>
          <?php foreach ($usuarios as $u): ?>
          <option value="<?php echo (int)$u['id']; ?>"><?php echo View::e($u['name']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Data de Vencimento</label>
        <input type="date" name="due_date" min="<?php echo date('Y-m-d'); ?>"/>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Prioridade</label>
        <select name="priority">
          <option value="baixa">Baixa</option>
          <option value="normal" selected>Normal</option>
          <option value="alta">Alta</option>
          <option value="urgente">Urgente</option>
        </select>
      </div>
      <div class="form-group">
        <label>Status</label>
        <select name="status">
          <option value="pendente" selected>Pendente</option>
          <option value="em_andamento">Em andamento</option>
          <option value="concluida">Concluída</option>
          <option value="cancelada">Cancelada</option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Entidade relacionada</label>
        <select name="related_type">
          <option value="">— Nenhuma —</option>
          <option value="lead">Lead</option>
          <option value="demanda">Demanda</option>
          <option value="cliente">Cliente</option>
          <option value="parceiro">Parceiro</option>
          <option value="proposta">Proposta</option>
        </select>
      </div>
      <div class="form-group">
        <label>ID da entidade</label>
        <input type="number" name="related_id" min="1" placeholder="Ex: 42"/>
      </div>
    </div>

    <div style="display:flex;gap:10px;margin-top:8px">
      <button type="submit" class="btn btn-primary">Salvar Tarefa</button>
      <a href="/equipe/tarefas" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>
