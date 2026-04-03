<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf, Settings};

$currentTab = $_GET['tab'] ?? 'branding';

// Buscar todas as settings necessárias
$settingsKeys = [
    'sistema.nome', 'sistema.slogan', 'sistema.logo', 'sistema.favicon', 'sistema.cor_primaria', 'sistema.copyright',
    'smtp.host', 'smtp.porta', 'smtp.usuario', 'smtp.senha', 'smtp.de_email', 'smtp.de_nome',
    'seo.meta_title', 'seo.meta_description', 'seo.og_image', 'seo.ga_id', 'seo.indexacao',
    'stripe.mode', 'stripe.test_publishable_key', 'stripe.test_secret_key', 'stripe.test_webhook_secret',
    'stripe.live_publishable_key', 'stripe.live_secret_key', 'stripe.live_webhook_secret',
    'asaas.mode', 'asaas.sandbox_api_key', 'asaas.sandbox_webhook_token',
    'asaas.production_api_key', 'asaas.production_webhook_token',
    'notificacoes.email_ativo', 'notificacoes.painel_ativo',
    'seguranca.2fa_obrigatorio', 'seguranca.tentativas_login', 'seguranca.bloqueio_minutos',
    'seguranca.senha_min', 'seguranca.sessao_expira',
    'sistema.idioma_padrao', 'sistema.moeda_padrao', 'sistema.timezone',
    'legal.termos', 'legal.privacidade',
    'trello.api_key', 'trello.api_token', 'trello.list_id', 'trello.list_contato',
    'trello.list_demanda', 'trello.list_parceiro',
    'comissao.empresa_pct', 'comissao.parceiro_origem_pct',
];

$settings = [];
foreach ($settingsKeys as $key) {
    $settings[$key] = Settings::obter($key, '');
}

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
    // Passar as classes como variáveis para o arquivo incluído
    $V = 'LEX\Core\View';
    $I = 'LEX\Core\I18n';
    $C = 'LEX\Core\Csrf';
    include __DIR__ . '/configuracoes-secao-content.php';
    ?>

    <div style="margin-top:24px;padding-top:24px;border-top:1px solid var(--border)">
      <button type="submit" class="btn btn-primary"><?php echo View::e(I18n::t('geral.salvar')); ?></button>
    </div>
  </form>
</div>
