<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <div>
    <h1 class="section-title"><?php echo View::e(I18n::t('sidebar.configuracoes')); ?></h1>
    <p class="section-subtitle"><?php echo View::e(I18n::t('config.subtitulo')); ?></p>
  </div>
</div>

<div class="cards-grid">
  <a href="/equipe/configuracoes/branding" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label">Branding</div>
    <div class="card-title"><?php echo View::e(I18n::t('config.branding_desc')); ?></div>
  </a>
  <a href="/equipe/configuracoes/smtp" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label">SMTP / E-mail</div>
    <div class="card-title"><?php echo View::e(I18n::t('config.smtp_desc')); ?></div>
  </a>
  <a href="/equipe/configuracoes/seo" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label">SEO</div>
    <div class="card-title"><?php echo View::e(I18n::t('config.seo_desc')); ?></div>
  </a>
  <a href="/equipe/configuracoes/cobranca" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('config.cobranca')); ?></div>
    <div class="card-title"><?php echo View::e(I18n::t('config.cobranca_desc')); ?></div>
  </a>
  <a href="/equipe/configuracoes/notificacoes" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('config.notificacoes')); ?></div>
    <div class="card-title"><?php echo View::e(I18n::t('config.notificacoes_desc')); ?></div>
  </a>
  <a href="/equipe/configuracoes/integracao" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('config.integracao')); ?></div>
    <div class="card-title"><?php echo View::e(I18n::t('config.integracao_desc')); ?></div>
  </a>
  <a href="/equipe/configuracoes/seguranca" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('config.seguranca')); ?></div>
    <div class="card-title"><?php echo View::e(I18n::t('config.seguranca_desc')); ?></div>
  </a>
  <a href="/equipe/configuracoes/geral" class="card" style="text-decoration:none;color:inherit">
    <div class="card-label"><?php echo View::e(I18n::t('config.geral')); ?></div>
    <div class="card-title"><?php echo View::e(I18n::t('config.geral_desc')); ?></div>
  </a>
</div>
