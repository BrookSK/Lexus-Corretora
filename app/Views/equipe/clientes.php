<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.clientes')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('clientes.subtitulo_lista')); ?></p>
  </div>
  <div style="display:flex;gap:12px;align-items:center">
    <form method="GET" action="/equipe/clientes" style="display:flex;gap:8px">
      <div class="form-group" style="margin:0">
        <input type="text" name="busca" placeholder="<?php echo View::e(I18n::t('geral.buscar')); ?>" value="<?php echo View::e($busca ?? ''); ?>"/>
      </div>
      <button type="submit" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.buscar')); ?></button>
    </form>
    <a href="/equipe/clientes/novo" class="btn btn-primary"><?php echo View::e(I18n::t('clientes.novo_cliente')); ?></a>
  </div>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('geral.nome')); ?></th>
        <th><?php echo View::e(I18n::t('auth.email')); ?></th>
        <th><?php echo View::e(I18n::t('geral.telefone')); ?></th>
        <th><?php echo View::e(I18n::t('geral.cidade')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr><td colspan="6"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($items as $item): ?>
      <tr>
        <td><?php echo View::e($item['name']); ?></td>
        <td><?php echo View::e($item['email']); ?></td>
        <td><?php echo View::e($item['phone'] ?? '—'); ?></td>
        <td><?php echo View::e($item['city'] ?? '—'); ?></td>
        <td>
          <?php if ($item['is_active']): ?>
            <span class="badge badge-green"><?php echo View::e(I18n::t('geral.ativo')); ?></span>
          <?php else: ?>
            <span class="badge badge-gray"><?php echo View::e(I18n::t('geral.inativo')); ?></span>
          <?php endif; ?>
        </td>
        <td>
          <a href="/equipe/clientes/<?php echo (int)$item['id']; ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.ver')); ?></a>
          <a href="/equipe/clientes/<?php echo (int)$item['id']; ?>/editar" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.editar')); ?></a>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php if (!empty($total) && $total > 0): ?>
<p style="margin-top:16px;font-size:.82rem;color:var(--text-muted)"><?php echo (int)$total; ?> <?php echo View::e(I18n::t('geral.registros')); ?></p>
<?php endif; ?>
