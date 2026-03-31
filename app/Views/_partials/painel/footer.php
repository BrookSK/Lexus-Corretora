<?php
declare(strict_types=1);
use LEX\Core\{View, SistemaConfig};
?>
<footer class="painel-footer">
  <span><?php echo View::e(SistemaConfig::nome()); ?> v<?php echo View::e(SistemaConfig::versao()); ?></span>
  <span><?php echo View::e(SistemaConfig::copyright()); ?></span>
</footer>
