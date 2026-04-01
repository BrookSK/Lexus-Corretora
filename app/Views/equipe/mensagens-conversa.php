<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e($conversa['subject'] ?? I18n::t('mensagens.conversa')); ?></h1>
    <p class="section-subtitle">
      <span class="badge <?php echo ($conversa['type'] ?? '') === 'interna' ? 'badge-gray' : 'badge-blue'; ?>"><?php echo View::e($conversa['type'] ?? ''); ?></span>
      <span class="badge <?php echo ($conversa['status'] ?? '') === 'aberta' ? 'badge-green' : 'badge-gray'; ?>"><?php echo View::e($conversa['status'] ?? 'aberta'); ?></span>
    </p>
  </div>
  <a href="/equipe/mensagens" class="btn btn-secondary"><?php echo View::e(I18n::t('geral.voltar')); ?></a>
</div>

<!-- Histórico de mensagens -->
<div class="card" style="margin-bottom:24px;max-height:500px;overflow-y:auto;padding:0">
  <?php if (empty($mensagens)): ?>
    <p style="padding:28px;color:var(--text-muted);font-size:.88rem"><?php echo View::e(I18n::t('mensagens.nenhuma_mensagem')); ?></p>
  <?php else: foreach ($mensagens as $msg): ?>
    <div style="padding:16px 28px;border-bottom:1px solid var(--border);<?php echo ($msg['sender_type'] ?? '') === 'equipe' ? 'background:rgba(184,148,90,.03)' : ''; ?>">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
        <span style="font-size:.78rem;font-weight:500;color:var(--text)">
          <?php echo View::e($msg['sender_nome'] ?? $msg['sender_type']); ?>
          <span class="badge <?php echo ($msg['sender_type'] ?? '') === 'equipe' ? 'badge-gold' : 'badge-blue'; ?>" style="margin-left:6px"><?php echo View::e($msg['sender_type']); ?></span>
        </span>
        <span style="font-size:.72rem;color:var(--text-muted)"><?php echo View::e($msg['created_at']); ?></span>
      </div>
      <p style="font-size:.88rem;line-height:1.6"><?php echo nl2br(View::e($msg['body'])); ?></p>
    </div>
  <?php endforeach; endif; ?>
</div>

<!-- Formulário de envio -->
<div class="card">
  <form method="POST" action="/equipe/mensagens/enviar">
    <?php echo Csrf::campo(); ?>
    <input type="hidden" name="conversa_id" value="<?php echo (int)$conversa['id']; ?>"/>
    <div class="form-group">
      <label><?php echo View::e(I18n::t('mensagens.nova_mensagem')); ?></label>
      <textarea name="body" rows="3" required placeholder="<?php echo View::e(I18n::t('mensagens.placeholder')); ?>"></textarea>
    </div>
    <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('mensagens.enviar')); ?></button>
  </form>
</div>
