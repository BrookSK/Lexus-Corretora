<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, SistemaConfig};
?>
<section class="inst-hero">
  <span class="lbl reveal"><?php echo View::e(I18n::t('pagina.status')); ?></span>
  <h1 class="disp reveal d1">Status do <em>Sistema</em></h1>
</section>
<section class="inst-section" style="text-align:center">
  <div style="display:inline-flex;align-items:center;gap:12px;padding:20px 32px;border:1px solid rgba(34,197,94,.3);background:rgba(34,197,94,.05)">
    <span style="width:12px;height:12px;border-radius:50%;background:#22c55e;display:block"></span>
    <span style="font-size:.95rem;font-weight:500;color:#166534">Todos os sistemas operacionais</span>
  </div>
  <p style="margin-top:24px;font-size:.85rem;color:rgba(12,12,10,.4)">Versão <?php echo View::e(SistemaConfig::versao()); ?></p>
</section>
