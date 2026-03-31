<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.crm')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('crm.subtitulo_lista')); ?></p>
  </div>
  <a href="/equipe/crm/novo" class="btn btn-primary"><?php echo View::e(I18n::t('crm.novo_lead')); ?></a>
</div>

<div class="card" style="margin-bottom:20px;padding:16px 20px">
  <form method="GET" action="/equipe/crm" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
    <div class="form-group" style="margin:0">
      <label><?php echo View::e(I18n::t('geral.buscar')); ?></label>
      <input type="text" name="busca" value="<?php echo View::e($busca ?? ''); ?>" placeholder="<?php echo View::e(I18n::t('geral.buscar')); ?>"/>
    </div>
    <div class="form-group" style="margin:0">
      <label><?php echo View::e(I18n::t('geral.status')); ?></label>
      <select name="status">
        <option value=""><?php echo View::e(I18n::t('geral.todos')); ?></option>
        <option value="novo" <?php echo ($filtro_status ?? '') === 'novo' ? 'selected' : ''; ?>>Novo</option>
        <option value="contatado" <?php echo ($filtro_status ?? '') === 'contatado' ? 'selected' : ''; ?>>Contatado</option>
        <option value="qualificado" <?php echo ($filtro_status ?? '') === 'qualificado' ? 'selected' : ''; ?>>Qualificado</option>
        <option value="convertido" <?php echo ($filtro_status ?? '') === 'convertido' ? 'selected' : ''; ?>>Convertido</option>
        <option value="perdido" <?php echo ($filtro_status ?? '') === 'perdido' ? 'selected' : ''; ?>>Perdido</option>
      </select>
    </div>
    <div class="form-group" style="margin:0">
      <label><?php echo View::e(I18n::t('crm.origem')); ?></label>
      <input type="text" name="origin" value="<?php echo View::e($filtro_origin ?? ''); ?>" placeholder="<?php echo View::e(I18n::t('crm.origem')); ?>"/>
    </div>
    <button type="submit" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.filtrar')); ?></button>
  </form>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('geral.nome')); ?></th>
        <th><?php echo View::e(I18n::t('auth.email')); ?></th>
        <th><?php echo View::e(I18n::t('geral.telefone')); ?></th>
        <th><?php echo View::e(I18n::t('geral.empresa')); ?></th>
        <th><?php echo View::e(I18n::t('crm.origem')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr><td colspan="7"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($items as $item): ?>
      <tr>
        <td><?php echo View::e($item['name']); ?></td>
        <td><?php echo View::e($item['email'] ?? '—'); ?></td>
        <td><?php echo View::e($item['phone'] ?? '—'); ?></td>
        <td><?php echo View::e($item['company'] ?? '—'); ?></td>
        <td><?php echo View::e($item['origin'] ?? '—'); ?></td>
        <td>
          <?php
          $badge = match($item['status'] ?? '') {
              'convertido' => 'badge-green',
              'perdido' => 'badge-red',
              'qualificado' => 'badge-gold',
              default => 'badge-blue',
          };
          ?>
          <span class="badge <?php echo $badge; ?>"><?php echo View::e($item['status']); ?></span>
        </td>
        <td>
          <a href="/equipe/crm/<?php echo (int)$item['id']; ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.ver')); ?></a>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
