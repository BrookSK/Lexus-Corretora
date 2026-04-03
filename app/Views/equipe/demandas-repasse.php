<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};

/**
 * Aba de Repasses — Demandas indicadas por parceiros
 * Variáveis: $items, $total, $page
 */
?>
<div class="section-header">
  <div>
    <h1 class="section-title">Demandas Repassadas</h1>
    <p class="section-subtitle">Indicações recebidas de parceiros</p>
  </div>
</div>

<!-- Filtros -->
<div class="card" style="margin-bottom:24px;padding:20px">
  <form method="GET" action="/equipe/demandas/repasse" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <div class="form-group" style="margin:0;min-width:200px">
      <label style="font-size:.75rem;margin-bottom:4px">Buscar</label>
      <input type="text" name="busca" placeholder="Código ou título" value="<?php echo View::e($_GET['busca'] ?? ''); ?>"/>
    </div>
    <div class="form-group" style="margin:0;min-width:150px">
      <label style="font-size:.75rem;margin-bottom:4px">Status</label>
      <select name="status">
        <option value="">Todos</option>
        <option value="novo" <?php echo ($_GET['status'] ?? '') === 'novo' ? 'selected' : ''; ?>>Novo</option>
        <option value="em_triagem" <?php echo ($_GET['status'] ?? '') === 'em_triagem' ? 'selected' : ''; ?>>Em Triagem</option>
        <option value="distribuido" <?php echo ($_GET['status'] ?? '') === 'distribuido' ? 'selected' : ''; ?>>Distribuído</option>
      </select>
    </div>
    <div class="form-group" style="margin:0;min-width:150px">
      <label style="font-size:.75rem;margin-bottom:4px">Revisão</label>
      <select name="repasse_status">
        <option value="">Todos</option>
        <option value="em_revisao" <?php echo ($_GET['repasse_status'] ?? '') === 'em_revisao' ? 'selected' : ''; ?>>Em Revisão</option>
        <option value="aprovado" <?php echo ($_GET['repasse_status'] ?? '') === 'aprovado' ? 'selected' : ''; ?>>Aprovado</option>
      </select>
    </div>
    <button type="submit" class="btn btn-secondary" style="margin:0">Filtrar</button>
  </form>
</div>

<!-- Tabela -->
<div class="card">
  <table class="data-table">
    <thead>
      <tr>
        <th>Código</th>
        <th>Título</th>
        <th>Parceiro</th>
        <th>Categoria</th>
        <th>Localização</th>
        <th>Status</th>
        <th>Revisão</th>
        <th>Data</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr>
        <td colspan="9" style="text-align:center;padding:40px;color:var(--text-muted)">
          Nenhuma demanda repassada encontrada
        </td>
      </tr>
      <?php else: ?>
        <?php foreach ($items as $item): ?>
        <tr>
          <td>
            <a href="/equipe/demandas/<?php echo (int)$item['id']; ?>" style="color:var(--primary);font-weight:500">
              <?php echo View::e($item['code']); ?>
            </a>
          </td>
          <td><?php echo View::e($item['title']); ?></td>
          <td>
            <div style="font-size:.88rem"><?php echo View::e($item['parceiro_nome'] ?? '—'); ?></div>
            <?php if (!empty($item['parceiro_email'])): ?>
            <div style="font-size:.75rem;color:var(--text-muted)"><?php echo View::e($item['parceiro_email']); ?></div>
            <?php endif; ?>
          </td>
          <td><?php echo View::e($item['category'] ?? '—'); ?></td>
          <td><?php echo View::e(($item['city'] ?? '') . ', ' . ($item['state'] ?? '')); ?></td>
          <td>
            <span class="badge badge-<?php echo View::e($item['status']); ?>">
              <?php echo View::e($item['status']); ?>
            </span>
          </td>
          <td>
            <?php if ($item['repasse_status'] === 'em_revisao'): ?>
              <span class="badge badge-warning">Em Revisão</span>
            <?php elseif ($item['repasse_status'] === 'aprovado'): ?>
              <span class="badge badge-success">Aprovado</span>
            <?php else: ?>
              <span class="badge badge-secondary">Pendente</span>
            <?php endif; ?>
          </td>
          <td style="font-size:.88rem;color:var(--text-muted)">
            <?php echo date('d/m/Y H:i', strtotime($item['created_at'])); ?>
          </td>
          <td>
            <div style="display:flex;gap:8px">
              <a href="/equipe/demandas/<?php echo (int)$item['id']; ?>" class="btn btn-sm btn-secondary">Ver</a>
              <a href="/equipe/demandas/<?php echo (int)$item['id']; ?>/editar" class="btn btn-sm btn-secondary">Editar</a>
              <?php if ($item['repasse_status'] !== 'aprovado'): ?>
              <form method="POST" action="/equipe/demandas/<?php echo (int)$item['id']; ?>/aprovar-repasse" style="display:inline">
                <?php echo \LEX\Core\Csrf::campo(); ?>
                <button type="submit" class="btn btn-sm btn-primary">Aprovar</button>
              </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php if ($total > 20): ?>
<div style="margin-top:24px;display:flex;justify-content:center">
  <?php
  $totalPages = ceil($total / 20);
  $currentPage = $page;
  for ($i = 1; $i <= $totalPages; $i++):
    $active = $i === $currentPage ? ' active' : '';
  ?>
    <a href="?page=<?php echo $i; ?>" class="pagination-link<?php echo $active; ?>"><?php echo $i; ?></a>
  <?php endfor; ?>
</div>
<?php endif; ?>
