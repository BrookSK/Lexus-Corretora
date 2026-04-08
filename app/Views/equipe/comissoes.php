<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.comissoes')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('comissoes.subtitulo_lista')); ?></p>
  </div>
  <a href="/equipe/comissoes/nova" class="btn btn-primary"><?php echo View::e(I18n::t('comissoes.nova_comissao')); ?></a>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Tipo</th>
        <th><?php echo View::e(I18n::t('sidebar.demandas')); ?></th>
        <th><?php echo View::e(I18n::t('sidebar.parceiros')); ?></th>
        <th><?php echo View::e(I18n::t('comissoes.valor_base')); ?></th>
        <th>%</th>
        <th><?php echo View::e(I18n::t('comissoes.valor_comissao')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr><td colspan="9"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($items as $item): ?>
      <tr>
        <td><?php echo (int)$item['id']; ?></td>
        <td>
          <?php $tipo = $item['tipo'] ?? 'recebimento'; ?>
          <span class="badge <?php echo $tipo === 'recebimento' ? 'badge-green' : 'badge-red'; ?>">
            <?php echo $tipo === 'recebimento' ? 'Recebimento' : 'Pagamento'; ?>
          </span>
        </td>
        <td><a href="/equipe/demandas/<?php echo (int)$item['demanda_id']; ?>"><?php echo View::e($item['demanda_code'] ?? '#' . $item['demanda_id']); ?></a></td>
        <td><?php echo View::e($item['parceiro_nome'] ?? ($tipo === 'recebimento' ? 'Lexus (empresa)' : '—')); ?></td>
        <td><?php echo I18n::formatarMoeda($item['base_amount']); ?></td>
        <td><?php echo number_format((float)$item['commission_pct'], 2); ?>%</td>
        <td><?php echo I18n::formatarMoeda($item['commission_amount']); ?></td>
        <td>
          <?php
          $badge = match($item['status'] ?? '') {
              'recebida' => 'badge-green',
              'cancelada' => 'badge-red',
              'atrasada' => 'badge-red',
              'confirmada', 'faturada' => 'badge-blue',
              default => 'badge-gold',
          };
          ?>
          <span class="badge <?php echo $badge; ?>"><?php echo View::e($item['status']); ?></span>
        </td>
        <td>
          <a href="/equipe/comissoes/<?php echo (int)$item['id']; ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.ver')); ?></a>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php echo Csrf::campo(); ?>

<script src="/assets/js/crud-actions.js"></script>
<script>
function excluirComissao(id, event) {
  // Armazenar o evento globalmente para que excluirRegistro possa acessá-lo
  window.currentDeleteEvent = event;
  excluirRegistro(`/equipe/comissoes/${id}/excluir`, 'comissão');
}

// Adicionar botões de exclusão nas linhas
document.addEventListener('DOMContentLoaded', function() {
  const linhas = document.querySelectorAll('.table-wrap tbody tr');
  linhas.forEach(linha => {
    const link = linha.querySelector('a[href*="/equipe/comissoes/"]');
    if (!link) return;
    
    const id = link.href.match(/\/(\d+)$/)?.[1];
    if (!id) return;
    
    linha.dataset.id = id;
    const acoesCell = linha.querySelector('td:last-child');
    if (!acoesCell || acoesCell.querySelector('.btn-excluir')) return;
    
    const btnExcluir = document.createElement('button');
    btnExcluir.type = 'button';
    btnExcluir.className = 'btn btn-danger btn-sm btn-excluir';
    btnExcluir.innerHTML = '🗑️ Excluir';
    btnExcluir.style.marginLeft = '8px';
    btnExcluir.onclick = (e) => excluirComissao(id, e);
    
    acoesCell.appendChild(btnExcluir);
  });
});
</script>
