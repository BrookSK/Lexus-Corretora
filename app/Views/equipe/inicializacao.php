<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, Csrf};
?>
<div class="section-header">
  <h1 class="section-title">Inicialização do Sistema</h1>
</div>

<div class="card" style="margin-bottom:24px">
  <p style="margin-bottom:16px">Execute a inicialização para criar tabelas, migrations, seeds e diretórios necessários.</p>
  <form method="POST" action="/equipe/inicializacao">
    <?php echo Csrf::campo(); ?>
    <button type="submit" class="btn btn-primary">Executar Inicialização</button>
  </form>
</div>

<?php if (!empty($resultados)): ?>
<div class="card">
  <h3 class="card-title">Resultados</h3>
  <ul style="list-style:none;padding:0;margin-top:12px">
    <?php foreach ($resultados as $r): ?>
    <li style="padding:8px 0;border-bottom:1px solid rgba(0,0,0,.06);font-size:.88rem">✓ <?php echo View::e($r); ?></li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>
