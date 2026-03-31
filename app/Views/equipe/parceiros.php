<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.parceiros')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('parceiros.subtitulo_lista')); ?></p>
  </div>
  <a href="/equipe/parceiros/novo" class="btn btn-primary"><?php echo View::e(I18n::t('parceiros.novo_parceiro')); ?></a>
</div>

<div class="card" style="margin-bottom:20px;padding:16px 20px">
  <form method="GET" action="/equipe/parceiros" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
    <div class="form-group" style="margin:0">
      <label><?php echo View::e(I18n::t('geral.buscar')); ?></label>
      <input type="text" name="busca" value="<?php echo View::e($busca ?? ''); ?>" placeholder="<?php echo View::e(I18n::t('geral.buscar')); ?>"/>
    </div>
    <div class="form-group" style="margin:0">
      <label><?php echo View::e(I18n::t('parceiros.tipo')); ?></label>
      <select name="type">
        <option value=""><?php echo View::e(I18n::t('geral.todos')); ?></option>
        <option value="arquiteto" <?php echo ($filtro_type ?? '') === 'arquiteto' ? 'selected' : ''; ?>>Arquiteto</option>
        <option value="construtora" <?php echo ($filtro_type ?? '') === 'construtora' ? 'selected' : ''; ?>>Construtora</option>
        <option value="engenheiro" <?php echo ($filtro_type ?? '') === 'engenheiro' ? 'selected' : ''; ?>>Engenheiro</option>
        <option value="empreiteira" <?php echo ($filtro_type ?? '') === 'empreiteira' ? 'selected' : ''; ?>>Empreiteira</option>
        <option value="prestador" <?php echo ($filtro_type ?? '') === 'prestador' ? 'selected' : ''; ?>>Prestador</option>
        <option value="fornecedor" <?php echo ($filtro_type ?? '') === 'fornecedor' ? 'selected' : ''; ?>>Fornecedor</option>
      </select>
    </div>
    <div class="form-group" style="margin:0">
      <label><?php echo View::e(I18n::t('geral.status')); ?></label>
      <select name="status">
        <option value=""><?php echo View::e(I18n::t('geral.todos')); ?></option>
        <option value="aprovado" <?php echo ($filtro_status ?? '') === 'aprovado' ? 'selected' : ''; ?>>Aprovado</option>
        <option value="vetriks_ativo" <?php echo ($filtro_status ?? '') === 'vetriks_ativo' ? 'selected' : ''; ?>>Vetriks</option>
        <option value="pendente_analise" <?php echo ($filtro_status ?? '') === 'pendente_analise' ? 'selected' : ''; ?>>Pendente</option>
        <option value="reprovado" <?php echo ($filtro_status ?? '') === 'reprovado' ? 'selected' : ''; ?>>Reprovado</option>
      </select>
    </div>
    <button type="submit" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.filtrar')); ?></button>
  </form>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('geral.nome')); ?></th>
        <th><?php echo View::e(I18n::t('parceiros.tipo')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th>Score</th>
        <th>Vetriks</th>
        <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr><td colspan="6"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($items as $item): ?>
      <tr>
        <td><?php echo View::e($item['name']); ?></td>
        <td><?php echo View::e($item['type']); ?></td>
        <td>
          <?php
          $statusBadge = match($item['status'] ?? '') {
              'aprovado', 'vetriks_ativo' => 'badge-green',
              'reprovado', 'suspenso' => 'badge-red',
              'pendente_analise', 'em_qualificacao' => 'badge-gold',
              default => 'badge-gray',
          };
          ?>
          <span class="badge <?php echo $statusBadge; ?>"><?php echo View::e($item['status']); ?></span>
        </td>
        <td><?php echo (int)($item['score'] ?? 0); ?></td>
        <td>
          <?php if ($item['is_vetriks'] ?? false): ?>
            <span class="badge badge-gold">Vetriks ✓</span>
          <?php else: ?>
            <span class="badge badge-gray">—</span>
          <?php endif; ?>
        </td>
        <td>
          <a href="/equipe/parceiros/<?php echo (int)$item['id']; ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.ver')); ?></a>
          <a href="/equipe/parceiros/<?php echo (int)$item['id']; ?>/editar" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.editar')); ?></a>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
