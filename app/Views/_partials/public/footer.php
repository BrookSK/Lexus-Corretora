<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, SistemaConfig};
?>
<footer class="site-footer">
  <div class="footer-top">
    <div class="footer-brand">
      <a href="/" class="flogo">Lexus</a>
      <p class="footer-tagline">Estruturação Estratégica de Obras</p>
    </div>
    <div class="footer-col">
      <h4 class="footer-heading">Institucional</h4>
      <a href="/sobre"><?php echo View::e(I18n::t('nav.sobre')); ?></a>
      <a href="/como-funciona"><?php echo View::e(I18n::t('nav.como_funciona')); ?></a>
      <a href="/vetriks"><?php echo View::e(I18n::t('nav.vetriks')); ?></a>
      <a href="/contato"><?php echo View::e(I18n::t('nav.contato')); ?></a>
    </div>
    <div class="footer-col">
      <h4 class="footer-heading">Plataforma</h4>
      <a href="/para-clientes"><?php echo View::e(I18n::t('nav.para_clientes')); ?></a>
      <a href="/para-parceiros"><?php echo View::e(I18n::t('nav.para_parceiros')); ?></a>
      <a href="/abrir-demanda"><?php echo View::e(I18n::t('nav.abrir_demanda')); ?></a>
      <a href="/seja-parceiro"><?php echo View::e(I18n::t('parceiro.titulo')); ?></a>
    </div>
    <div class="footer-col">
      <h4 class="footer-heading">Legal</h4>
      <a href="/termos"><?php echo View::e(I18n::t('footer.termos')); ?></a>
      <a href="/privacidade"><?php echo View::e(I18n::t('footer.privacidade')); ?></a>
      <a href="/status"><?php echo View::e(I18n::t('footer.status')); ?></a>
      <a href="/changelog">Changelog</a>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="fcopy">© <?php echo date('Y'); ?> Lexus Corretora — Estruturação Estratégica de Obras</div>
    <div class="footer-nuvem">Uma empresa <a href="https://nuvemlabs.com.br" target="_blank" rel="noopener">Nuvem Labs</a></div>
  </div>
</footer>
