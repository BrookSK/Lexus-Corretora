<?php
declare(strict_types=1);
use LEX\Core\View;
?>
<section class="inst-hero">
  <h1 class="disp reveal"><?php echo View::e($titulo); ?></h1>
</section>
<section class="inst-section">
  <div style="max-width:780px;margin:0 auto">
    <?php echo $conteudoHtml; ?>
  </div>
</section>
