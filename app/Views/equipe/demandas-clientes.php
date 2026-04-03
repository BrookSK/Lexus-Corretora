<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};

/**
 * Aba de Clientes — Demandas originadas de clientes
 * Variáveis: $items, $total, $page
 */
?>
<div class="section-header">
  <div>
    <h1 class="section-title">Demandas de Clientes</h1>
    <p class="section-subtitle">Demandas criadas diretamente por clientes</p>
  </div>
</div>

<!-- Filtros -->
<div class="card" style="margin-bottom:24px;padding:20px">
  <form method="GET" action="/equipe/demandas" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <input type="hidden" name="tab" value="clientes"/>
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
        <option value="recebendo_propostas" <?php echo ($_GET['status'] ?? '') === 'recebendo_propostas' ? 'selected' : ''; ?>>Recebendo Propostas</option>
        <option value="fechado_ganho" <?php echo ($_GET['status'] ?? '') === 'fechado_ganho' ? 'selected' : ''; ?>>Fechado (Ganho)</option>
      </select>
    </div>
    <div class="form-group" style="margin:0;min-width:150px">
      <label style="font-size:.75rem;margin-bottom:4px">Urgência</label>
      <select name="urgency">
        <option value="">Todas</option>
        <option value="baixa" <?php echo ($_GET['urgency'] ?? '') === 'baixa' ? 'selected' : ''; ?>>Baixa</option>
        <option value="media" <?php echo ($_GET['urgency'] ?? '') === 'media' ? 'selected' : ''; ?>>Média</option>
        <option value="alta" <?php echo ($_GET['urgency'] ?? '') === 'alta' ? 'selected' : ''; ?>>Alta</option>
        <option value="critica" <?php echo ($_GET['urgency'] ?? '') === 'critica' ? 'selected' : ''; ?>>Crítica</option>
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
        <th>Cliente</th>
        <th>Categoria</th>
        <th>Localização</th>
        <th>Status</th>
        <th>Urgência</th>
        <th>Data</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr>
        <td colspan="9" style="text-align:center;padding:40px;color:var(--text-muted)">
          Nenhuma demanda de cliente encontrada
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
            <div style="font-size:.88rem"><?php echo View::e($item['cliente_nome'] ?? '—'); ?></div>
            <?php if (!empty($item['cliente_email'])): ?>
            <div style="font-size:.75rem;color:var(--text-muted)"><?php echo View::e($item['cliente_email']); ?></div>
            <?php endif; ?>
          </td>
          <td><?php echo View::e($item['category'] ?? '—'); ?></td>
          <td><?php echo View::e(($item['city'] ?? '') . ', ' . ($item['state'] ?? '')); ?></td>
          <td>
            <?php
            $statusClass = match($item['status'] ?? '') {
                'fechado_ganho' => 'badge-success',
                'fechado_perda', 'cancelado' => 'badge-danger',
                'novo', 'em_triagem' => 'badge-info',
                'pausado' => 'badge-secondary',
                default => 'badge-warning',
            };
            ?>
            <span class="badge <?php echo $statusClass; ?>">
              <?php echo View::e($item['status']); ?>
            </span>
          </td>
          <td>
            <?php
            $urgencyClass = match($item['urgency'] ?? 'media') {
                'critica' => 'badge-danger',
                'alta' => 'badge-warning',
                'baixa' => 'badge-secondary',
                default => 'badge-info',
            };
            ?>
            <span class="badge <?php echo $urgencyClass; ?>">
              <?php echo View::e($item['urgency'] ?? 'media'); ?>
            </span>
          </td>
          <td style="font-size:.88rem;color:var(--text-muted)">
            <?php echo date('d/m/Y H:i', strtotime($item['created_at'])); ?>
          </td>
          <td>
            <div style="display:flex;gap:8px">
              <a href="/equipe/demandas/<?php echo (int)$item['id']; ?>" class="btn btn-sm btn-secondary">Ver</a>
              <a href="/equipe/demandas/<?php echo (int)$item['id']; ?>/editar" class="btn btn-sm btn-secondary">Editar</a>
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
    <a href="?tab=clientes&page=<?php echo $i; ?>" class="pagination-link<?php echo $active; ?>"><?php echo $i; ?></a>
  <?php endfor; ?>
</div>
<?php endif; ?>
