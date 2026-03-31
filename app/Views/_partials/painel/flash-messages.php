<?php
declare(strict_types=1);
use LEX\Core\View;
$flash = $_SESSION['flash'] ?? null;
if ($flash) { unset($_SESSION['flash']); }
?>
<?php if ($flash): ?>
<div class="flash-msg flash-<?php echo View::e($flash['type'] ?? 'info'); ?>">
  <?php echo View::e($flash['message'] ?? ''); ?>
  <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
</div>
<?php endif; ?>
