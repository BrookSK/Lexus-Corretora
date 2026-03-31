<?php
declare(strict_types=1);
use LEX\Core\{View, I18n};
?>
<section class="inst-hero">
  <span class="lbl reveal"><?php echo View::e(I18n::t('pagina.changelog')); ?></span>
  <h1 class="disp reveal d1">Changelog</h1>
</section>
<section class="inst-section">
  <div style="max-width:780px;margin:0 auto">
    <pre style="font-family:'Outfit',sans-serif;font-size:.9rem;line-height:1.8;white-space:pre-wrap;color:rgba(12,12,10,.7)"><?php echo View::e($changelogRaw); ?></pre>
  </div>
</section>
