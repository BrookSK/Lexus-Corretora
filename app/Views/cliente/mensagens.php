<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};

/**
 * Lista de conversas do cliente
 * Variáveis: $conversas (array)
 */
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar_cli.mensagens')); ?></h1>
    <p class="section-subtitle">Suas conversas com a equipe Lexus</p>
  </div>
  <a href="/cliente/mensagens/nova" class="btn btn-primary"><?php echo View::e(I18n::t('geral.novo')); ?> Mensagem</a>
</div>

<?php if (empty($conversas)): ?>
  <div class="card" style="text-align:center;padding:48px">
    <p style="color:var(--text-muted)"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></p>
  </div>
<?php else: ?>
  <div style="display:flex;flex-direction:column;gap:1px">
    <?php foreach ($conversas as $c): ?>
    <a href="/cliente/mensagens/<?php echo View::e((string)$c['id']); ?>" class="card" style="text-decoration:none;display:flex;justify-content:space-between;align-items:center;padding:20px 28px">
      <div style="flex:1;min-width:0">
        <div style="font-size:.92rem;font-weight:500;color:var(--text);margin-bottom:4px">
          <?php echo View::e($c['subject'] ?? 'Sem assunto'); ?>
        </div>
        <?php if (!empty($c['last_message'])): ?>
        <div style="font-size:.82rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:500px">
          <?php echo View::e($c['last_message']); ?>
        </div>
        <?php endif; ?>
      </div>
      <div style="flex-shrink:0;text-align:right;margin-left:24px">
        <div style="font-size:.75rem;color:var(--text-muted)">
          <?php echo View::e(date('d/m/Y H:i', strtotime($c['updated_at'] ?? $c['created_at']))); ?>
        </div>
        <?php if (!empty($c['unread'])): ?>
        <span class="badge badge-gold" style="margin-top:4px"><?php echo View::e((string)$c['unread']); ?></span>
        <?php endif; ?>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
