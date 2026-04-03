<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title">Webhooks</h1>
    <p class="section-subtitle">Dispare notificações HTTP para sistemas externos a cada evento</p>
  </div>
  <button type="button" class="btn btn-primary" onclick="abrirModal()">+ Novo Webhook</button>
</div>

<!-- Lista de webhooks configurados -->
<div class="card" style="margin-bottom:24px">
  <?php if (empty($webhooks)): ?>
    <p style="color:var(--text-muted);font-size:.88rem;padding:8px 0">Nenhum webhook configurado ainda.</p>
  <?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Evento</th>
        <th>URL de Destino</th>
        <th>Descrição</th>
        <th>Status</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($webhooks as $wh): ?>
      <tr>
        <td><code style="font-size:.78rem;background:rgba(184,148,90,.08);padding:2px 8px"><?php echo View::e($wh['evento']); ?></code></td>
        <td style="font-size:.82rem;word-break:break-all;max-width:300px"><?php echo View::e($wh['url']); ?></td>
        <td style="font-size:.82rem;color:var(--text-muted)"><?php echo View::e($wh['descricao'] ?? '—'); ?></td>
        <td>
          <?php if ($wh['ativo']): ?>
            <span class="badge badge-green">Ativo</span>
          <?php else: ?>
            <span class="badge badge-gray">Inativo</span>
          <?php endif; ?>
        </td>
        <td style="display:flex;gap:8px">
          <button type="button" class="btn btn-secondary btn-sm"
            onclick="editarWebhook(<?php echo (int)$wh['id']; ?>, '<?php echo View::e(addslashes($wh['evento'])); ?>', '<?php echo View::e(addslashes($wh['url'])); ?>', '<?php echo View::e(addslashes($wh['descricao'] ?? '')); ?>', <?php echo $wh['ativo'] ? 1 : 0; ?>)">
            Editar
          </button>
          <form method="POST" action="/equipe/webhooks/<?php echo (int)$wh['id']; ?>/excluir" style="display:inline" onsubmit="return confirm('Remover este webhook?')">
            <?php echo Csrf::campo(); ?>
            <button type="submit" class="btn btn-danger btn-sm">Remover</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<!-- Logs recentes -->
<?php if (!empty($logs)): ?>
<div class="card" style="margin-bottom:24px">
  <h3 style="font-size:.88rem;font-weight:500;margin-bottom:16px">Últimos disparos</h3>
  <table>
    <thead>
      <tr><th>Data</th><th>Evento</th><th>URL</th><th>HTTP</th><th>Status</th></tr>
    </thead>
    <tbody>
      <?php foreach ($logs as $log): ?>
      <tr>
        <td style="font-size:.78rem;white-space:nowrap"><?php echo View::e($log['created_at']); ?></td>
        <td><code style="font-size:.75rem"><?php echo View::e($log['evento']); ?></code></td>
        <td style="font-size:.75rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?php echo View::e($log['url']); ?></td>
        <td style="font-size:.78rem"><?php echo View::e((string)($log['status_code'] ?? '—')); ?></td>
        <td>
          <?php if ($log['sucesso']): ?>
            <span class="badge badge-green">OK</span>
          <?php else: ?>
            <span class="badge badge-red" title="<?php echo View::e($log['erro'] ?? ''); ?>">Falha</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<!-- Modal de criação/edição -->
<div id="webhookModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:1000;align-items:center;justify-content:center">
  <div style="background:var(--white);padding:32px;width:100%;max-width:520px;max-height:90vh;overflow-y:auto">
    <h2 style="font-size:1rem;font-weight:500;margin-bottom:24px" id="modalTitle">Novo Webhook</h2>
    <form method="POST" action="/equipe/webhooks/salvar">
      <?php echo Csrf::campo(); ?>
      <input type="hidden" name="id" id="whId" value="0"/>

      <div class="form-group">
        <label>Evento *</label>
        <select name="evento" id="whEvento" required>
          <option value="">— Selecione o evento —</option>
          <?php foreach ($eventos as $slug => $desc): ?>
          <option value="<?php echo View::e($slug); ?>"><?php echo View::e($desc); ?> <span style="color:var(--text-muted)">(<?php echo View::e($slug); ?>)</span></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>URL de Destino *</label>
        <input type="text" name="url" id="whUrl" required placeholder="https://seu-sistema.com/webhook"/>
        <small style="color:var(--text-muted);font-size:.75rem">Receberá um POST com JSON contendo os dados do evento</small>
      </div>

      <div class="form-group">
        <label>Descrição (opcional)</label>
        <input type="text" name="descricao" id="whDescricao" placeholder="Ex: WhatsApp via n8n"/>
      </div>

      <div class="form-group">
        <label>Secret (opcional)</label>
        <input type="text" name="secret" id="whSecret" placeholder="Chave para validar assinatura HMAC-SHA256"/>
        <small style="color:var(--text-muted);font-size:.75rem">Se preenchido, o header <code>X-Lexus-Signature</code> será enviado</small>
      </div>

      <div class="form-group">
        <label>
          <input type="checkbox" name="ativo" id="whAtivo" value="1" checked style="width:auto;margin-right:6px"/>
          Ativo
        </label>
      </div>

      <div style="display:flex;gap:12px;margin-top:24px">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!-- Payload de referência -->
<div class="card">
  <h3 style="font-size:.88rem;font-weight:500;margin-bottom:12px">Payload enviado (exemplo)</h3>
  <pre style="font-size:.75rem;background:var(--bg);padding:16px;overflow-x:auto;line-height:1.6">{
  "evento": "nova_demanda",
  "timestamp": "2026-04-03T15:00:00-03:00",
  "sistema": "Lexus Corretora",
  "cliente_nome": "João Silva",
  "cliente_email": "joao@email.com",
  "demanda_codigo": "LEX-000001",
  "demanda_titulo": "Reforma residencial",
  "cidade": "São Paulo",
  "estado": "SP"
}</pre>
</div>

<script>
function abrirModal() {
  document.getElementById('modalTitle').textContent = 'Novo Webhook';
  document.getElementById('whId').value = '0';
  document.getElementById('whEvento').value = '';
  document.getElementById('whUrl').value = '';
  document.getElementById('whDescricao').value = '';
  document.getElementById('whSecret').value = '';
  document.getElementById('whAtivo').checked = true;
  document.getElementById('webhookModal').style.display = 'flex';
}
function editarWebhook(id, evento, url, descricao, ativo) {
  document.getElementById('modalTitle').textContent = 'Editar Webhook';
  document.getElementById('whId').value = id;
  document.getElementById('whEvento').value = evento;
  document.getElementById('whUrl').value = url;
  document.getElementById('whDescricao').value = descricao;
  document.getElementById('whSecret').value = '';
  document.getElementById('whAtivo').checked = ativo === 1;
  document.getElementById('webhookModal').style.display = 'flex';
}
function fecharModal() {
  document.getElementById('webhookModal').style.display = 'none';
}
document.getElementById('webhookModal').addEventListener('click', function(e) {
  if (e.target === this) fecharModal();
});
</script>
