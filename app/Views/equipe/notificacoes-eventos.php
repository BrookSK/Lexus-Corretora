<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
?>
<div class="section-header">
  <div>
    <h1 class="section-title">Configurar Notificações</h1>
    <p class="section-subtitle">Gerenciar eventos e destinatários de notificações do sistema</p>
  </div>
  <a href="/equipe/configuracoes" class="btn btn-secondary">Voltar</a>
</div>

<div class="card">
  <table class="data-table">
    <thead>
      <tr>
        <th>Evento</th>
        <th>Descrição</th>
        <th>Status</th>
        <th>Destinatários</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($eventos as $evento): ?>
      <tr>
        <td>
          <div style="font-weight:500"><?php echo View::e($evento['name']); ?></div>
          <div style="font-size:0.75rem;color:var(--text-muted);margin-top:2px">
            <?php echo View::e($evento['slug']); ?>
          </div>
        </td>
        <td style="max-width:300px">
          <?php echo View::e($evento['description']); ?>
        </td>
        <td>
          <?php if ($evento['is_active']): ?>
            <span class="badge badge-success">Ativo</span>
          <?php else: ?>
            <span class="badge badge-secondary">Inativo</span>
          <?php endif; ?>
        </td>
        <td>
          <?php 
          $destLabels = [
            'admin' => 'Administrador',
            'cliente' => 'Cliente',
            'parceiro' => 'Parceiro',
          ];
          $dests = [];
          foreach ($evento['destinatarios'] as $dest) {
            $dests[] = $destLabels[$dest] ?? $dest;
          }
          echo View::e(implode(', ', $dests));
          ?>
        </td>
        <td>
          <a href="/equipe/notificacoes/<?php echo (int)$evento['id']; ?>/editar" class="btn btn-sm btn-secondary">
            Editar
          </a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="card" style="margin-top:24px;padding:24px">
  <h3 style="margin-bottom:16px">Variáveis Disponíveis</h3>
  <p style="color:var(--text-muted);margin-bottom:16px">
    Use as variáveis abaixo nos templates de mensagem. Elas serão substituídas pelos valores reais ao enviar a notificação.
  </p>
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px">
    <div style="padding:12px;background:var(--bg-secondary);border-radius:6px">
      <code style="color:var(--primary)">{{codigo}}</code>
      <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px">Código da demanda</div>
    </div>
    <div style="padding:12px;background:var(--bg-secondary);border-radius:6px">
      <code style="color:var(--primary)">{{titulo}}</code>
      <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px">Título da demanda</div>
    </div>
    <div style="padding:12px;background:var(--bg-secondary);border-radius:6px">
      <code style="color:var(--primary)">{{cliente}}</code>
      <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px">Nome do cliente</div>
    </div>
    <div style="padding:12px;background:var(--bg-secondary);border-radius:6px">
      <code style="color:var(--primary)">{{parceiro}}</code>
      <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px">Nome do parceiro</div>
    </div>
    <div style="padding:12px;background:var(--bg-secondary);border-radius:6px">
      <code style="color:var(--primary)">{{valor}}</code>
      <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px">Valor da proposta</div>
    </div>
    <div style="padding:12px;background:var(--bg-secondary);border-radius:6px">
      <code style="color:var(--primary)">{{status}}</code>
      <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px">Status atual</div>
    </div>
  </div>
</div>
