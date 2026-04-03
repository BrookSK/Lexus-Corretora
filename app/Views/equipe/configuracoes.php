<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf, Settings};

$currentTab = $_GET['tab'] ?? 'branding';
$settings = Settings::todos();

$tabs = [
    'branding' => ['label' => 'Branding', 'icon' => '🎨'],
    'smtp' => ['label' => 'SMTP / E-mail', 'icon' => '📧'],
    'seo' => ['label' => 'SEO', 'icon' => '🔍'],
    'cobranca' => ['label' => 'Cobrança', 'icon' => '💳'],
    'notificacoes' => ['label' => 'Notificações', 'icon' => '🔔'],
    'integracao' => ['label' => 'Integrações', 'icon' => '🔗'],
    'trello' => ['label' => 'Trello', 'icon' => '📋'],
    'comissoes' => ['label' => 'Comissões', 'icon' => '💰'],
    'seguranca' => ['label' => 'Segurança', 'icon' => '🔒'],
    'geral' => ['label' => 'Geral', 'icon' => '⚙️'],
    'legal' => ['label' => 'Legal', 'icon' => '📄'],
];
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.configuracoes')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('config.subtitulo')); ?></p>
  </div>
</div>

<!-- Tabs -->
<div class="tabs-container" style="margin-bottom:24px">
  <div class="tabs" style="overflow-x:auto;white-space:nowrap">
    <?php foreach ($tabs as $slug => $tab): ?>
    <a href="/equipe/configuracoes?tab=<?php echo $slug; ?>" class="tab-item<?php echo $currentTab === $slug ? ' active' : ''; ?>">
      <span style="margin-right:6px"><?php echo $tab['icon']; ?></span>
      <?php echo View::e($tab['label']); ?>
    </a>
    <?php endforeach; ?>
  </div>
</div>

<!-- Conteúdo da aba -->
<div class="card">
  <form method="POST" action="/equipe/configuracoes/<?php echo View::e($currentTab); ?>" enctype="multipart/form-data">
    <?php echo Csrf::campo(); ?>

    <?php
    // Incluir o conteúdo da seção específica
    $secao = $currentTab;
    include __DIR__ . '/configuracoes-secao-content.php';
    ?>

    <div style="margin-top:24px;padding-top:24px;border-top:1px solid var(--border)">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
