<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title">Editar Evento: <?php echo View::e($evento['name']); ?></h1>
    <p class="section-subtitle"><?php echo View::e($evento['description']); ?></p>
  </div>
  <a href="/equipe/notificacoes" class="btn btn-secondary">Voltar</a>
</div>

<form method="POST" action="/equipe/notificacoes/<?php echo (int)$evento['id']; ?>/editar">
  <?php echo Csrf::campo(); ?>
  
  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px">Configurações</h2>
    
    <div class="form-group">
      <label style="display:flex;align-items:center;gap:8px">
        <input type="checkbox" name="is_active" value="1" <?php echo $evento['is_active'] ? 'checked' : ''; ?>/>
        <span>Ativar este evento</span>
      </label>
      <small style="color:var(--text-muted);font-size:0.75rem">
        Quando desativado, nenhuma notificação será enviada para este evento
      </small>
    </div>
  </div>

  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px">Destinatários</h2>
    
    <div style="display:flex;flex-direction:column;gap:12px">
      <label style="display:flex;align-items:center;gap:8px">
        <input type="checkbox" name="dest_admin" value="1" 
               <?php echo in_array('admin', $evento['destinatarios']) ? 'checked' : ''; ?>/>
        <span>Administrador (Equipe)</span>
      </label>
      
      <label style="display:flex;align-items:center;gap:8px">
        <input type="checkbox" name="dest_cliente" value="1"
               <?php echo in_array('cliente', $evento['destinatarios']) ? 'checked' : ''; ?>/>
        <span>Cliente</span>
      </label>
      
      <label style="display:flex;align-items:center;gap:8px">
        <input type="checkbox" name="dest_parceiro" value="1"
               <?php echo in_array('parceiro', $evento['destinatarios']) ? 'checked' : ''; ?>/>
        <span>Parceiro</span>
      </label>
    </div>
  </div>

  <div class="card" style="margin-bottom:24px;padding:32px">
    <h2 class="card-title" style="margin-bottom:20px">Template da Mensagem</h2>
    
    <div class="form-group">
      <label>Mensagem</label>
      <textarea name="template_message" rows="4" required><?php echo View::e($evento['template_message']); ?></textarea>
      <small style="color:var(--text-muted);font-size:0.75rem">
        Use variáveis como {{codigo}}, {{titulo}}, {{cliente}}, {{parceiro}}, {{valor}}, {{status}}
      </small>
    </div>
    
    <?php if (!empty($evento['available_variables'])): ?>
    <div style="margin-top:16px;padding:16px;background:var(--bg-secondary);border-radius:6px">
      <div style="font-size:0.85rem;font-weight:500;margin-bottom:8px">Variáveis disponíveis para este evento:</div>
      <div style="display:flex;flex-wrap:wrap;gap:8px">
        <?php foreach ($evento['available_variables'] as $var): ?>
          <code style="padding:4px 8px;background:var(--bg-primary);color:var(--primary);border-radius:4px;font-size:0.8rem">
            {{<?php echo View::e($var); ?>}}
          </code>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <div style="display:flex;gap:12px;justify-content:flex-end">
    <a href="/equipe/notificacoes" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
  </div>
</form>
