<?php
declare(strict_types=1);
// Layout do painel — usado por equipe, cliente e parceiro
// Variáveis esperadas: $conteudo, $painelTipo, $pageTitle, $breadcrumbs (opcionais)
$painelTipo = $painelTipo ?? 'equipe';
?>
<?php require __DIR__ . '/../_partials/painel/head.php'; ?>

<div class="painel-wrapper">
  <?php require __DIR__ . '/../_partials/painel/sidebar.php'; ?>

  <div class="painel-main">
    <?php require __DIR__ . '/../_partials/painel/header.php'; ?>

    <main class="painel-content">
      <?php require __DIR__ . '/../_partials/painel/flash-messages.php'; ?>
      <?php echo $conteudo ?? ''; ?>
    </main>

    <?php require __DIR__ . '/../_partials/painel/footer.php'; ?>
  </div>
</div>

<script>
// Mobile sidebar toggle
const mobileToggle=document.getElementById('sidebarMobileToggle');
const sidebar=document.getElementById('sidebar');
if(mobileToggle&&sidebar){
  mobileToggle.addEventListener('click',()=>sidebar.classList.toggle('mobile-open'));
  document.addEventListener('click',e=>{
    if(!sidebar.contains(e.target)&&!mobileToggle.contains(e.target)){
      sidebar.classList.remove('mobile-open');
    }
  });
}
</script>
</body>
</html>
