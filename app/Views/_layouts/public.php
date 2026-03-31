<?php
declare(strict_types=1);
// Layout público institucional — usado por todas as páginas públicas
// Variáveis esperadas: $conteudo (string HTML), $pageTitle, $metaDesc (opcionais)
?>
<?php require __DIR__ . '/../_partials/public/head.php'; ?>
<?php require __DIR__ . '/../_partials/public/header.php'; ?>

<?php echo $conteudo ?? ''; ?>

<?php require __DIR__ . '/../_partials/public/footer.php'; ?>
<?php require __DIR__ . '/../_partials/public/cookie-banner.php'; ?>
<?php require __DIR__ . '/../_partials/public/chatbot-widget.php'; ?>
<?php require __DIR__ . '/../_partials/public/scripts.php'; ?>
</body>
</html>
