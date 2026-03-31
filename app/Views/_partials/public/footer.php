<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, SistemaConfig};
?>
<footer>
  <div class="flogo">Lexus</div>
  <div class="fcopy"><?php echo View::e(I18n::t('footer.copyright', ['ano' => date('Y')])); ?></div>
  <div class="flinks">
    <a href="/sobre"><?php echo View::e(I18n::t('nav.sobre')); ?></a>
    <a href="/vetriks"><?php echo View::e(I18n::t('nav.vetriks')); ?></a>
    <a href="/contato"><?php echo View::e(I18n::t('nav.contato')); ?></a>
    <a href="/termos"><?php echo View::e(I18n::t('footer.termos')); ?></a>
    <a href="/privacidade"><?php echo View::e(I18n::t('footer.privacidade')); ?></a>
    <a href="/status"><?php echo View::e(I18n::t('footer.status')); ?></a>
  </div>
</footer>
