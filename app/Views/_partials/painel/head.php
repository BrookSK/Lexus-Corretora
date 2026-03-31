<?php
declare(strict_types=1);
use LEX\Core\{View, I18n, SistemaConfig, Csrf};
$pageTitle = $pageTitle ?? SistemaConfig::nome();
?>
<!DOCTYPE html>
<html lang="<?php echo View::e(I18n::idioma()); ?>">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?php echo View::e($pageTitle); ?> — <?php echo View::e(SistemaConfig::nome()); ?></title>
<link rel="icon" href="<?php echo View::e(SistemaConfig::favicon()); ?>" type="image/x-icon"/>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/assets/css/painel.css"/>
<meta name="csrf-token" content="<?php echo View::e(Csrf::gerar()); ?>"/>
</head>
<body class="painel-body">
