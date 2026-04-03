<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
$currentTab = $_GET['tab'] ?? 'todas';
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.demandas')); ?></h1>
    <p class="section-subtitle">Gerenciar demandas e oportunidades</p>
  </div>
  <a href="/equipe/demandas/nova" class="btn btn-primary"><?php echo View::e(I18n::t('demandas.nova_demanda')); ?></a>
</div>

<!-- Tabs -->
<div class="tabs-container" style="margin-bottom:24px">
  <div class="tabs">
    <a href="/equipe/demandas?tab=todas" class="tab-item<?php echo $currentTab === 'todas' ? ' active' : ''; ?>">
      Todas as Demandas
    </a>
    <a href="/equipe/demandas?tab=clientes" class="tab-item<?php echo $currentTab === 'clientes' ? ' active' : ''; ?>">
      Clientes
    </a>
    <a href="/equipe/demandas?tab=repasse" class="tab-item<?php echo $currentTab === 'repasse' ? ' active' : ''; ?>">
      Repasses
      <?php if (!empty($repassesPendentes) && $repassesPendentes > 0): ?>
        <span class="badge-notification"><?php echo $repassesPendentes; ?></span>
      <?php endif; ?>
    </a>
  </div>
</div>

<?php if ($currentTab === 'repasse'): ?>
  <?php include __DIR__ . '/demandas-repasse.php'; ?>
  <?php return; ?>
<?php endif; ?>

<?php if ($currentTab === 'clientes'): ?>
  <?php include __DIR__ . '/demandas-clientes.php'; ?>
  <?php return; ?>
<?php endif; ?>

<div class="card" style="margin-bottom:20px;padding:16px 20px">
  <form method="GET" action="/equipe/demandas" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
    <div class="form-group" style="margin:0">
      <label><?php echo View::e(I18n::t('geral.buscar')); ?></label>
      <input type="text" name="busca" value="<?php echo View::e($busca ?? ''); ?>" placeholder="<?php echo View::e(I18n::t('geral.buscar')); ?>"/>
    </div>
    <div class="form-group" style="margin:0">
      <label><?php echo View::e(I18n::t('geral.status')); ?></label>
      <select name="status">
        <option value=""><?php echo View::e(I18n::t('geral.todos')); ?></option>
        <?php foreach (['novo','em_triagem','em_estruturacao','pronto_repasse','distribuido','aguardando_respostas','recebendo_propostas','em_curadoria','apresentado_cliente','em_negociacao','contrato_formalizacao','fechado_ganho','fechado_perda','pausado','cancelado'] as $s): ?>
        <option value="<?php echo $s; ?>" <?php echo ($filtro_status ?? '') === $s ? 'selected' : ''; ?>><?php echo ucfirst(str_replace('_', ' ', $s)); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group" style="margin:0">
      <label><?php echo View::e(I18n::t('demandas.urgencia')); ?></label>
      <select name="urgency">
        <option value=""><?php echo View::e(I18n::t('geral.todos')); ?></option>
        <option value="baixa" <?php echo ($filtro_urgency ?? '') === 'baixa' ? 'selected' : ''; ?>>Baixa</option>
        <option value="media" <?php echo ($filtro_urgency ?? '') === 'media' ? 'selected' : ''; ?>>Média</option>
        <option value="alta" <?php echo ($filtro_urgency ?? '') === 'alta' ? 'selected' : ''; ?>>Alta</option>
        <option value="critica" <?php echo ($filtro_urgency ?? '') === 'critica' ? 'selected' : ''; ?>>Crítica</option>
      </select>
    </div>
    <button type="submit" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.filtrar')); ?></button>
  </form>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th><?php echo View::e(I18n::t('demandas.codigo')); ?></th>
        <th><?php echo View::e(I18n::t('geral.titulo')); ?></th>
        <th><?php echo View::e(I18n::t('sidebar.clientes')); ?></th>
        <th><?php echo View::e(I18n::t('geral.status')); ?></th>
        <th><?php echo View::e(I18n::t('demandas.urgencia')); ?></th>
        <th><?php echo View::e(I18n::t('geral.cidade')); ?></th>
        <th><?php echo View::e(I18n::t('geral.data')); ?></th>
        <th><?php echo View::e(I18n::t('geral.acoes')); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
      <tr><td colspan="8"><?php echo View::e(I18n::t('geral.nenhum_registro')); ?></td></tr>
      <?php else: foreach ($items as $item): ?>
      <tr>
        <td><a href="/equipe/demandas/<?php echo (int)$item['id']; ?>"><?php echo View::e($item['code']); ?></a></td>
        <td><?php echo View::e($item['title']); ?></td>
        <td><?php echo View::e($item['cliente_nome'] ?? '—'); ?></td>
        <td>
          <?php
          $statusBadge = match($item['status'] ?? '') {
              'fechado_ganho' => 'badge-green',
              'fechado_perda', 'cancelado' => 'badge-red',
              'novo', 'em_triagem' => 'badge-blue',
              'pausado' => 'badge-gray',
              default => 'badge-gold',
          };
          ?>
          <span class="badge <?php echo $statusBadge; ?>"><?php echo View::e($item['status']); ?></span>
        </td>
        <td>
          <?php
          $urgBadge = match($item['urgency'] ?? '') {
              'critica' => 'badge-red', 'alta' => 'badge-gold',
              'baixa' => 'badge-gray', default => 'badge-blue',
          };
          ?>
          <span class="badge <?php echo $urgBadge; ?>"><?php echo View::e($item['urgency'] ?? 'media'); ?></span>
        </td>
        <td><?php echo View::e($item['city'] ?? '—'); ?></td>
        <td><?php echo View::e($item['created_at']); ?></td>
        <td>
          <a href="/equipe/demandas/<?php echo (int)$item['id']; ?>" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.ver')); ?></a>
          <a href="/equipe/demandas/<?php echo (int)$item['id']; ?>/editar" class="btn btn-secondary btn-sm"><?php echo View::e(I18n::t('geral.editar')); ?></a>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
